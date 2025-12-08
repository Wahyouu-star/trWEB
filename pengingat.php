<?php
// pengingat.php
include "inc/header.php";
include "inc/koneksi.php";

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['id'] == 0) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// ==================================
// 1. TAMBAH DATA KE DATABASE
// ==================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $jenis          = mysqli_real_escape_string($conn, $_POST['jenis'] ?? '');
    $jarak          = (int)($_POST['jarak'] ?? 0);
    $durasi_jam     = (int)($_POST['durasi_jam'] ?? 0);
    $waktu          = mysqli_real_escape_string($conn, $_POST['waktu'] ?? '');
    $interval_servis = mysqli_real_escape_string($conn, $_POST['interval'] ?? '');

    $sql_insert = "INSERT INTO pengingat (user_id, jenis_servis, jarak, durasi, waktu, interval_servis, created_at) 
                   VALUES ($user_id, '$jenis', $jarak, $durasi_jam, '$waktu', '$interval_servis', NOW())";

    if (mysqli_query($conn, $sql_insert)) {
        header('Location: pengingat.php?status=success');
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan pengingat: " . mysqli_error($conn) . "');</script>";
    }
}

// ==================================
// 2. HAPUS DATA DARI DATABASE
// ==================================
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql_delete = "DELETE FROM pengingat WHERE id = $id AND user_id = $user_id";
    if (mysqli_query($conn, $sql_delete)) {
        header('Location: pengingat.php');
        exit;
    } else {
        echo "<script>alert('Gagal menghapus pengingat: " . mysqli_error($conn) . "');</script>";
    }
}

// ==================================
// 3. AMBIL DATA DARI DATABASE
// ==================================
$result = mysqli_query($conn, "SELECT * FROM pengingat WHERE user_id = $user_id ORDER BY id DESC");
$pengingat_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengingat Servis</title>

<style>
/* ... (Bagian Style CSS Anda, TIDAK PERLU DIUBAH) ... */
body{
  background:#f5f5f5;
  font-family:"Poppins", Arial;
}

/* ===== TITLE ===== */
.title-pill{
  display:inline-block;
  background:#d32f2f;
  color:white;
  padding:12px 34px;
  border-radius:999px;
  font-weight:700;
  margin:100px auto 40px;
  box-shadow:0 8px 18px rgba(0,0,0,.2);
}

/* ===== LIST CARD ===== */
#list{
  max-width:900px;
  margin:0 auto 120px;
  padding:0 20px;
  display:flex;
  flex-direction:column;
  gap:14px;
}

.svc-card{
  background:white;
  border-radius:14px;
  padding:18px;
  box-shadow:0 8px 20px rgba(0,0,0,.1);
  border:1px solid #eee;
}

.svc-title{
  font-weight:700;
  margin-bottom:6px;
}

.svc-meta{
  font-size:14px;
  color:#555;
  line-height:1.5;
}

.card-footer{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-top:14px;
  padding-top:10px;
  border-top:1px solid #eee;
}

.btn{
  padding:7px 14px;
  border-radius:10px;
  border:0;
  cursor:pointer;
  font-weight:600;
}

.btn-hapus{background:#eee}
.btn-selesai{background:#2ecc71;color:white}

.selesai{
  opacity:.5;
  text-decoration:line-through;
}

/* ===== FLOAT BUTTON ===== */
.btn-tambah{
  position:fixed;
  right:26px;
  bottom:26px;
  background:#d32f2f;
  color:white;
  padding:14px 28px;
  border-radius:999px;
  border:0;
  font-weight:700;
  box-shadow:0 8px 18px rgba(211,47,47,.4);
  cursor:pointer;
}

/* ===== MODAL ===== */
.modal{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.45);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:999;
}

.modal-box{
  background:white;
  width:360px;
  padding:26px;
  border-radius:18px;
}

.modal-box h3{
  text-align:center;
  margin-bottom:18px;
}

.form-group{
  margin-bottom:12px;
}

.form-group label{
  font-size:14px;
  font-weight:600;
  margin-bottom:4px;
  display:block;
}

.form-group input,
.form-group select{
  width:100%;
  padding:10px;
  border-radius:10px;
  border:1px solid #ddd;
}

.modal-footer{
  display:flex;
  justify-content:end;
  gap:10px;
  margin-top:14px;
}

/* ===== POPUP SUCCESS ===== */
.success-popup{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.5);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:2000;
}

.success-card{
  background:white;
  width:320px;
  padding:26px;
  border-radius:18px;
  text-align:center;
  box-shadow:0 20px 50px rgba(0,0,0,.3);
}

.success-icon{
  width:60px;
  height:60px;
  background:#2ecc71;
  color:white;
  border-radius:50%;
  font-size:26px;
  display:flex;
  align-items:center;
  justify-content:center;
  margin:0 auto 10px;
}

.btn-ok-modern{
  background:#d32f2f;
  color:white;
  padding:8px 30px;
  border-radius:999px;
  border:0;
  margin-top:14px;
  cursor:pointer;
}
</style>
</head>

<body>

<center>
  <div class="title-pill">Pengingat Servis Rutin</div>
</center>

<div id="list">
  <?php if (empty($pengingat_list)): ?>
    <div class='svc-card' style="text-align:center;">Belum ada pengingat servis yang ditambahkan.</div>
  <?php else: ?>
    <?php foreach ($pengingat_list as $item): ?>
    <div class="svc-card">
      <div class="svc-title"><?= htmlspecialchars($item['jenis_servis']) ?></div>
      <div class="svc-meta">
        Jarak: <?= htmlspecialchars($item['jarak']) ?> km<br>
        Durasi: <?= htmlspecialchars($item['durasi']) ?> jam<br>
        Waktu: <?= htmlspecialchars($item['waktu']) ?><br>
        Interval: <?= htmlspecialchars($item['interval_servis']) ?>
      </div>

      <div class="card-footer">
        <small>Ditambahkan: <?= date('d M Y H:i', strtotime($item['created_at'] ?? date('Y-m-d H:i:s'))) ?></small>
        <div>
          <a href="pengingat.php?delete=<?= $item['id'] ?>" class="btn btn-hapus" onclick="return confirm('Yakin ingin menghapus pengingat ini?');">Hapus</a>
          
          <a href="pindah_ke_riwayat.php?id=<?= $item['id'] ?>" class="btn btn-selesai" onclick="return confirm('Pengingat ini akan dipindahkan ke Riwayat Servis. Lanjutkan?');">Selesai</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<button class="btn-tambah" onclick="bukaModal()">Tambah +</button>

<div class="modal" id="modal">
  <form class="modal-box" method="POST">
    <input type="hidden" name="action" value="add">
    <h3>Tambah Jadwal</h3>

    <div class="form-group">
      <label>Jenis Servis</label>
      <input id="jenis" name="jenis" required>
    </div>

    <div class="form-group">
      <label>Jarak (km)</label>
      <input type="number" id="jarak" name="jarak" required>
    </div>

    <div class="form-group">
      <label>Durasi (jam)</label>
      <input type="number" id="durasi" name="durasi_jam" required>
    </div>

    <div class="form-group">
      <label>Waktu</label>
      <input type="time" id="waktu" name="waktu" required>
    </div>

    <div class="form-group">
      <label>Interval</label>
      <select id="interval" name="interval" required>
        <option>3 bulan / 5000 km</option>
        <option>6 bulan / 10000 km</option>
        <option>12 bulan / 20000 km</option>
      </select>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-hapus" onclick="tutupModal()">Batal</button>
      <button type="submit" class="btn btn-selesai">Tambah</button>
    </div>
  </form>
</div>

<div class="success-popup" id="popup" style="<?= (isset($_GET['status']) && ($_GET['status'] === 'success' || $_GET['status'] === 'moved')) ? 'display:flex;' : 'display:none;' ?>">
  <div class="success-card">
    <div class="success-icon">✔</div>
    <h3>Berhasil</h3>
    <p>Jadwal berhasil <?= ($_GET['status'] === 'moved') ? 'diselesaikan dan dipindahkan ke Riwayat.' : 'ditambahkan'; ?></p>
    <button class="btn-ok-modern" onclick="tutupPopup()">OK</button>
  </div>
</div>

<script>
function bukaModal(){
  document.getElementById("modal").style.display="flex";
}

function tutupModal(){
  document.getElementById("modal").style.display="none";
}

function tutupPopup(){
  document.getElementById("popup").style.display="none";
  // Hapus parameter status dari URL agar popup tidak muncul lagi setelah refresh
  window.history.pushState({}, document.title, "pengingat.php");
}
</script>

</body>
</html>