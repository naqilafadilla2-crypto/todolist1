<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Monitoring Aplikasi BAKTI Kominfo</title>

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
      background: linear-gradient(135deg, #0d0f2a, #1a1f5c);
      overflow: hidden;
    }

    /* ===== CARD ===== */
    .card {
      background: #111439;
      padding: 60px 40px;
      border-radius: 20px;
      width: 400px;
      text-align: center;
      box-shadow: 0 15px 40px rgba(0,0,0,0.5);
      position: relative;
      overflow: hidden;
      color: #fff;
      animation: slideFade 1s ease forwards;
    }

    @keyframes slideFade {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    /* ===== TITLE ===== */
    .title {
      font-size: 28px;
      font-weight: 800;
      color: #ffcc00;
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: 1.2px;
    }

    .subtitle {
      font-size: 14px;
      font-weight: 400;
      color: #c4c7ff;
      margin-bottom: 40px;
      line-height: 1.5;
    }

    /* ===== BUTTON ===== */
    .btn-masuk {
      background: linear-gradient(135deg, #ffcc00, #f1b800);
      border: none;
      padding: 14px 60px;
      font-size: 16px;
      font-weight: 700;
      color: #000;
      border-radius: 30px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 20px rgba(255,204,0,0.4);
    }

    .btn-masuk:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 28px rgba(255,204,0,0.55);
    }

    .btn-masuk:active {
      transform: translateY(0);
      box-shadow: 0 6px 12px rgba(255,204,0,0.35);
    }

    a {
      text-decoration: none;
    }

    /* ===== BACKGROUND CIRCLES ===== */
    .circle {
      position: absolute;
      border-radius: 50%;
      opacity: 0.1;
      z-index: 0;
    }

    .circle.one {
      width: 180px;
      height: 180px;
      background: #ffcc00;
      top: -60px;
      right: -60px;
    }

    .circle.two {
      width: 120px;
      height: 120px;
      background: #ff7f50;
      bottom: -40px;
      left: -40px;
    }

    /* ===== FOOTNOTE ===== */
    .footnote {
      margin-top: 28px;
      font-size: 12px;
      color: #aaaaff;
      opacity: 0.7;
    }

    /* RESPONSIVE */
    @media (max-width: 500px) {
      .card { width: 90%; padding: 40px 25px; }
    }

  </style>
</head>
<body>

  <div class="card">
    <!-- BACKGROUND CIRCLES -->
    <div class="circle one"></div>
    <div class="circle two"></div>

    <!-- TITLE -->
    <h1 class="title">Monitoring Aplikasi</h1>
    <p class="subtitle">Sistem pemantauan aplikasi BAKTI Kominfo untuk memantau status dan performa secara real-time.</p>

    <!-- BUTTON MASUK (FUNGSI TETAP) -->
    <a href="{{ route('signin.page') }}">
      <button class="btn-masuk">MASUK SISTEM</button>
    </a>

    <!-- FOOTNOTE -->
    <div class="footnote">© {{ date('Y') }} BAKTI Kominfo</div>
  </div>

</body>
</html>
