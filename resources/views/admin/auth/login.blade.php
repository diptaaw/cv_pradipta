<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login — Control Room</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .admin-login-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 20px;
            background: rgba(12, 10, 35, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            box-shadow: 
                0 20px 50px rgba(0, 0, 0, 0.3),
                0 0 40px rgba(123, 97, 255, 0.1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(123, 97, 255, 0.05) 0%, transparent 60%);
            pointer-events: none;
        }

        .login-header {
            text-align: center;
        }

        .login-logo {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            margin: 0 auto 16px;
            background: linear-gradient(135deg, #7b61ff 0%, #b637ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 8px 20px rgba(123, 97, 255, 0.4);
            font-weight: 700;
        }

        .login-header h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }

        .login-header p {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 500;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: 0.78rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .login-input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.03);
            color: white;
            font-size: 0.9rem;
            font-family: inherit;
            outline: none;
            transition: all 0.25s ease;
        }

        .login-input:focus {
            border-color: rgba(123, 97, 255, 0.5);
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 0 16px rgba(123, 97, 255, 0.15);
        }

        .login-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 8px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.82rem;
            cursor: pointer;
            user-select: none;
        }

        .remember-me input {
            cursor: pointer;
            accent-color: #7b61ff;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            background: linear-gradient(90deg, #7b61ff, #b637ff);
            color: white;
            border: none;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(123, 97, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(123, 97, 255, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-back {
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: rgba(255, 255, 255, 0.4);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        .btn-back:hover {
            color: white;
        }

        .flash-error {
            padding: 12px 16px;
            border-radius: 10px;
            background: rgba(255, 59, 48, 0.1);
            border: 1px solid rgba(255, 59, 48, 0.2);
            color: #ff453a;
            font-size: 0.8rem;
            font-weight: 600;
            line-height: 1.4;
        }
    </style>
</head>
<body class="admin-page">

<div class="bg-orb orb-1"></div>
<div class="bg-orb orb-2"></div>
<div class="bg-orb orb-3"></div>

<div class="admin-login-shell" style="flex-direction: column; gap: 16px;">

    <a href="/" class="btn-back" style="margin-top: 0; margin-bottom: 4px; font-size: 0.86rem; color: rgba(255, 255, 255, 0.6); display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: color 0.2s ease;">
        <span>&larr;</span> Back to Portfolio
    </a>

    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">Ω</div>
            <h2>Control Room</h2>
            <p>Sign in to manage your portfolio</p>
        </div>

        @if($errors->any())
            <div class="flash-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input class="login-input" id="email" type="email" name="email" placeholder="admin@example.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="login-input" id="password" type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="login-footer">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
            </div>

            <button class="btn-login" type="submit">
                Sign In <span>&rarr;</span>
            </button>
        </form>
    </div>

</div>

</body>
</html>
