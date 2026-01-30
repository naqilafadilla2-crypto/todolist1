<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Bakti Komdigi</title>

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif;}
    body { height: 100vh; background: linear-gradient(135deg, #0f122b, #1c1f6a); display: flex; justify-content: center; align-items: center;}
    .container { background: #fff; width: 420px; padding: 60px 40px; border-radius: 14px; text-align: center; box-shadow: 0 15px 35px rgba(0,0,0,0.2);}
    .logo-text { margin-bottom: 60px;}
    .bakti { display: block; font-size: 52px; font-weight: 800; color: #2c2f7e;}
    .komdigi { display: block; font-size: 30px; font-weight: 500; color: #2c2f7e; letter-spacing: 1px;}
    .btn-masuk { background: #2c2f7e; color: #fff; border: none; padding: 14px 60px; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; transition: 0.3s;}
    .btn-masuk:hover { background: #1f225f; transform: translateY(-2px);}
    a { text-decoration: none;}
  </style>
</head>
<body>

  <div class="container">

    <div class="logo-text">
      <span class="bakti">BAKTI</span>
      <span class="komdigi">KOMDIGI</span>
    </div>

    <!-- TOMBOL MASUK -->
    <a href="{{ route('signin.page') }}">
      <button class="btn-masuk">MASUK</button>
    </a>

  </div>

</body>
</html>
