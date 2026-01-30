<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Aplikasi')</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: #f4f6fb;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2f3b82, #1f275f);
            color: #fff;
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
        }

        /* LOGO APPLINK */
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 30px;
            animation: fadeSlide 1.2s ease-out;
        }

        .logo img {
            width: 42px;
            height: 42px;
            object-fit: contain;
            border-radius: 8px;
            background: #fff;
            padding: 4px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.25);
            animation: pulse 2s infinite;
        }

        .logo-text {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .logo-text .app {
            color: #ffffff;
        }

        .logo-text .link {
            color: #ffcc00;
        }

        .logo:hover .link {
            text-shadow: 0 0 10px rgba(255, 204, 0, 0.9);
            transition: 0.3s;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }

        /* MENU */
        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin-bottom: 10px;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .menu a:hover {
            background: rgba(255,255,255,0.15);
            transform: translateX(6px);
        }

        .menu a.active {
            background: rgba(255,255,255,0.25);
            font-weight: 600;
        }

        .icon {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        /* LOGOUT */
        .logout {
            margin-top: 40px;
        }

        .logout button {
            width: 100%;
            padding: 12px;
            background: #e74c3c;
            border: none;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        .logout button:hover {
            background: #c0392b;
        }

        /* CONTENT */
        .content {
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
        }
    </style>
</head>
<body>

<div class="sidebar">

    <!-- LOGO -->
    <div class="logo">
        <img src="{{ asset('images/bakti.png') }}" alt="Logo">
        <div class="logo-text">
            <span class="app">App</span><span class="link">Link</span>
        </div>
    </div>

    <div class="menu">
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}">
                <span class="icon">🏠</span> Dashboard
            </a>

            <a href="{{ route('applink.index') }}" class="{{ request()->routeIs('applink.*') ? 'active' : '' }}">
                <span class="icon">🧩</span> Kelola Aplikasi
            </a>

            <a href="{{ route('monitoring.index') }}" class="{{ request()->routeIs('monitoring.*') && !request()->routeIs('monitoring.user.*') ? 'active' : '' }}">
                <span class="icon">📊</span> Monitoring
            </a>

            <a href="{{ route('laporan') }}" class="{{ request()->routeIs('laporan') ? 'active' : '' }}">
                <span class="icon">📄</span> Laporan
            </a>

            <a href="{{ route('user.index') }}" class="{{ request()->routeIs('user.*') ? 'active' : '' }}">
                <span class="icon">👤</span> User
            </a>
        @else
            <a href="{{ route('monitoring.user.dashboard') }}" class="{{ request()->routeIs('monitoring.user.*') || request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span> Dashboard Monitoring
            </a>

            <a href="{{ route('laporan') }}" class="{{ request()->routeIs('laporan') ? 'active' : '' }}">
                <span class="icon">📄</span> Laporan
            </a>

            <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}">
                <span class="icon">🏠</span> Menu Aplikasi
            </a>
        @endif
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button>🚪 Logout</button>
        </form>
    </div>
</div>

<div class="content">
    @yield('content')
</div>

</body>
</html>
