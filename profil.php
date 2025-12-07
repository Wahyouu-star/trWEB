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

    <style>
      body {
          margin: 0;
          font-family: Poppins, sans-serif;
          background: #f5f5f5;
      }

      .header {
          width: 100%;
          background: white;
          padding: 15px 40px;
          display: flex;
          justify-content: space-between;
          align-items: center;
          border-bottom: 2px solid #eee;
      }

      .right {
          display: flex;
          align-items: center;
          gap: 35px;
          margin-right: 70px;
      }

      .menu-text {
          margin-right: 10px;
      }


      .profile-icon {
          text-decoration: none;
      }

      .avatar {
          width: 35px;
          height: 35px;
          border-radius: 50%;
          overflow: hidden;
          display: flex;
          justify-content: center;
          align-items: center;
          background: #ddd;
      }

      .avatar-img {
          width: 100%;
          height: 100%;
          object-fit: cover;
      }



      .logo {
          font-size: 22px;
          font-weight: 700;
      }

      .top-bar {
          width: 100%;
          display: flex;
          justify-content: center;
          align-items: center;
          padding: 15px 40px;
          margin-top: 10px;
      }

      .search-box {
          width: 70%;
          max-width: 600px;
          background: #f8f8f8;
          padding: 10px 15px;
          border-radius: 10px;
          display: flex;
          align-items: center;
          border: 1px solid #ddd;
      }

      .search-box i {
          margin-right: 10px;
          color: #777;
      }

      .search-box input {
          width: 100%;
          border: none;
          outline: none;
          font-size: 15px;
          background: transparent;
      }

      .cart-page-icon {
          position: relative;
      }

      .cart-page-icon img {
          width: 30px;
      }

      .notif {
          position: absolute;
          top: -6px;
          right: -8px;
          background: red;
          color: white;
          padding: 3px 7px;
          border-radius: 50%;
          font-size: 12px;
      }


      /* PRODUCT GRID */
      .container {
          padding: 30px;
      }

      .title {
          margin-bottom: 15px;
      }

      .product-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
          gap: 20px;
      }

      .product-card {
          background: white;
          padding: 15px;
          border-radius: 12px;
          box-shadow: 0px 4px 10px rgba(0,0,0,0.05);
          text-align: center;
      }

      .product-card .img {
          width: 100%;
          height: 120px;
          background: #ddd;
          border-radius: 10px;
          margin-bottom: 10px;
      }

      .product-card .img {
          width: 100%;
          height: 140px;
          background: #ddd;
          border-radius: 10px;
          margin-bottom: 10px;
          overflow: hidden;
          display: flex;
          justify-content: center;
          align-items: center;
      }

      .product-card .img img {
          width: 100%;
          height: 100%;
          object-fit: cover;
      }

      .cart-img2 {
          width: 80px;
          height: 80px;
          background: #f5f5f5;
          border-radius: 10px;
          overflow: hidden;
          display: flex;
          justify-content: center;
          align-items: center;
      }

      .cart-img2 img {
          width: 100%;
          height: 100%;
          object-fit: contain;
      }



      .btn-add {
          background: #c0392b;
          color: white;
          padding: 8px 15px;
          border-radius: 8px;
          text-decoration: none;
          display: inline-block;
      }

      /* CART PAGE */
      .cart-box {
          background: white;
          padding: 20px;
          border-radius: 12px;
          max-width: 500px;
      }

      .cart-item {
          display: flex;
          gap: 15px;
          margin-bottom: 15px;
      }

      .img-small {
          width: 60px;
          height: 60px;
          background: #ddd;
          border-radius: 10px;
      }

      .btn-pay {
          background: #c0392b;
          width: 100%;
          padding: 12px;
          border: none;
          border-radius: 10px;
          color: white;
          font-size: 16px;
      }

      .pay-container {
          margin-top: 30px;
          display: grid;
          grid-template-columns: 1.2fr 1fr;
          gap: 30px;
      }

      /* LIST PRODUK KIRI */
      .cart-list-box {
          background: #fff;
          padding: 25px;
          border-radius: 15px;
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
          height: fit-content;
      }

      .cart-item2 {
          display: flex;
          gap: 15px;
          padding-bottom: 20px;
          margin-bottom: 20px;
          border-bottom: 1px solid #eee;
      }

      .cart-img2 {
          width: 80px;
          height: 80px;
          background: #e6e6e6;
          border-radius: 15px;
      }

      /* FORM PEMBAYARAN */
      .pay-box {
          background: #fff;
          padding: 35px;
          border-radius: 20px;
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      }

      .pay-box input {
          width: 100%;
          padding: 10px 0;
          border: none;
          border-bottom: 1px solid #bbb;
          margin-bottom: 20px;
          outline: none;
      }

      .btn-pay-new {
          width: 100%;
          padding: 15px;
          font-size: 18px;
          background: #c0392b;
          color: white;
          border: none;
          border-radius: 25px;
          cursor: pointer;
          margin-top: 20px;
          box-shadow: 0 4px 8px rgba(192,57,43,0.3);
      }

      .btn-pay-new:hover {
          background: #a83226;
      }

      /* POPUP BACKGROUND */
      .popup {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0,0,0,0.5);
          display: none;
          justify-content: center;
          align-items: center;
          z-index: 9999;
      }

      /* POPUP BOX */
      .popup-box {
          width: 260px;
          background: white;
          border-radius: 15px;
          padding: 20px;
          text-align: center;
          box-shadow: 0px 6px 15px rgba(0,0,0,0.2);
      }

      /* ICON CIRCLE */
      .popup-icon {
          width: 70px;
          height: 70px;
          margin: 0 auto 10px;
          background: #c34646;
          color: white;
          font-size: 40px;
          font-weight: bold;
          border-radius: 50%;
          display: flex;
          justify-content: center;
          align-items: center;
      }

      /* TITLE */
      .popup-box h3 {
          font-size: 22px;
          margin-top: 5px;
      }

      /* TEXT */
      .popup-box p {
          margin: 10px 0 20px;
          font-size: 14px;
      }

      /* OK BUTTON */
      .popup-ok {
          background: #c34646;
          color: white;
          border: none;
          padding: 8px 25px;
          border-radius: 10px;
          font-size: 15px;
          cursor: pointer;
      }

      .popup-ok:hover {
          background: #a63434;
      }

      /* ================================
         POPUP SUCCESS (GLOBAL STYLE)
         ================================ */

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

      /* ICON BULAT MERAH */
      .popup-icon {
          width: 70px;
          height: 70px;
          background: #c34646;
          border-radius: 50%;
          color: #fff;
          font-size: 38px;
          display: flex;
          justify-content: center;
          align-items: center;
          margin: 0 auto 12px;
      }

      /* TEXT */
      .popup-box h3 {
          font-size: 22px;
          margin-bottom: 8px;
      }

      .popup-box p {
          font-size: 14px;
          margin-bottom: 18px;
      }

      /* BUTTON */
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

      /* ANIMASI */
      @keyframes fadeInPopup {
          from { transform: scale(0.85); opacity: 0; }
          to { transform: scale(1); opacity: 1; }
      }

      @keyframes fadeInBg {
          from { opacity: 0; }
          to { opacity: 1; }
      }

      /* Container Card */
      .profile-container {
        width: 450px;
        margin: 50px auto;
        padding: 25px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      }

      /* Title */
      .profile-container h2 {
        text-align: center;
        margin-bottom: 25px;
      }

      /* Label */
      .profile-container label {
        font-weight: 600;
        margin-top: 10px;
        display: block;
      }

      /* Input */
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

      /* Date Input with Icon */
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

      /* Gender Button */
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

      /* Popup */
      .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .popup-box {
        background: white;
        padding: 20px 30px;
        border-radius: 12px;
        text-align: center;
        width: 300px;
      }

      .popup-icon {
        font-size: 40px;
        color: green;
        margin-bottom: 10px;
      }

      .popup-btn {
        margin-top: 12px;
        background: #d9534f;
        color: white;
        padding: 8px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
      }

      .footer {
          width: 100%;
          background: #ffffff;
          border-top: 2px solid #eee;
          padding: 15px 0;
          margin-top: 40px;
      }

      .footer-container {
          text-align: center;
          font-size: 14px;
          color: #777;
      }

      /* Tambahan style dari profil.php */
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

    <div class="profile-header">
      <h2>Edit Profil</h2>

      <a href="logout.php" class="logout-btn">
        → Logout </a>
    </div>

    <form method="POST">
      <label>Nama Lengkap</label>
      <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>

      <label>Tanggal Lahir</label>
      <div class="date-input">
        <input type="date" name="tgl" required>
        📅 </div>

      <label>Jenis Kelamin</label>
      <div class="gender-btns">
        <input type="hidden" name="gender" id="gender">
        <button type="button" class="gender-btn active" onclick="setGender('Laki-Laki', this)">
          👤 Laki-Laki </button>
        <button type="button" class="gender-btn" onclick="setGender('Perempuan', this)">
          🧍‍♀️ Perempuan </button>
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