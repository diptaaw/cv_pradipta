<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel')</title>
    @vite(['resources/css/app.css'])
    <style>
        .admin-page,
        .admin-page * {
            cursor: auto !important;
        }

        .admin-page .layout-shell {
            position: relative !important;
            top: auto !important;
            left: auto !important;
            transform: none !important;
            margin: 0 auto !important;
            width: min(1200px, calc(100% - 48px)) !important;
            max-width: 1200px !important;
            pointer-events: auto !important;
        }

        .admin-shell {
            min-height: 100vh;
            padding: 34px 36px 40px;
            background: rgba(5, 5, 10, 0.95);
            border-radius: 28px;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.35);
            color: white;
        }

        .admin-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .admin-top h1 {
            margin-bottom: 8px;
            font-size: clamp(1.8rem, 2.4vw, 2.5rem);
        }

        .admin-subtitle {
            color: rgba(255,255,255,0.68);
            max-width: 720px;
            line-height: 1.7;
            margin-top: 0;
        }

        .admin-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 28px;
        }

        .admin-nav a,
        .admin-actions button,
        .admin-actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.04);
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: background 0.25s ease, transform 0.25s ease, border-color 0.25s ease;
        }

        .admin-nav a:hover,
        .admin-actions button:hover,
        .admin-actions a:hover {
            background: rgba(123,97,255,0.18);
            border-color: rgba(123,97,255,0.24);
            transform: translateY(-1px);
            color: white;
        }

        .admin-grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        .admin-card {
            padding: 22px;
            border-radius: 20px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
        }

        .admin-card h2 {
            margin-bottom: 14px;
            font-size: 1.1rem;
        }

        .admin-form {
            width: 100%;
            max-width: 860px;
            padding: 24px;
            border-radius: 22px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
        }

        .admin-form label {
            display: block;
            margin-bottom: 10px;
            color: rgba(255,255,255,0.7);
            font-size: 0.88rem;
        }

        .admin-form input,
        .admin-form textarea,
        .admin-form select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            color: white;
            margin-bottom: 16px;
        }

        .admin-form textarea {
            min-height: 170px;
            resize: vertical;
        }

        .admin-form .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .admin-form .form-actions button,
        .admin-form .form-actions a {
            padding: 12px 18px;
            border-radius: 14px;
            border: none;
            background: linear-gradient(90deg, #7b61ff, #b637ff);
            color: white;
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .admin-form .form-actions button:hover,
        .admin-form .form-actions a:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 55px rgba(123,97,255,0.18);
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.88);
            font-size: 0.82rem;
        }

        .flash-message {
            padding: 16px 18px;
            border-radius: 16px;
            background: rgba(123,97,255,0.16);
            border: 1px solid rgba(123,97,255,0.22);
            color: #fff;
            margin-bottom: 22px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th,
        .admin-table td {
            padding: 16px 14px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .admin-table th {
            color: rgba(255,255,255,0.75);
            font-weight: 600;
        }

        .admin-table td {
            color: rgba(255,255,255,0.92);
        }

        .admin-table tr:hover {
            background: rgba(255,255,255,0.03);
        }
    </style>
</head>
<body class="admin-page">
    <div class="layout-shell admin-shell">
        <div class="admin-top">
            <div>
                <h1>@yield('page-title')</h1>
                @hasSection('page-subtitle')
                    <p class="admin-subtitle">@yield('page-subtitle')</p>
                @endif
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.experiences.index') }}">Experiences</a>
                <a href="{{ route('admin.projects.index') }}">Projects</a>
                <a href="{{ route('admin.about.edit') }}">About</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
