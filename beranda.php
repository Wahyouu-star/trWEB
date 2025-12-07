<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Beranda - AUTO CARE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2d9d5d66b.js" crossorigin="anonymous"></script>

  <style>
    body {
      font-family: "Poppins", sans-serif;
      background-color: #fff;
    }

    /* === Navbar === */
    .navbar {
      background-color: #ffffff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 15px 40px;
    }

    .navbar-brand {
      font-weight: 700;
      color: #b22929 !important;
    }

    .navbar-nav .nav-link {
      color: #000 !important;
      font-weight: 500;
      margin-left: 20px;
    }

    .navbar-nav .nav-link:hover {
      color: #b22929 !important;
    }

    .navbar .logo-icon {
      width: 38px;
      height: auto;
      margin-left: 10px;
      transition: transform 0.2s ease;
    }

    .navbar .logo-icon:hover {
      transform: scale(1.1);
    }

    /* === Banner === */
    .banner {
      margin-top: 85px;
      text-align: center;
      background-color: #fff;
      padding: 0 15px; /* sedikit ruang di kiri kanan di layar kecil */
    }

    .banner img {
      width: 100%;
      height: auto;       /* biar proporsi gambar tetap */
      max-height: none;   /* hilangkan batas tinggi kaku */
      border-radius: 10px;
      object-fit: cover;
    }

    @media (max-width: 576px) {
      .banner {
        margin-top: 75px; /* navbar di hp biasanya lebih kecil */
      }
    }

    /* === Layanan === */
    .layanan-section {
      background-color: #b22929;
      color: white;
      padding: 50px 0;
      border-radius: 10px;
      margin: 40px auto;
      width: 95%;
      max-width: 1100px;
    }

    .layanan-title {
      text-align: center;
      font-weight: 700;
      font-size: 1.7rem;
      margin-bottom: 35px;
    }

    .layanan-container {
      display: flex;
      justify-content: center;
      gap: 25px;
      flex-wrap: wrap;
    }

    .layanan-box {
      background-color: #fff;
      color: #b22929;
      border-radius: 10px;
      text-align: center;
      padding: 25px 20px;
      width: 150px;
      transition: transform 0.2s ease, box-shadow 0.3s ease;
      box-shadow: 0px 3px 8px rgba(0,0,0,0.15);
      text-decoration: none;
    }

    .layanan-box:hover {
      transform: translateY(-6px);
      box-shadow: 0px 6px 12px rgba(0,0,0,0.25);
    }

    .layanan-box img {
      width: 60px;
      height: 60px;
      margin-bottom: 10px;
    }

    .layanan-box p {
      margin: 0;
      font-weight: 500;
    }

    /* === Produk Diskon === */
    .promo-section {
      background-color: #ffffff;
      padding: 30px 0;
      margin: 0 auto 20px auto;
      width: 95%;
      max-width: 1100px;
    }

    .promo-title {
      text-align: center;
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 20px;
      color: #b22929;
    }

    .promo-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 15px;
    }

    .promo-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      padding: 12px 12px 14px 12px;
      width: 170px;              /* tidak terlalu besar */
      text-align: center;
      font-size: 0.85rem;
    }

    .promo-card img {
      width: 70px;
      height: 70px;
      object-fit: contain;
      margin-bottom: 8px;
    }

    .promo-name {
      font-weight: 600;
      margin-bottom: 4px;
    }

    .promo-price {
      font-size: 0.8rem;
      margin-bottom: 2px;
    }

    .promo-price-old {
      text-decoration: line-through;
      color: #888;
      margin-right: 4px;
    }

    .promo-price-new {
      color: #b22929;
      font-weight: 700;
    }

    .promo-badge {
      display: inline-block;
      background-color: #b22929;
      color: #fff;
      padding: 2px 8px;
      border-radius: 20px;
      font-size: 0.7rem;
      margin-bottom: 6px;
    }

    /* === Tentang Kami === */
    .tentang-section {
      background-color: #b22929;
      color: white;
      padding: 50px 0;
      border-radius: 10px;
      margin: 40px auto;
      width: 95%;
      max-width: 1100px;
    }

    .tentang-title {
      text-align: center;
      font-weight: 700;
      font-size: 1.7rem;
      margin-bottom: 35px;
    }

    .tentang-card {
      background-color: #fff;
      color: #333;
      border-radius: 12px;
      padding: 30px;
      display: flex;
      align-items: flex-start;
      gap: 30px;
      max-width: 950px;
      margin: 0 auto;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .tentang-card img {
      width: 100px;
      height: auto;
    }

    .tentang-card p {
      margin: 0;
      text-align: justify;
      font-size: 0.95rem;
    }

    @media (max-width: 768px) {
      .tentang-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .tentang-card p {
        text-align: justify;
      }
    }

    /* === Footer === */
    footer {
      background-color: #ffffff;
      border-top: 1px solid #ddd;
      padding: 40px 60px;
    }

    .footer-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .footer-left img {
      width: 60px;
      height: auto;
    }

    .footer-left h4 {
      margin: 0;
      font-weight: 700;
      color: #b22929;
    }

    .footer-right {
      display: flex;
      gap: 50px;
    }

    .footer-right h6 {
      font-weight: 700;
      margin-bottom: 12px;
      color: #000;
    }

    .footer-right a {
      color: #000;
      text-decoration: none;
      display: block;
      font-size: 0.9rem;
      margin-bottom: 6px;
    }

    .footer-right a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar fixed-top navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">AUTO CARE</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
          <li class="nav-item">
            <a href="profil.php" class="nav-link p-0">
              <img src="IMG/profil.jpg" alt="Profil" class="logo-icon">
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- BANNER -->
  <section class="banner">
    <img src="img/banner.png" alt="Banner Auto Care" class="img-fluid">
  </section>

  <!-- LAYANAN -->
  <section class="layanan-section">
    <h3 class="layanan-title">Layanan Kami</h3>
    <div class="layanan-container">
      <a href="daftarnamamekanik.php" class="layanan-box">
        <img src="img/konsultasi.png" alt="Konsultasi">
        <p>Konsultasi</p>
      </a>
      <a href="BUATJANJILANGKAH2.PHP" class="layanan-box">
        <img src="img/booking.png" alt="Booking">
        <p>Booking</p>
      </a>
      <a href="tokosparepart.php" class="layanan-box">
        <img src="img/sperpart.png" alt="Sparepart">
        <p>Sparepart</p>
      </a>
      <a href="riwayat.php" class="layanan-box">
        <img src="img/riwayat.png" alt="Riwayat Servis">
        <p>Riwayat Servis</p>
      </a>
      <a href="pengingat.php" class="layanan-box">
        <img src="img/pengingat.png" alt="Pengingat Servis">
        <p>Pengingat Servis</p>
      </a>
    </div>
  </section>

  <!-- PRODUK DISKON  -->
 <section class="promo-section">
  <h3 class="promo-title">Produk Diskon</h3>

  <div class="promo-container">

    <!-- 1. Aki 12 volt, 45 Ah -->
    <a href="tokosparepart.php" class="promo-card">
      <span class="promo-badge">20%</span>
      <img src="img/aki.jpg" alt="Aki 12 volt, 45 Ah">
      <div class="promo-name">Aki 12 volt, 45 Ah</div>
      <div class="promo-price">
        <span class="promo-price-old">Rp125.000</span>
        <span class="promo-price-new">Rp100.000</span>
      </div>
    </a>

    <!-- 2. Busi Gap 0,8 mm -->
    <a href="tokosparepart.php" class="promo-card">
      <span class="promo-badge">15%</span>
      <img src="img/busi.jpg" alt="Busi Gap 0,8 mm">
      <div class="promo-name">Busi Gap 0,8 mm</div>
      <div class="promo-price">
        <span class="promo-price-old">Rp12.000</span>
        <span class="promo-price-new">Rp10.000</span>
      </div>
    </a>

    <!-- 3. Filter Oli Diameter 80 mm -->
    <a href="tokosparepart.php" class="promo-card">
      <span class="promo-badge">10%</span>
      <img src="img/filteroli.jpg" alt="Filter Oli Diameter 80 mm">
      <div class="promo-name">Filter Oli Diameter 80 mm</div>
      <div class="promo-price">
        <span class="promo-price-old">Rp11.000</span>
        <span class="promo-price-new">Rp10.000</span>
      </div>
    </a>

    <!-- 4. Shockbreaker 45 cm -->
    <a href="tokosparepart.php" class="promo-card">
      <span class="promo-badge">20%</span>
      <img src="img/shockbreaker.jpg" alt="Shockbreaker 45 cm">
      <div class="promo-name">Shockbreaker 45 cm</div>
      <div class="promo-price">
        <span class="promo-price-old">Rp125.000</span>
        <span class="promo-price-new">Rp100.000</span>
      </div>
    </a>

    <!-- 5. Knalpot Diameter 2.5 inch -->
    <a href="tokosparepart.php" class="promo-card">
      <span class="promo-badge">20%</span>
      <img src="img/knalpot.jpg" alt="Knalpot Diameter 2.5 inch">
      <div class="promo-name">Knalpot Diameter 2.5 inch</div>
      <div class="promo-price">
        <span class="promo-price-old">Rp125.000</span>
        <span class="promo-price-new">Rp100.000</span>
      </div>
    </a>

  </div>
</section>


  <!-- TENTANG KAMI -->
  <section class="tentang-section">
    <h3 class="tentang-title">Tentang Kami</h3>
    <div class="tentang-card">
      <img src="img/logo.png" alt="Logo Auto Care">
      <p>
        Auto Care adalah platform layanan otomotif digital yang menghubungkan Anda dengan bengkel dan teknisi terpercaya secara cepat dan mudah.
        Kami hadir untuk menjawab kebutuhan masyarakat modern dalam merawat kendaraan tanpa hambatan waktu dan jarak. <br><br>
        Di Auto Care, Anda dapat melakukan:<br>
        • Konsultasi online dengan teknisi berpengalaman.<br>
        • Membuat janji servis di bengkel pilihan Anda.<br>
        • Mendapatkan layanan perawatan kendaraan yang aman, profesional, dan sesuai kebutuhan Anda.<br><br>
        Kami percaya bahwa perawatan kendaraan yang tepat adalah kunci keselamatan dan kenyamanan berkendara.
        Karena itu, Auto Care berkomitmen untuk memberikan layanan yang cepat, transparan, dan terpercaya — kapan pun dan di mana pun Anda berada.
      </p>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="mt-5 py-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-start">
      <div class="footer-left mb-3 mb-md-0">
        <img src="img/logo.png" alt="Logo Auto Care">
        <h4>AUTO CARE</h4>
      </div>

      <div class="footer-right">
        <div>
          <h6>Quick Links</h6>
          <a href="index.php">Beranda</a>
          <a href="profil.php">Profil</a>
        </div>
        <div>
          <h6>Social</h6>
          <a href="#">@AutoCare</a>
          <a href="#">Facebook</a>
          <a href="#">Instagram</a>
          <a href="#">0856-0000-0000</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
