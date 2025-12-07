<?php
// Ambil data mekanik dari URL
$namaMekanik = isset($_GET['mekanik']) ? htmlspecialchars($_GET['mekanik']) : 'Tidak Diketahui';
$bidangMekanik = isset($_GET['bidang']) ? htmlspecialchars($_GET['bidang']) : 'Bidang Tidak Diketahui';

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = htmlspecialchars($_POST['nama']);
  $alamat = htmlspecialchars($_POST['alamat']);
  $telp = htmlspecialchars($_POST['telp']);
  $kendaraan = htmlspecialchars($_POST['kendaraan']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>AUTO CARE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background-color: #f5f5f5;
    }

    header {
      background-color: white;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
    }

    header h1 {
      font-size: 20px;
      font-weight: 700;
    }

    .menu-kanan {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .menu-kanan a {
      text-decoration: none;
      color: black;
      font-weight: 500;
    }

    .icon-user {
      background-color: #d13d3d;
      color: white;
      width: 32px;
      height: 32px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 50%;
      font-size: 18px;
    }

    main {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 50px 20px;
      gap: 50px;
      flex-wrap: wrap;
    }

    /* Kartu mekanik */
    .card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      width: 250px;
      height: 320px;
      text-align: center;
      padding: 20px;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .mekanik-icon {
      font-size: 90px;
      color: #d13d3d;
      margin-top: 30px;
    }

    .card p {
      margin-top: 10px;
      font-weight: 600;
      font-size: 18px;
    }

    .card small {
      display: block;
      margin-top: 5px;
      color: #666;
      font-size: 14px;
    }

    .form-container {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 40px;
      width: 400px;
    }

    .form-container h2 {
      text-align: center;
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 30px;
    }

    label {
      display: block;
      font-size: 14px;
      margin-bottom: 5px;
      margin-top: 15px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      border: none;
      border-bottom: 1px solid #aaa;
      font-size: 14px;
      outline: none;
      background-color: transparent;
    }

    button {
      width: 100%;
      background-color: #d13d3d;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 16px;
      margin-top: 30px;
      cursor: pointer;
      box-shadow: 0 4px 5px rgba(0,0,0,0.2);
      transition: background 0.2s;
    }

    button:hover {
      background-color: #b73232;
    }

    .back-btn {
      position: absolute;
      top: 80px;
      left: 30px;
      font-size: 30px;
      color: #777;
      text-decoration: none;
    }

    .hasil {
      margin-top: 30px;
      padding: 15px;
      background-color: #eaf6ea;
      border: 1px solid #b5e0b5;
      border-radius: 8px;
    }

    /* Tombol Chat Mekanik */
    .btn-chat {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      background: linear-gradient(90deg, #d13d3d, #ff5c5c);
      color: white;
      text-decoration: none;
      font-weight: 600;
      padding: 14px 26px;
      border-radius: 30px;
      font-size: 15px;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(209, 61, 61, 0.4);
    }

    .btn-chat i {
      font-size: 18px;
      transition: transform 0.3s ease;
    }

    .btn-chat:hover {
      background: linear-gradient(90deg, #b73232, #ff4b4b);
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(209, 61, 61, 0.6);
    }

    .btn-chat:hover i {
      transform: scale(1.2);
    }
  </style>
</head>
<body>

  <header>
    <h1>AUTO CARE</h1>
    <div class="menu-kanan">
      <a href="beranda.php">Beranda</a>
      <div class="icon-user">👤</div>
    </div>
  </header>

  <a href="daftarnamamekanik.php" class="back-btn">←</a>

  <main>
    <!-- Kartu Mekanik -->
    <div class="card">
      <div class="mekanik-icon"><i class="bi bi-person-gear"></i></div>
      <p><?= $namaMekanik ?></p>
      <small>Spesialis: <?= $bidangMekanik ?></small>
    </div>

    <!-- Form Data Diri -->
    <div class="form-container">
      <h2>Isi Data Diri</h2>
      <form method="POST" action="">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" placeholder="Masukkan nama" required>

        <label for="alamat">Alamat</label>
        <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamat" required>

        <label for="telp">No. Telp</label>
        <input type="text" id="telp" name="telp" placeholder="Masukkan nomor telepon" required>

        <label for="kendaraan">Jenis Kendaraan</label>
        <select id="kendaraan" name="kendaraan" required>
          <option value="">Pilih jenis kendaraan</option>
          <option value="Matic">MATIC</option>
          <option value="Manual">MANUAL</option>
        </select>

        <button type="submit">LANJUTKAN</button>
      </form>

      <?php if (!empty($nama)): ?>
        <div class="hasil">
          <h3>Data yang Anda kirim:</h3>
          <p><strong>Nama:</strong> <?= $nama ?></p>
          <p><strong>Alamat:</strong> <?= $alamat ?></p>
          <p><strong>No. Telp:</strong> <?= $telp ?></p>
          <p><strong>Jenis Kendaraan:</strong> <?= $kendaraan ?></p>
        </div>

        <!-- Tombol Chat Mekanik -->
        <div style="text-align: center; margin-top: 30px;">
          <a href="konsultasichatmekanik.php?mekanik=<?= urlencode($namaMekanik) ?>&bidang=<?= urlencode($bidangMekanik) ?>" class="btn-chat">
            <i class="bi bi-chat-dots-fill"></i> Chat <?= $namaMekanik ?> Sekarang
          </a>
        </div>
      <?php endif; ?>
    </div>
  </main>

</body>
</html>
