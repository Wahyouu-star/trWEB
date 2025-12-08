<?php
// riwayat.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "inc/koneksi.php";

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['id'] == 0) {
    // Redirect jika tidak ada ID user (termasuk Guest)
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// ==================================
// 1. HAPUS SEMUA RIWAYAT DARI DATABASE (DENGAN user_id)
// ==================================
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    // Mengubah TRUNCATE menjadi DELETE WHERE user_id agar hanya menghapus data user ini
    $sql_delete = "DELETE FROM riwayat WHERE user_id = $user_id"; 
    
    if (mysqli_query($conn, $sql_delete)) {
        header('Location: riwayat.php?status=cleared');
        exit;
    } else {
        echo "<script>alert('Gagal menghapus riwayat: " . mysqli_error($conn) . "');</script>";
    }
}

// ==================================
// 2. AMBIL DATA DARI DATABASE (DENGAN user_id)
// ==================================
// Ambil hanya data riwayat milik user yang sedang login
$result = mysqli_query($conn, "SELECT * FROM riwayat WHERE user_id = $user_id ORDER BY selesai_pada DESC");
$riwayat_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Servis - AUTO CARE</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
  margin:0;
  font-family: 'Poppins', Arial, sans-serif;
  background:#fff;
}

/* ===== NAVBAR ===== */
.consistent-navbar {
  width: 100%;
  height: 68px;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 100;
  background: #ffffff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 40px;
  box-sizing: border-box;
}

.navbar-brand {
  font-weight: 700;
  color: #b22929;
  font-size: 22px; 
  text-decoration: none;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 20px;
}

.nav-link-text {
  color: #000;
  font-weight: 500;
  text-decoration: none;
}

.profile-icon-wrapper img {
  width: 38px;
}

/* ===== KONTEN ===== */
main{
  padding-top: 110px;
  max-width: 900px;
  margin: auto;
}

.judul{
  background:#2ea44f;
  color:white;
  padding:14px 40px;
  border-radius:999px;
  width:max-content;
  margin:0 auto 30px;
  font-weight:600;
  box-shadow:0 6px 18px rgba(0,0,0,0.2);
}

#riwayatList{
  display:flex;
  flex-direction:column;
  gap:16px;
  padding:0 20px 60px;
}

.svc-card{
  background:white;
  border-radius:12px;
  padding:16px;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
  border-left:6px solid #2ea44f;
}

.svc-card b{
  font-size:16px;
}

.kosong{
  background:white;
  padding:20px;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
  text-align:center;
  color:#666;
}

.hapus-semua{
  background:#d32f2f;
  color:white;
  border:none;
  padding:12px 24px;
  border-radius:10px;
  cursor:pointer;
  margin:20px auto;
  display:block;
}
</style>
</head>

<body>

<header class="consistent-navbar">
  <a class="navbar-brand" href="beranda.php">AUTO CARE</a>
  <div class="nav-links">
    <a class="nav-link-text" href="beranda.php">Beranda</a>
    <a href="profil.php" class="profile-icon-wrapper">
      <img src="IMG/profil.jpg">
    </a>
  </div>
</header>

<main>

<div class="judul">Riwayat Servis</div>

<div id="riwayatList">
<?php if (empty($riwayat_list)): ?>
    <div class='kosong'>Belum ada riwayat servis.</div>
<?php else: ?>
    <?php foreach ($riwayat_list as $item): ?>
        <div class="svc-card">
            <b><?= htmlspecialchars($item['jenis_servis']) ?></b><br><br>
            Jarak: <?= htmlspecialchars($item['jarak']) ?> km<br>
            Durasi: <?= htmlspecialchars($item['durasi']) ?> jam<br>
            Waktu: <?= htmlspecialchars($item['waktu']) ?><br>
            Interval: <?= htmlspecialchars($item['interval_servis']) ?><br><br>
            <small>Tanggal Selesai: <?= date('d M Y H:i', strtotime($item['selesai_pada'])) ?></small>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<button class="hapus-semua" onclick="hapusRiwayat()">Hapus Semua Riwayat</button>

</main>

<script>
function hapusRiwayat(){
  if(confirm("Yakin ingin menghapus semua riwayat servis? Tindakan ini tidak dapat dibatalkan.")){
    // Redirect ke URL dengan parameter clear
    window.location.href = "riwayat.php?action=clear";
  }
}
</script>

</body>
</html>