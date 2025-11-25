<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
    </head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="#">BangKyd ERP</a>
            <div class="ms-auto d-flex gap-2"></div>
        </div>
    </nav>
    <main class="container py-4" style="max-width: 520px;">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3">Log in</h1>
                <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus class="form-control" />
                @error('username')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" required class="form-control" />
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" />
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary">Log in</button>
            </div>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+kbeLJwWJknhk+aoCA8DmF5asJ5AZt0pOEtpJR/YWZLxE+nobVht5cVbE+1WVP" crossorigin="anonymous"></script>
</body>
</html>

