<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi')</title>

@livewireStyles

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
        background: #1a1f3c;
        }

    /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
        background: linear-gradient(180deg, #2c2f7e, #1c1f5c);
            color: #fff;
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 4px 0 20px rgba(0,0,0,0.3);
    }

    /* ===== LOGO ===== */
    .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
            margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    .logo img {
        width: 48px;
        height: 48px;
        background: #fff;
        padding: 6px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        transition: transform 0.3s ease;
    }

    .logo:hover img {
        transform: scale(1.08);
    }

    .logo-text {
        font-size: 28px;
        font-weight: 800;
            letter-spacing: 1px;
        }

    .logo-text .app { color: #ffffff; }
    .logo-text .link { color: #ffcc00; }

    /* ===== MENU ===== */
    .menu {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

        .menu a {
            display: flex;
            align-items: center;
        gap: 14px;
        padding: 14px 16px;
            margin-bottom: 10px;
        color: #e6e8ff;
            text-decoration: none;
        border-radius: 12px;
            font-size: 15px;
        transition: all 0.25s ease;
        }

        .menu a:hover {
        background: rgba(255,255,255,0.12);
            transform: translateX(6px);
        }

        .menu a.active {
        background: rgba(255,255,255,0.2);
        color: #fff;
            font-weight: 600;
        box-shadow: inset 4px 0 0 #ffcc00;
        }

    .menu a .icon {
            font-size: 18px;
        width: 26px;
            text-align: center;
        opacity: 0.9;
        }

    /* ===== LOGOUT ===== */
        .logout {
        margin-top: 30px;
        }

        .logout button {
            width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #ff4d4d, #c0392b);
            border: none;
        color: #fff;
        border-radius: 14px;
            cursor: pointer;
            font-weight: 600;
        font-size: 15px;
        transition: all 0.25s ease;
        box-shadow: 0 6px 18px rgba(255,77,77,0.4);
        }

        .logout button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(255,77,77,0.55);
        }

    /* ===== CONTENT ===== */
        .content {
            margin-left: 260px;
        padding: 32px;
            width: calc(100% - 260px);
        background: #f4f6fb;
        min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="sidebar">

    <div>
        <!-- LOGO -->
        <div class="logo">
            <img src="{{ asset('images/bakti.png') }}" alt="Logo">
            <div class="logo-text">
                <span class="app">App</span><span class="link">Link</span>
            </div>
        </div>

        <!-- MENU -->
    <div class="menu">
            @if(auth()->user()->role === 'admin')
        <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}">
                    Dashboard
        </a>

        <a href="{{ route('applink.index') }}" class="{{ request()->routeIs('applink.*') ? 'active' : '' }}">
                    Kelola Aplikasi
        </a>

                <a href="{{ route('monitoring.index') }}" class="{{ request()->routeIs('monitoring.*') && !request()->routeIs('monitoring.user.*') ? 'active' : '' }}">
                    Monitoring
        </a>

        <a href="{{ route('laporan') }}" class="{{ request()->routeIs('laporan') ? 'active' : '' }}">
                    Laporan
        </a>

        <a href="{{ route('user.index') }}" class="{{ request()->routeIs('user.*') ? 'active' : '' }}">
                   User
                </a>

                <a href="{{ route('rack.index') }}" class="{{ request()->routeIs('rack.*') ? 'active' : '' }}">
                   Rack Management
                </a>
            @else
                <a href="{{ route('monitoring.user.dashboard') }}" class="{{ request()->routeIs('monitoring.user.*') || request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard Monitoring
                </a>

                <a href="{{ route('laporan') }}" class="{{ request()->routeIs('laporan') ? 'active' : '' }}">
                    Laporan
                </a>

                <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}">
                    Menu Aplikasi
                </a>
            @endif
        </div>
    </div>

    <!-- LOGOUT -->
    <div class="logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button> Logout</button>
        </form>
    </div>

</div>

<div class="content">
    @yield('content')
</div>

@livewireScripts
</body>
</html>
