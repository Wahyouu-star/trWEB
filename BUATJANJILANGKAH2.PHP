<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Auto Care</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      width: 100%;
      font-family: "Poppins", sans-serif;
      background-color: #f4f4f4;
    }

    /* ===== HEADER ===== */
    header {
      width: 100%;
      background-color: #ffffff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      position: fixed;
      top: 0;
      left: 0;
      z-index: 100;
    }

    header h1 {
      font-size: 20px;
      font-weight: 700;
      color: #000;
    }

    .profile {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 16px;
      color: #000;
    }

    .profile i {
      background-color: #c0392b;
      color: #fff;
      border-radius: 50%;
      padding: 6px;
      font-style: normal;
    }

    /* ===== TOMBOL BACK DI BAWAH HEADER ===== */
    .back-btn {
      position: fixed;
      top: 75px;
      left: 30px;
      font-size: 28px;
      font-weight: bold;
      color: #000;
      text-decoration: none;
      z-index: 99;
      background: #fff;
      padding: 4px 10px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      transition: all 0.2s;
    }

    .back-btn:hover {
      background-color: #c0392b;
      color: #fff;
    }

    /* ===== MAIN CONTENT ===== */
    main {
      width: 100%;
      height: calc(100vh - 70px);
      margin-top: 100px;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f0f0f0;
      gap: 60px;
    }

    /* ===== KARTU MEKANIK ===== */
    .mekanik-card {
      background-color: #fff;
      width: 260px;
      height: 260px;
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      text-align: center;
    }

    .mekanik-card img {
      width: 120px;
      height: 120px;
      margin-bottom: 15px;
    }

    .mekanik-card h3 {
      font-size: 16px;
      font-weight: 600;
    }

    /* ===== FORM ===== */
    .form-container {
      display: flex;
      flex-direction: column;
      gap: 20px;
      align-items: center;
    }

    .date-time {
      display: flex;
      gap: 20px;
    }

    .date-time button {
      background-color: #fff;
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      padding: 15px 25px;
      font-size: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .date-time button:hover {
      background-color: #f9f9f9;
    }

    .data-form {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      width: 400px;
      padding: 25px 30px;
    }

    .data-form h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 18px;
      font-weight: 700;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group input {
      width: 100%;
      border: none;
      border-bottom: 1px solid #000;
      padding: 8px 0;
      font-size: 14px;
      outline: none;
      background: transparent;
    }

    .submit-btn {
      width: 100%;
      padding: 10px;
      background-color: #c0392b;
      color: #fff;
      border: none;
      border-radius: 20px;
      font-size: 14px;
      cursor: pointer;
      margin-top: 15px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .submit-btn:hover {
      background-color: #a93226;
    }

    /* teks kecil hasil pilihan */
    .chosen {
      font-size: 13px;
      color: #555;
      margin-top: 5px;
      text-align: center;
    }
  </style>
</head>
<body>
  <header>
    <h1>AUTO CARE</h1>
    <div class="profile">
      <span>Beranda</span>
      <i>👤</i>
    </div>
  </header>

  <a href="javascript:history.back()" class="back-btn">←</a>

  <main>
    <div class="mekanik-card">
      <img src="https://cdn-icons-png.flaticon.com/512/4715/4715325.png" alt="Mekanik">
      <h3>Nama Mekanik</h3>
    </div>

    <div class="form-container">
      <div class="date-time">
        <div>
          <button type="button" id="btnTanggal">Pilih tanggal 📅</button>
          <div class="chosen" id="tanggalTerpilih">Belum dipilih</div>
        </div>
        <div>
          <button type="button" id="btnJam">Pilih jam ⏱️</button>
          <div class="chosen" id="jamTerpilih">Belum dipilih</div>
        </div>
      </div>

      <div class="data-form">
        <h2>Isi Data Diri</h2>
        <form action="proses_data.php" method="POST">
          <input type="hidden" id="inputTanggal" name="tanggal">
          <input type="hidden" id="inputJam" name="jam">

          <div class="form-group">
            <input type="text" name="nama" placeholder="Nama" required>
          </div>
          <div class="form-group">
            <input type="text" name="alamat" placeholder="Alamat" required>
          </div>
          <div class="form-group">
            <input type="text" name="notelp" placeholder="No. Telp" required>
          </div>
          <div class="form-group">
            <input type="text" name="kendaraan" placeholder="Jenis Kendaraan" required>
          </div>
          <button class="submit-btn" type="submit">LANJUTKAN</button>
        </form>
      </div>
    </div>
  </main>

  <script>
    // Tombol Pilih Tanggal
    document.getElementById('btnTanggal').addEventListener('click', function() {
      const tanggal = prompt("Masukkan tanggal (format: YYYY-MM-DD):");
      if (tanggal) {
        document.getElementById('tanggalTerpilih').textContent = tanggal;
        document.getElementById('inputTanggal').value = tanggal;
      }
    });

    // Tombol Pilih Jam
    document.getElementById('btnJam').addEventListener('click', function() {
      const jam = prompt("Masukkan jam (format: HH:MM):");
      if (jam) {
        document.getElementById('jamTerpilih').textContent = jam;
        document.getElementById('inputJam').value = jam;
      }
    });
  </script>
</body>
</html>
