<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama   = trim($_POST['nama']);
  $tgl    = trim($_POST['tgl']);
  $gender = trim($_POST['gender']);
  $telp   = trim($_POST['telp']);
  $email  = trim($_POST['email']);

  if ($nama && $tgl && $gender && $telp && $email) {
    echo "
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const popup = document.createElement('div');
        popup.className = 'popup-overlay';

        popup.innerHTML = `
          <div class='popup-box'>
            <div class='popup-icon'>✔</div>
            <h3>Berhasil</h3>
            <p>Data profil berhasil diperbarui</p>
            <button class='popup-btn' onclick='window.location=\"sparepart.php\";'>OK</button>
          </div>
        `;
        document.body.appendChild(popup);
      });
    </script>
    ";
  } else {
    echo "<script>alert('Mohon lengkapi semua data sebelum menyimpan profil!');</script>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil</title>

    <!-- CSS utama -->
    <link rel="stylesheet" href="style.css">
    <!-- Icon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Tambahan style untuk header profil + tombol logout -->
    <style>
      .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
      }

      .profile-header h2 {
        margin: 0;
      }

      .logout-btn {
        font-size: 0.9rem;
        background-color: #c24a4a;
        color: #fff;
        padding: 6px 12px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.2s;
      }

      .logout-btn:hover {
        background-color: #a83838;
        color: #fff;
      }
    </style>
</head>

<body>

<?php include "inc/header.php"; ?>

<main>
  <div class="profile-container">

    <!-- HEADER PROFIL + LOGOUT -->
    <div class="profile-header">
      <h2>Edit Profil</h2>

      <!-- Bisa pakai link biasa atau form POST, di sini pakai link ke logout.php -->
      <a href="logout.php" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>

    <form method="POST">
      <label>Nama Lengkap</label>
      <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>

      <label>Tanggal Lahir</label>
      <div class="date-input">
        <input type="date" name="tgl" required>
        <i class="bi bi-calendar-date"></i>
      </div>

      <label>Jenis Kelamin</label>
      <div class="gender-btns">
        <input type="hidden" name="gender" id="gender">
        <button type="button" class="gender-btn active" onclick="setGender('Laki-Laki', this)">
          <i class="bi bi-person-fill"></i> Laki-Laki
        </button>
        <button type="button" class="gender-btn" onclick="setGender('Perempuan', this)">
          <i class="bi bi-person-standing"></i> Perempuan
        </button>
      </div>

      <label>Nomor Telepon</label>
      <input type="tel" name="telp" placeholder="Masukkan nomor telepon" required>

      <label>Email Anda</label>
      <input type="email" name="email" placeholder="Masukkan email" required>

      <button type="submit" class="edit-btn">Edit Profil</button>
    </form>
  </div>
</main>

<script>
  document.getElementById('gender').value = "Laki-Laki";

  function setGender(value, el) {
    document.getElementById('gender').value = value;
    document.querySelectorAll('.gender-btn').forEach(btn => btn.classList.remove('active'));
    el.classList.add('active');
  }
</script>

<?php include "inc/footer.php"; ?>

</body>
</html>
