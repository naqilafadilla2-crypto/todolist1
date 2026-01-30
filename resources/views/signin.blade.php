<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login User</title>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Arial, sans-serif;
    }
    body {
        background: #2f3b82;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .container {
        width: 380px;
        text-align: center;
    }
    .avatar {
        width: 110px;
        height: 110px;
        background: #e3e3e3;
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .avatar img {
        width: 60px;
    }
    h2 {
        color: #fff;
        margin-bottom: 30px;
        font-size: 26px;
        font-weight: bold;
    }
    .card {
        background: #fff;
        border-radius: 14px;
        padding: 24px 20px;
        box-shadow: 0px 4px 10px rgba(0,0,0,0.15);
    }
    label {
        display: block;
        font-size: 14px;
        color: #2c2f7e;
        margin-top: 14px;
        text-align: left;
    }
    input {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        margin-top: 6px;
        font-size: 15px;
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
        fill: #777;
    }
    .btn-login {
        width: 100%;
        padding: 14px;
        background: #2f3b82;
        color: #fff;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        font-weight: bold;
        margin-top: 24px;
        cursor: pointer;
        transition: 0.3s ease;
    }
    .btn-login:hover {
        background: #1f225f;
    }
    .error {
        color: #e74c3c;
        font-size: 14px;
        text-align: left;
        margin-top: 8px;
    }
</style>

</head>
<body>

<div class="container">

    <div class="avatar">
    <svg width="60" height="60" viewBox="0 0 24 24" fill="#2f3b82" xmlns="http://www.w3.org/2000/svg">
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

                <!-- ICON MATA UNTUK SHOW/HIDE -->
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

<!-- JAVASCRIPT SHOW/HIDE PASSWORD -->
<script>
    const togglePassword = document.querySelector("#togglePassword");
    const passwordField = document.querySelector("#password-field");

    togglePassword.addEventListener("click", function () {
        const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
        passwordField.setAttribute("type", type);

        // ubah ikon
        this.style.fill = type === "password" ? "#777" : "#2f3b82";
    });
</script>

</body>
</html>
