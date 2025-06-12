<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Admin</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('/admin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/assets/css/kaiadmin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/assets/css/kaiadmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />

    <style>
        body {
            background: linear-gradient(135deg, #3f51b5, #5a55ae);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            max-width: 420px;
            width: 100%;
            padding: 40px 35px;
        }

        .login-logo a {
            font-size: 2rem;
            font-weight: 700;
            color: #3f51b5;
            text-decoration: none;
        }

        .login-box-msg {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #3f51b5;
            box-shadow: 0 0 0 0.2rem rgba(63, 81, 181, 0.2);
        }

        .btn-primary {
            border-radius: 8px;
            width: 100%;
            padding: 10px;
        }

        .input-group-text {
            background-color: #f1f1f1;
            border: none;
            border-radius: 0 8px 8px 0;
        }

        .toggle-password {
            cursor: pointer;
        }

        .form-check-label {
            font-weight: 500;
        }

        .forgot-password {
            font-size: 0.9rem;
        }

        .alert {
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="text-center login-logo mb-4">
        <a href="{{ url('/') }}">Admin</a>
    </div>

    <div class="text-center mb-3">
        <p class="login-box-msg">Sign in to your dashboard</p>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Flash Error --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST" autocomplete="off" novalidate>
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <div class="input-group">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                >
                <span class="input-group-text toggle-password" onclick="togglePassword()">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </span>
            </div>
        </div>

        {{-- Remember & Submit --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    class="form-check-input"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mb-2">
            <span class="spinner-border spinner-border-sm d-none" id="loginSpinner" role="status" aria-hidden="true"></span>
            <span id="loginText">Sign In</span>
        </button>

        {{-- Forgot Password --}}
        @if (Route::has('password.request'))
            <div class="text-end forgot-password">
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            </div>
        @endif
    </form>
</div>

<!-- Scripts -->
<script src="{{ asset('admin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/kaiadmin.min.js') }}"></script>

<script>
    function togglePassword() {
        const password = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");
        if (password.type === "password") {
            password.type = "text";
            toggleIcon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            password.type = "password";
            toggleIcon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }

    document.querySelector('form').addEventListener('submit', function () {
        document.getElementById('loginSpinner').classList.remove('d-none');
        document.getElementById('loginText').textContent = 'Signing In...';
    });
</script>

</body>
</html>
