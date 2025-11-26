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
            background: url('{{ asset('img/BG.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }

        /* Dark overlay for better glass effect visibility */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 0;
        }

        /* Floating shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 15s ease-in-out infinite;
        }

        .shape1 {
            width: 200px;
            height: 200px;
            background: white;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape2 {
            width: 150px;
            height: 150px;
            background: white;
            bottom: 15%;
            right: 15%;
            animation-delay: 5s;
        }

        .shape3 {
            width: 100px;
            height: 100px;
            background: white;
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

        /* Glass morphism card */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            padding: 35px 30px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 45px 0 rgba(31, 38, 135, 0.5);
        }

        /* Logo section */
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
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-title {
            color: white;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .logo-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.85rem;
            font-weight: 400;
        }

        /* Form styles */
        .form-label {
            color: white;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .glass-input {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 12px 18px;
            color: white;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            color: white;
            outline: none;
        }

        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .glass-input:-webkit-autofill,
        .glass-input:-webkit-autofill:hover,
        .glass-input:-webkit-autofill:focus {
            -webkit-text-fill-color: white;
            -webkit-box-shadow: 0 0 0px 1000px rgba(255, 255, 255, 0.2) inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* Checkbox */
        .glass-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .glass-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: white;
        }

        .glass-checkbox label {
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            margin: 0;
        }

        /* Button */
        .glass-button {
            width: 100%;
            padding: 12px;
            background: white;
            color: #fa709a;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .glass-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.95);
        }

        .glass-button:active {
            transform: translateY(0);
        }

        /* Error message */
        .error-message {
            background: rgba(220, 53, 69, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: white;
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
            color: white;
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
            color: rgba(255, 255, 255, 0.6);
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

