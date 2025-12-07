<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Auto Care</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      width: 100%;
      height: 100%;
      font-family: "Poppins", sans-serif;
      background-color: #f0f0f0;
      overflow-x: hidden;
    }

    body {
      background-color: #f0f0f0;
    }

    header {
      width: 100%;
      background: white;
    }

    /* ===== MAIN ===== */
    main {
      width: 100vw;
      margin-top: 100px;
      background-color: #f0f0f0;
      padding: 60px 0;
      display: flex;
      justify-content: center;
    }

    /* ===== WRAPPER SEJAJAR ===== */
    .layout-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 80px;
      width: 100%;
    }

    /* ===== KARTU MEKANIK (DITURUNKAN) ===== */
    .mekanik-card {
      background-color: #fff;
      width: 260px;
      height: 320px;
      border-radius: 14px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.12);
      text-align: center;
      margin-top: 40px;   /* ✅ INI YANG MENURUNKAN POSISINYA */
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

    /* ===== FORM CONTAINER ===== */
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
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.12);
      padding: 14px 26px;
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
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.12);
      width: 420px;
      padding: 28px 34px;
    }

    .data-form h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 18px;
      font-weight: 700;
    }

    .form-group {
      margin-bottom: 16px;
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
      padding: 12px;
      background-color: #c0392b;
      color: #fff;
      border: none;
      border-radius: 24px;
      font-size: 14px;
      cursor: pointer;
      margin-top: 18px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .submit-btn:hover {
      background-color: #a93226;
    }

    .chosen {
      font-size: 13px;
      color: #555;
      margin-top: 5px;
      text-align: center;
    }
  </style>
</head>

<body>

  <?php include __DIR__ . "/inc/header.php"; ?>

  <main>

    <div class="layout-wrapper">

      <!-- KARTU MEKANIK -->
      <div class="mekanik-card">
        <img src="IMG/konsultasi.png" alt="Mekanik">
        <h3>Nama Mekanik</h3>
      </div>

      <!-- FORM -->
      <div class="form-container">

        <!-- PILIH TANGGAL & JAM -->
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

        <!-- FORM DATA DIRI -->
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

    </div>

  </main>

  <script>
    document.getElementById('btnTanggal').addEventListener('click', function() {
      const tanggal = prompt("Masukkan tanggal (format: YYYY-MM-DD):");
      if (tanggal) {
        document.getElementById('tanggalTerpilih').textContent = tanggal;
        document.getElementById('inputTanggal').value = tanggal;
      }
    });

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
