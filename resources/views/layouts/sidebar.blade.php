<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi')</title>

@livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        background: #1a1f3c;
        }

        .main-wrapper {
            display: flex;
            flex: 1;
        }

    .sidebar {
    width: 260px;
    background: linear-gradient(180deg, #2c2f7e, #1c1f5c);
    color: #fff;
    padding: 30px 20px;
    position: relative;

    display: flex;
    flex-direction: column;
    /* HAPUS justify-content: space-between */

    min-height: 100vh; /* 🔥 ini yang bikin biru tetap penuh */
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
    margin-top: 20px; /* jarak aman dari menu */
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

    .content {
    flex: 1;
    padding: 32px;
    background: #f4f6fb;
}

        /* ===== FOOTER FULL WIDTH ===== */
.main-footer {
    background: linear-gradient(180deg, #2c2f7e, #1c1f5c);
    color: #e6e8ff;
    padding: 50px 0 20px;
}

.footer-container {
    width: 100%;
    max-width: 1200px;
    margin: auto;
    padding: 0 32px;
}

.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 40px;
    font-size: 14px;
}

.main-footer h3 {
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 12px;
    color: #ffcc00;
}

.main-footer p {
    line-height: 1.8;
}

.main-footer a {
    color: #ffcc00;
    text-decoration: none;
}

.main-footer a:hover {
    text-decoration: underline;
    color: #ffffff;
}

.footer-bottom {
    margin-top: 30px;
    padding-top: 18px;
    border-top: 1px solid rgba(255,255,255,0.2);
    text-align: center;
    font-size: 12px;
}


    </style>
</head>
<body>

<div class="main-wrapper">
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
                    Dasbor
        </a>

        <a href="{{ route('applink.index') }}" class="{{ request()->routeIs('applink.*') ? 'active' : '' }}">
                    Kelola Aplikasi
        </a>

                <a href="{{ route('monitoring.index') }}" class="{{ request()->routeIs('monitoring.*') && !request()->routeIs('monitoring.user.*') ? 'active' : '' }}">
                    Pantau
        </a>

        <a href="{{ route('laporan') }}" class="{{ request()->routeIs('laporan') ? 'active' : '' }}">
                    Laporan
        </a>
            <a href="{{ route('rack.index') }}" class="{{ request()->routeIs('rack.*') ? 'active' : '' }}">
                   Kelola Rack
                </a>

             <a href="{{ route('user.index') }}" class="{{ request()->routeIs('user.*') ? 'active' : '' }}">
                   Pengguna
                </a>    
            @else

            <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}">
                    Dasbor
                </a>
                <a href="{{ route('monitoring.user.dashboard') }}" class="{{ request()->routeIs('monitoring.user.*') || request()->routeIs('dashboard') ? 'active' : '' }}">
                    Menu Aplikasi
                </a>

                <a href="{{ route('laporan') }}" class="{{ request()->routeIs('laporan') ? 'active' : '' }}">
                    Laporan
                </a>
            @endif
        </div>
    </div>

    <!-- LOGOUT -->
    <div class="logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button> Keluar</button>
        </form>
    </div>

</div>

<div class="content">
    @yield('content')
</div>
</div>

<!-- FOOTER -->
<footer style="background: linear-gradient(180deg, #2c2f7e, #1c1f5c); color: #fff;" class="text-white">
    <div class="px-4 sm:px-6 py-10" style="margin-left: 0;">
        <div class="grid md:grid-cols-3 gap-8 text-sm">
            <div>
                <h3 class="font-semibold mb-3">Alamat Kantor</h3>
                <p style="color: #e6e8ff;">
                    Centennial Tower Lt. 42-45<br>
                    Jl. Gatot Subroto Kav. 24-25<br>
                    Jakarta 12930
                </p>
            </div>

            <div>
                <h3 class="font-semibold mb-3">Kontak</h3>
                <p style="color: #e6e8ff;">Telepon: (021) 3193 6590</p>
                <p style="color: #e6e8ff;">
                    Email:
                    <a href="mailto:humas@baktikominfo.id" class="underline hover:text-white">
                        humas@baktikominfo.id
                    </a>
                </p>
            </div>

            <div>
                <h3 class="font-semibold mb-3">Jam Operasional</h3>
                <p style="color: #e6e8ff;">Senin - Jumat</p>
                <p style="color: #e6e8ff;">08.00 - 17.00 WIB</p>
                <p style="color: #e6e8ff;">Layanan Online: 24 Jam</p>
            </div>
        </div>

        <div class="border-t mt-8 pt-4 text-center text-xs" style="border-color: rgba(255,255,255,0.2); color: #e6e8ff;">
            © {{ date('Y') }} AppLink -  @ Bakti Komdigi 2026.
        </div>
    </div>
</footer>
@livewireScripts
</body>
</html>
