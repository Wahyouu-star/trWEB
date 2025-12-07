<?php
// Jika form disubmit, ambil data dari POST
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
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background-color: #f5f5f5;
    }

    /* Header */
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

    /* Konten utama */
    main {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 50px 20px;
      gap: 50px;
    }

    /* Kartu mekanik */
    .card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      width: 250px;
      height: 300px;
      text-align: center;
      padding: 20px;
    }

    .card img {
      width: 100px;
      height: 100px;
      margin-top: 40px;
    }

    .card p {
      margin-top: 20px;
      font-weight: 600;
    }

    /* Form */
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

    /* Tombol panah kembali */
    .back-btn {
      position: absolute;
      top: 80px;
      left: 30px;
      font-size: 30px;
      color: #777;
      text-decoration: none;
    }

    /* Hasil data */
    .hasil {
      margin-top: 30px;
      padding: 15px;
      background-color: #eaf6ea;
      border: 1px solid #b5e0b5;
      border-radius: 8px;
    }
  </style>
</head>
<body>

  <header>
    <h1>AUTO CARE</h1>
    <div class="menu-kanan">
      <a href="#">Beranda</a>
      <div class="icon-user">👤</div>
    </div>
  </header>

  <a href="#" class="back-btn">←</a>

  <main>
    <!-- Kartu Mekanik -->
    <div class="card">
      <img src="https://cdn-icons-png.flaticon.com/512/679/679922.png" alt="Mekanik">
      <p>Nama Mekanik</p>
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
          <option value="Mobil">Mobil</option>
          <option value="Motor">Motor</option>
          <option value="Lainnya">Lainnya</option>
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
      <?php endif; ?>
    </div>
  </main>

</body>
</html>
