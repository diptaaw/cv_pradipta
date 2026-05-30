<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login</title>
    @vite(['resources/css/app.css'])
    <style>
        .admin-login-shell{ min-height:100vh; display:flex; align-items:center; justify-content:center; background:transparent }
        .admin-card{ width:360px; padding:28px; border-radius:12px; background:rgba(12,10,35,0.6); border:1px solid rgba(255,255,255,0.04); backdrop-filter:blur(8px); }
        .admin-card h2{ margin-bottom:16px; font-size:1.1rem }
        .form-row{ margin-bottom:12px }
        .form-row input{ width:100%; padding:10px 12px; border-radius:8px; border:1px solid rgba(255,255,255,0.04); background:rgba(255,255,255,0.02); color:white }
        .admin-actions{ display:flex; gap:8px; align-items:center; margin-top:14px }
        .btn-primary{ padding:8px 12px; border-radius:8px; background:linear-gradient(90deg,#7b61ff,#b637ff); color:white; border:none }
        .flash-error{ color:#ffb4c6; margin-bottom:12px }
    </style>
</head>
<body class="admin-page">

<div class="layout-shell admin-login-shell">

    <div class="admin-card">
        <h2>Administrator</h2>

        @if($errors->any())
            <div class="flash-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf

            <div class="form-row">
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-row">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="admin-actions">
                <label style="color:rgba(255,255,255,0.6); font-size:0.9rem"><input type="checkbox" name="remember"> Remember</label>
                <button class="btn-primary" type="submit">Sign in</button>
            </div>

        </form>
    </div>

</div>

</body>
</html>
