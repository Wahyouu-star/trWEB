<?php
// daftarnamamekanik.php
$mekanik = [
  ["nama" => "Pak Slamet", "bidang" => "Electrical"],
  ["nama" => "Mas Surya", "bidang" => "Mesin"],
  ["nama" => "Budi Sentosa", "bidang" => "Body Repair"],
  ["nama" => "Agus Hendro", "bidang" => "Tune Up"],
  ["nama" => "Rino Sanjaya", "bidang" => "AC & Cooling System"],
  ["nama" => "Pak Min", "bidang" => "Suspensi & Rem"],
  ["nama" => "Ronny Nopirman", "bidang" => "Transmisi"],
  ["nama" => "Pak Hartanto", "bidang" => "Kelistrikan & Diagnostik"],
  ["nama" => "Emanuele Rico", "bidang" => "Interior & Detailing"],
  ["nama" => "Vallentino David", "bidang" => "Mesin Diesel"]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Mekanik | AUTO CARE</title>
  <style>
    /* Menghilangkan semua variabel dark-mode */
    :root {
      --primary: #c33;
      --bg-light: #f2f2f2;
      --text-light: #000;
      --card-light: #fff;
    }

    body {
      font-family: "Poppins", sans-serif;
      background-color: var(--bg-light);
      color: var(--text-light);
      /* Padding-top untuk menggeser konten di bawah fixed header 68px */
      padding-top: 68px; 
    }

    /* Search bar */
    .search-bar {
      display: flex;
      align-items: center;
      background: var(--card-light);
      border-radius: 25px;
      padding: 8px 15px;
      width: 100%;
      max-width: 500px;
      margin: 25px auto;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      margin-top: 25px; 
    }

    .search-bar input {
      border: none;
      outline: none;
      flex: 1;
      background: transparent;
      color: inherit;
    }
    
    .search-bar .search-icon {
        margin-right: 8px;
        color: #555;
    }

    /* Container utama */
    .container-fluid {
      padding: 0 30px 50px 30px;
    }

    /* Tata Letak Kartu (Menggantikan Bootstrap Grid) */
    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem; /* Jarak antar kartu */
        justify-content: center;
    }
    .mekanik-item {
        /* Default 2 kolom untuk mobile */
        flex: 0 0 auto;
        width: calc(50% - 1.5rem); 
        max-width: 180px;
    }
    @media (min-width: 768px) {
        .mekanik-item {
            /* Sekitar 5 kolom untuk desktop/tablet */
            width: calc(20% - 1.5rem); 
            max-width: 180px; 
        }
    }

    /* Kartu mekanik */
    .mekanik-card {
      background: var(--card-light);
      border-radius: 15px;
      text-align: center;
      padding: 20px 10px;
      transition: transform 0.3s ease, background 0.3s ease;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      min-height: 200px; 
    }

    .mekanik-card:hover {
      transform: scale(1.03);
    }

    .mekanik-icon {
      /* Mengganti ikon Bootstrap dengan gambar konsultasi.png */
      width: 55px;
      height: 55px;
      margin-bottom: 10px;
      display: inline-block;
    }
    .mekanik-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .mekanik-card h6 {
      font-size: 15px;
      font-weight: 600;
      color: #000;
      margin-bottom: 5px;
    }

    .mekanik-card p {
        font-size: 12px;
        color: #777;
        margin-bottom: 10px;
    }

    .btn-chat {
      background-color: var(--primary);
      border: none;
      color: #fff;
      border-radius: 20px;
      padding: 6px 16px;
      font-size: 14px;
      transition: background 0.3s ease;
      text-decoration: none; 
      display: inline-block;
    }

    .btn-chat:hover {
      background-color: #a11;
    }

    @media (max-width: 768px) {
      .mekanik-card {
        padding: 15px 8px;
      }
      .btn-chat {
        font-size: 12px;
        padding: 5px 12px;
      }
    }
    
    footer {
      text-align: center;
      padding: 15px;
      color: #666;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <?php include "inc/header.php"; ?>

  <div class="search-bar">
    <span class="search-icon">🔍</span>
    <input type="text" id="searchInput" placeholder="Cari mekanik atau bidang...">
  </div>

  <div class="container-fluid">
    <div class="row" id="mekanikList">
      <?php foreach ($mekanik as $m): ?>
      <div class="mekanik-item">
        <div class="mekanik-card">
          <div class="mekanik-icon">
            <img src="IMG/konsultasi.png" alt="Mekanik Icon">
          </div>
          <h6><?= htmlspecialchars($m['nama']) ?></h6>
          <p><?= htmlspecialchars($m['bidang']) ?></p>
          <a href="konsultasionlinelangkah2.php?mekanik=<?= urlencode($m['nama']); ?>&bidang=<?= urlencode($m['bidang']); ?>" class="btn-chat">
            Chat
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <footer>
    &copy; <?= date('Y'); ?> AUTO CARE | All Rights Reserved
  </footer>

  <script>
    // 🔍 Filter pencarian
    const searchInput = document.getElementById('searchInput');
    const mekanikItems = document.querySelectorAll('.mekanik-item');

    searchInput.addEventListener('keyup', function() {
      const keyword = this.value.toLowerCase();
      mekanikItems.forEach(item => {
        const name = item.querySelector('h6').textContent.toLowerCase();
        const bidang = item.querySelector('p').textContent.toLowerCase();
        item.style.display = name.includes(keyword) || bidang.includes(keyword) ? '' : 'none';
      });
    });
  </script>

</body>
</html>