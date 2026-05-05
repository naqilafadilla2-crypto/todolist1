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
      background: linear-gradient(135deg, #0a978e, #084d46);
      overflow: hidden;
    }

    /* ===== CARD ===== */
    .card {
      background: linear-gradient(135deg, rgba(10, 151, 142, 0.1), rgba(8, 77, 70, 0.15));
      backdrop-filter: blur(10px);
      border: 1px solid rgba(10, 151, 142, 0.3);
      padding: 60px 40px;
      border-radius: 20px;
      width: 400px;
      text-align: center;
      box-shadow: 0 15px 40px rgba(0,0,0,0.3), 0 0 30px rgba(10, 151, 142, 0.2);
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
      background: linear-gradient(135deg, #FFD700, #FFC107);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: 1.2px;
    }

    .subtitle {
      font-size: 14px;
      font-weight: 400;
      color: #d4f4f1;
      margin-bottom: 40px;
      line-height: 1.5;
    }

    /* ===== BUTTON ===== */
    .btn-masuk {
      background: linear-gradient(135deg, #0a978e, #087d77);
      border: none;
      padding: 14px 60px;
      font-size: 16px;
      font-weight: 700;
      color: #fff;
      border-radius: 30px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 20px rgba(10, 151, 142, 0.4);
    }

    .btn-masuk:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 28px rgba(10, 151, 142, 0.55), 0 0 15px rgba(255, 215, 0, 0.3);
      border: 1px solid rgba(255, 215, 0, 0.4);
    }

    .btn-masuk:active {
      transform: translateY(0);
      box-shadow: 0 6px 12px rgba(10, 151, 142, 0.35);
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
      background: #FFD700;
      top: -60px;
      right: -60px;
    }

    .circle.two {
      width: 120px;
      height: 120px;
      background: #0a978e;
      bottom: -40px;
      left: -40px;
    }

    /* ===== FOOTNOTE ===== */
    .footnote {
      margin-top: 28px;
      font-size: 12px;
      color: #a0e1dc;
      opacity: 0.8;
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
