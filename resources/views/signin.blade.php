<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Monitoring Aplikasi BAKTI Kominfo - Login</title>

<style>
    /* ===== RESET ===== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Arial, sans-serif;
    }

    body {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #0a978e, #084d46);
        overflow: hidden;
    }

    /* ===== CARD ===== */
    .container {
        background: linear-gradient(135deg, rgba(10, 151, 142, 0.1), rgba(8, 77, 70, 0.15));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(10, 151, 142, 0.3);
        padding: 50px 35px;
        border-radius: 20px;
        width: 380px;
        text-align: center;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3), 0 0 30px rgba(10, 151, 142, 0.2);
        position: relative;
        color: #fff;
        animation: fadeUp 1s ease forwards;
    }

    @keyframes fadeUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* ===== TITLE ===== */
    h2 {
        font-size: 26px;
        font-weight: 800;
        background: linear-gradient(135deg, #FFD700, #FFC107);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 25px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    /* ===== AVATAR ICON ===== */
    .avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        margin: 0 auto 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 8px 20px rgba(10, 151, 142, 0.3);
    }

    .avatar svg {
        width: 50px;
        height: 50px;
        fill: #FFD700;
    }

    /* ===== FORM CARD ===== */
    .card {
        background: rgba(255,255,255,0.1);
        border-radius: 16px;
        padding: 25px 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        margin-top: 10px;
    }

    label {
        display: block;
        font-size: 14px;
        color: #fff5cc;
        margin-top: 14px;
        text-align: left;
    }

    input {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.3);
        margin-top: 6px;
        font-size: 15px;
        background: rgba(255,255,255,0.05);
        color: #fff;
        transition: 0.3s;
    }

    input:focus {
        outline: none;
        border-color: #0a978e;
        box-shadow: 0 0 8px rgba(10, 151, 142, 0.6);
        background: rgba(255,255,255,0.1);
    }

    .password-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        width: 22px;
        height: 22px;
        fill: #FFD700;
    }

    .btn-login {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #0a978e, #087d77);
        color: #fff;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        font-weight: 700;
        margin-top: 24px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(10, 151, 142, 0.4);
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(10, 151, 142, 0.55), 0 0 15px rgba(255, 215, 0, 0.3);
    }

    .btn-login:active {
        transform: translateY(0);
        box-shadow: 0 6px 12px rgba(10, 151, 142, 0.35);
    }

    .error {
        color: #ff4d4d;
        font-size: 14px;
        text-align: left;
        margin-top: 8px;
    }

    /* BACKGROUND CIRCLES */
    .circle {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        z-index: 0;
    }

    .circle.one {
        width: 200px;
        height: 200px;
        background: #FFD700;
        top: -50px;
        right: -50px;
    }

    .circle.two {
        width: 140px;
        height: 140px;
        background: #0a978e;
        bottom: -40px;
        left: -40px;
    }

</style>
</head>
<body>

<div class="container">
    <!-- BACKGROUND CIRCLES -->
    <div class="circle one"></div>
    <div class="circle two"></div>

    <div class="avatar">
        <svg viewBox="0 0 24 24">
            <path d="M12 12c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 2c-2.757 0-8 1.378-8 4.125V22h16v-3.875C20 15.378 14.757 14 12 14z"/>
        </svg>
    </div>

    <h2>LOGIN USER</h2>

    <div class="card">
        <form method="POST" action="{{ route('signin.process') }}">
            @csrf

            <label>Email</label>
            <input
                type="email"
                name="email"
                placeholder="Masukkan email"
                value="{{ old('email') }}"
                required
            >

            <label>Password</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password-field"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >
                <svg class="toggle-password" id="togglePassword" viewBox="0 0 24 24">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12c-2.48 0-4.5-2.02-4.5-4.5s2.02-4.5 4.5-4.5 4.5 2.02 4.5 4.5-2.02 4.5-4.5 4.5z"/>
                    <circle cx="12" cy="12" r="2.5"/>
                </svg>
            </div>

            @error('loginError')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn-login">LOGIN</button>
        </form>
    </div>
</div>

<script>
    const togglePassword = document.querySelector("#togglePassword");
    const passwordField = document.querySelector("#password-field");

    const eyeIcon = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12c-2.48 0-4.5-2.02-4.5-4.5s2.02-4.5 4.5-4.5 4.5 2.02 4.5 4.5-2.02 4.5-4.5 4.5z"/><circle cx="12" cy="12" r="2.5"/>';
    const eyeOffIcon = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.1 2.8 3.17 5.13 5.78 6.3l-1.36 1.36C3.17 18.88 1.72 16.6 1 14c1.73-4.39 6-7.5 11-7.5 2.04 0 3.95.54 5.63 1.46l-1.46 1.46C15.82 8.25 13.99 7.5 12 7.5c-2.76 0-5 2.24-5 5 0 1.08.36 2.08.96 2.88l-1.45 1.45C5.07 15.04 4.5 13.56 4.5 12c0-3.59 2.91-6.5 6.5-6.5 1.54 0 2.95.55 4.04 1.46l1.43-1.43C17.4 4.83 14.8 4.5 12 4.5z"/>';

    const setPasswordIcon = (type) => {
        togglePassword.innerHTML = type === 'password' ? eyeIcon : eyeOffIcon;
        togglePassword.style.fill = type === 'password' ? '#FFD700' : '#0a978e';
    };

    togglePassword.addEventListener("click", function () {
        const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
        passwordField.setAttribute("type", type);
        setPasswordIcon(type);
    });
</script>

</body>
</html>
