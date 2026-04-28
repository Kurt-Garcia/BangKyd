<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $request->session()->put('pending_2fa_user_id', $user->id);
            $request->session()->put('pending_2fa_remember', $request->boolean('remember'));
            $request->session()->forget([
                'pending_2fa_cellphone_verified',
                'pending_2fa_otp_hash',
                'pending_2fa_otp_expires_at',
                'pending_2fa_otp_sent_at',
            ]);

            return redirect()->route('two-factor.cellphone');
        }

        return back()->withErrors([
            'username' => 'Invalid username or password.',
        ])->onlyInput('username');
    }

    public function showTwoFactorCellphoneForm(Request $request)
    {
        $user = $this->pendingTwoFactorUser($request);
        if (! $user) {
            return redirect()->route('login');
        }

        return view('auth.login', [
            'twoFactorStep' => 'cellphone',
            'twoFactorEmail' => $user->email,
        ]);
    }

    public function verifyTwoFactorCellphone(Request $request)
    {
        $user = $this->pendingTwoFactorUser($request);
        if (! $user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'cellphone' => ['required', 'string', 'max:30'],
        ]);

        if (! $this->cellphonesMatch($user->cellphone, $validated['cellphone'])) {
            return back()->withErrors([
                'cellphone' => 'Cellphone number does not match our records.',
            ]);
        }

        $request->session()->put('pending_2fa_cellphone_verified', true);

        $otp = (string) random_int(100000, 999999);
        $otpHash = hash_hmac('sha256', $otp, (string) config('app.key'));
        $expiresAt = now()->addMinutes(10)->timestamp;

        $request->session()->put('pending_2fa_otp_hash', $otpHash);
        $request->session()->put('pending_2fa_otp_expires_at', $expiresAt);
        $request->session()->put('pending_2fa_otp_sent_at', now()->timestamp);

        Mail::raw("Your OTP code is: {$otp}\n\nThis code will expire in 10 minutes.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your OTP Code');
        });

        return redirect()->route('two-factor.otp')->with('otp_sent', true);
    }

    public function showTwoFactorOtpForm(Request $request)
    {
        $user = $this->pendingTwoFactorUser($request);
        if (! $user) {
            return redirect()->route('login');
        }

        if (! $request->session()->get('pending_2fa_cellphone_verified')) {
            return redirect()->route('two-factor.cellphone');
        }

        return view('auth.login', [
            'twoFactorStep' => 'otp',
            'twoFactorEmail' => $user->email,
        ]);
    }

    public function resendTwoFactorOtp(Request $request)
    {
        $user = $this->pendingTwoFactorUser($request);
        if (! $user) {
            return redirect()->route('login');
        }

        if (! $request->session()->get('pending_2fa_cellphone_verified')) {
            return redirect()->route('two-factor.cellphone');
        }

        $lastSentAt = (int) $request->session()->get('pending_2fa_otp_sent_at', 0);
        if ($lastSentAt && now()->timestamp - $lastSentAt < 60) {
            return back()->withErrors([
                'otp' => 'Please wait a moment before requesting a new OTP.',
            ]);
        }

        $otp = (string) random_int(100000, 999999);
        $otpHash = hash_hmac('sha256', $otp, (string) config('app.key'));
        $expiresAt = now()->addMinutes(10)->timestamp;

        $request->session()->put('pending_2fa_otp_hash', $otpHash);
        $request->session()->put('pending_2fa_otp_expires_at', $expiresAt);
        $request->session()->put('pending_2fa_otp_sent_at', now()->timestamp);

        Mail::raw("Your OTP code is: {$otp}\n\nThis code will expire in 10 minutes.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your OTP Code');
        });

        return back()->with('otp_sent', true);
    }

    public function verifyTwoFactorOtp(Request $request)
    {
        $user = $this->pendingTwoFactorUser($request);
        if (! $user) {
            return redirect()->route('login');
        }

        if (! $request->session()->get('pending_2fa_cellphone_verified')) {
            return redirect()->route('two-factor.cellphone');
        }

        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $expiresAt = (int) $request->session()->get('pending_2fa_otp_expires_at', 0);
        if (! $expiresAt || now()->timestamp > $expiresAt) {
            return back()->withErrors([
                'otp' => 'OTP has expired. Please request a new one.',
            ]);
        }

        $expectedHash = (string) $request->session()->get('pending_2fa_otp_hash', '');
        $providedHash = hash_hmac('sha256', $validated['otp'], (string) config('app.key'));
        if (! hash_equals($expectedHash, $providedHash)) {
            return back()->withErrors([
                'otp' => 'Invalid OTP.',
            ]);
        }

        $remember = (bool) $request->session()->get('pending_2fa_remember', false);
        $this->clearTwoFactorSession($request);

        Auth::loginUsingId($user->id, $remember);
        $request->session()->regenerate();

        ActivityLog::log('login', "User logged in: {$user->name}");

        return redirect()->intended('/dashboard');
    }

    public function cancelTwoFactor(Request $request)
    {
        $this->clearTwoFactorSession($request);

        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function pendingTwoFactorUser(Request $request): ?User
    {
        $userId = $request->session()->get('pending_2fa_user_id');
        if (! $userId) {
            return null;
        }

        return User::find($userId);
    }

    private function clearTwoFactorSession(Request $request): void
    {
        $request->session()->forget([
            'pending_2fa_user_id',
            'pending_2fa_remember',
            'pending_2fa_cellphone_verified',
            'pending_2fa_otp_hash',
            'pending_2fa_otp_expires_at',
            'pending_2fa_otp_sent_at',
        ]);
    }

    private function cellphonesMatch(?string $storedCellphone, string $providedCellphone): bool
    {
        if (! $storedCellphone) {
            return false;
        }

        $normalize = function (string $value): string {
            return preg_replace('/\D+/', '', $value) ?? '';
        };

        $stored = $normalize($storedCellphone);
        $provided = $normalize($providedCellphone);

        if ($stored === '' || $provided === '') {
            return false;
        }

        return $stored === $provided;
    }
}
