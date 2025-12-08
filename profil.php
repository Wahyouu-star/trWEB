<?php
session_start();
include "inc/header.php"; 
include "inc/koneksi.php"; 

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['id'] == 0) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$status_success = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama   = mysqli_real_escape_string($conn, trim($_POST['nama']));
  $tgl    = mysqli_real_escape_string($conn, trim($_POST['tgl']));
  $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
  $telp   = mysqli_real_escape_string($conn, trim($_POST['telp']));
  $email  = mysqli_real_escape_string($conn, trim($_POST['email']));

  if ($nama && $tgl && $gender && $telp && $email) {
    $sql_update = "UPDATE users SET 
                    nama = '$nama', 
                    tgl_lahir = '$tgl', 
                    gender = '$gender', 
                    telp = '$telp', 
                    email = '$email'
                   WHERE id = $user_id";

    if (mysqli_query($conn, $sql_update)) {
        $status_success = true;
    } else {
        $error_message = "Gagal menyimpan data ke database: " . mysqli_error($conn);
    }
  } else {
    $error_message = "Mohon lengkapi semua data sebelum menyimpan profil!";
  }
}

$sql_select = "SELECT nama, tgl_lahir, gender, telp, email FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql_select);
$data_profil = mysqli_fetch_assoc($result);

$current_nama = $data_profil['nama'] ?? '';
$current_tgl = $data_profil['tgl_lahir'] ?? '';
$current_gender = $data_profil['gender'] ?? 'Laki-Laki'; 
$current_telp = $data_profil['telp'] ?? '';
$current_email = $data_profil['email'] ?? '';

if ($status_success) {
    $current_nama = $nama;
    $current_tgl = $tgl;
    $current_gender = $gender;
    $current_telp = $telp;
    $current_email = $email;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil</title>

    <style>
      body {
          margin: 0;
          font-family: Poppins, sans-serif;
          background: #f5f5f5;
      }

      .popup-overlay {
          position: fixed;
          inset: 0;
          background: rgba(0,0,0,0.45);
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 9999;
          animation: fadeInBg 0.3s ease;
      }

      .popup-box {
          width: 240px;
          background: #ffffff;
          padding: 25px;
          border-radius: 15px;
          text-align: center;
          box-shadow: 0px 5px 12px rgba(0,0,0,0.2);
          animation: fadeInPopup 0.35s ease;
      }

      .popup-icon {
          width: 70px;
          height: 70px;
          background: #2ecc71; 
          border-radius: 50%;
          color: #fff;
          font-size: 38px;
          display: flex;
          justify-content: center;
          align-items: center;
          margin: 0 auto 12px;
      }
      
      .popup-box h3 {
          font-size: 22px;
          margin-bottom: 8px;
      }

      .popup-box p {
          font-size: 14px;
          margin-bottom: 18px;
      }

      .popup-btn {
          background: #c34646;
          color: #fff;
          border: none;
          padding: 8px 25px;
          border-radius: 20px;
          font-size: 15px;
          cursor: pointer;
          box-shadow: 0px 3px 6px rgba(0,0,0,0.25);
          transition: 0.2s;
      }

      .popup-btn:hover {
          background: #a73737;
      }

      @keyframes fadeInPopup {
          from { transform: scale(0.85); opacity: 0; }
          to { transform: scale(1); opacity: 1; }
      }

      @keyframes fadeInBg {
          from { opacity: 0; }
          to { opacity: 1; }
      }
      
      .profile-container {
        width: 450px;
        margin: 100px auto 50px auto; 
        padding: 25px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      }

      .profile-container h2 {
        text-align: center;
        margin-bottom: 25px;
      }

      .profile-container label {
        font-weight: 600;
        margin-top: 10px;
        display: block;
      }

      .profile-container input[type="text"],
      .profile-container input[type="date"],
      .profile-container input[type="tel"],
      .profile-container input[type="email"] {
        width: 100%;
        padding: 8px 5px;
        margin-top: 3px;
        border: none;
        border-bottom: 1px solid #888;
        outline: none;
        font-size: 15px;
      }

      .date-input {
        position: relative;
      }

      .date-input i {
        position: absolute;
        right: 10px;
        top: 10px;
        color: #b30000;
        font-size: 20px;
      }

      .gender-btns {
        display: flex;
        gap: 10px;
        margin: 10px 0;
      }

      .gender-btn {
        flex: 1;
        padding: 12px 0;
        border: none;
        background: #d9534f;
        color: white;
        font-weight: bold;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
      }

      .gender-btn i {
        margin-right: 5px;
      }

      .gender-btn.active {
        background: #c9302c;
        transform: scale(1.05);
      }

      .gender-btn:hover {
        background: #c9302c;
      }

      .edit-btn {
        margin-top: 25px;
        background: #d9534f;
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 8px;
        cursor: pointer;
        display: block;
        margin-left: auto;
      }


      .edit-btn:hover {
        background: #c9302c;
      }

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

<main>
  <div class="profile-container">

    <div class="profile-header">
      <h2>Edit Profil</h2>

      <a href="logout.php" class="logout-btn">
        → Logout </a>
    </div>

    <?php if ($error_message): ?>
        <p style="color: red; text-align: center; font-weight: bold;"><?= $error_message ?></p>
    <?php endif; ?>

    <form method="POST">
      <label>Nama Lengkap</label>
      <input type="text" name="nama" placeholder="Masukkan nama lengkap" value="<?= htmlspecialchars($current_nama) ?>" required>

      <label>Tanggal Lahir</label>
      <div class="date-input">
        <input type="date" name="tgl" value="<?= htmlspecialchars($current_tgl) ?>" required>
        📅 </div>

      <label>Jenis Kelamin</label>
      <div class="gender-btns">
        <input type="hidden" name="gender" id="gender" value="<?= htmlspecialchars($current_gender) ?>">
        <button type="button" class="gender-btn <?= ($current_gender == 'Laki-Laki') ? 'active' : '' ?>" onclick="setGender('Laki-Laki', this)">
          👤 Laki-Laki </button>
        <button type="button" class="gender-btn <?= ($current_gender == 'Perempuan') ? 'active' : '' ?>" onclick="setGender('Perempuan', this)">
          🧍‍♀️ Perempuan </button>
      </div>

      <label>Nomor Telepon</label>
      <input type="tel" name="telp" placeholder="Masukkan nomor telepon" value="<?= htmlspecialchars($current_telp) ?>" required>

      <label>Email Anda</label>
      <input type="email" name="email" placeholder="Masukkan email" value="<?= htmlspecialchars($current_email) ?>" required>

      <button type="submit" class="edit-btn">Edit Profil</button>
    </form>
  </div>
</main>

<?php if ($status_success): ?>
<div class='popup-overlay'>
  <div class='popup-box'>
    <div class='popup-icon'>✔</div>
    <h3>Berhasil</h3>
    <p>Data profil berhasil diperbarui</p>
    <button class='popup-btn' onclick='window.location="profil.php";'>OK</button>
  </div>
</div>
<?php endif; ?>


<script>
  // Script untuk mengatur tampilan tombol gender yang aktif saat halaman dimuat
  document.addEventListener('DOMContentLoaded', function() {
      const currentGender = document.getElementById('gender').value;
      document.querySelectorAll('.gender-btn').forEach(btn => {
          if (btn.textContent.trim().includes(currentGender)) {
              btn.classList.add('active');
          } else {
              btn.classList.remove('active');
          }
      });
  });

  function setGender(value, el) {
    document.getElementById('gender').value = value;
    document.querySelectorAll('.gender-btn').forEach(btn => btn.classList.remove('active'));
    el.classList.add('active');
  }
</script>

<?php include "inc/footer.php"; ?>

</body>
</html>