<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - BangKyd ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('{{ asset('img/bg.svg') }}') no-repeat center center fixed;
            background-size: cover;
            background-color: #f2f2f2;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                linear-gradient(rgba(0, 0, 0, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 0, 0, 0.04) 1px, transparent 1px);
            background-size: 48px 48px;
            z-index: 0;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.06;
            animation: float 18s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        .shape1 {
            width: 200px;
            height: 200px;
            background: #000;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape2 {
            width: 150px;
            height: 150px;
            background: #000;
            bottom: 15%;
            right: 15%;
            animation-delay: 5s;
        }

        .shape3 {
            width: 100px;
            height: 100px;
            background: #000;
            top: 50%;
            left: 80%;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 22px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.14),
                0 2px 10px rgba(0, 0, 0, 0.06);
            padding: 35px 30px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 26px 80px rgba(0, 0, 0, 0.18),
                0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 22px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-title {
            color: #0f0f0f;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 0.3px;
        }

        .logo-subtitle {
            color: rgba(0, 0, 0, 0.6);
            font-size: 0.85rem;
            font-weight: 400;
        }

        .form-label {
            color: rgba(0, 0, 0, 0.82);
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.9px;
        }

        .glass-input {
            background: rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.14);
            border-radius: 12px;
            padding: 12px 18px;
            color: #101010;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(0, 0, 0, 0.65);
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08);
            color: #101010;
            outline: none;
        }

        .glass-input::placeholder {
            color: rgba(0, 0, 0, 0.45);
        }

        .glass-input:-webkit-autofill,
        .glass-input:-webkit-autofill:hover,
        .glass-input:-webkit-autofill:focus {
            -webkit-text-fill-color: #101010;
            -webkit-box-shadow: 0 0 0px 1000px rgba(0, 0, 0, 0.03) inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        .glass-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .glass-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #111;
        }

        .glass-checkbox label {
            color: rgba(0, 0, 0, 0.75);
            font-size: 0.9rem;
            cursor: pointer;
            margin: 0;
        }

        .glass-button {
            width: 100%;
            padding: 12px;
            background: #111;
            color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        }

        .glass-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.22);
            background: #000;
        }

        .glass-button:active {
            transform: translateY(0);
        }

        .error-message {
            background: rgba(220, 53, 69, 0.08);
            border: 1px solid rgba(220, 53, 69, 0.22);
            color: #a00014;
            padding: 12px 15px;
            border-radius: 10px;
            margin-top: 8px;
            font-size: 0.85rem;
        }

        /* Back to home link */
        .back-home {
            text-align: center;
            margin-top: 20px;
        }

        .back-home a {
            color: #111;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-home a:hover {
            opacity: 0.8;
            transform: translateX(-5px);
        }

        /* Input group with icon */
        .input-group-glass {
            position: relative;
        }

        .input-group-glass .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(0, 0, 0, 0.55);
            font-size: 1.1rem;
            pointer-events: none;
        }

        .input-group-glass .glass-input {
            padding-left: 45px;
        }

        @media (max-width: 576px) {
            .glass-card {
                padding: 40px 25px;
                border-radius: 20px;
            }
            
            .logo-title {
                font-size: 1.5rem;
            }
            
            .login-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="shape shape1"></div>
    <div class="shape shape2"></div>
    <div class="shape shape3"></div>

    <div class="login-container">
        <div class="glass-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="{{ asset('img/BangKydLogo.png') }}" alt="BangKyd Logo">
                </div>
                <h1 class="logo-title">BangKyd</h1>
                <p class="logo-subtitle">Jersey Production Management</p>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group-glass">
                        <i class="bi bi-person-fill input-icon"></i>
                        <input 
                            id="username" 
                            name="username" 
                            type="text" 
                            value="{{ old('username') }}" 
                            required 
                            autofocus 
                            class="form-control glass-input" 
                            placeholder="Enter your username"
                        />
                    </div>
                    @error('username')
                        <div class="error-message">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group-glass">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="form-control glass-input" 
                            placeholder="Enter your password"
                        />
                    </div>
                    @error('password')
                        <div class="error-message">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="glass-checkbox">
                        <input type="checkbox" name="remember" id="remember" />
                        <label for="remember">Remember me for 30 days</label>
                    </div>
                </div>

                <button type="submit" class="glass-button">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Log In
                </button>
            </form>


        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+kbeLJwWJknhk+aoCA8DmF5asJ5AZt0pOEtpJR/YWZLxE+nobVht5cVbE+1WVP" crossorigin="anonymous"></script>
</body>
</html>

