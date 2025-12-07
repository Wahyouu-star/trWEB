<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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

<!-- ===== NAVBAR ===== -->
<header class="consistent-navbar">
  <a class="navbar-brand" href="beranda.php">AUTO CARE</a>
  <div class="nav-links">
    <a class="nav-link-text" href="beranda.php">Beranda</a>
    <a href="profil.php" class="profile-icon-wrapper">
      <img src="IMG/profil.jpg">
    </a>
  </div>
</header>

<!-- ===== KONTEN RIWAYAT ===== -->
<main>

<div class="judul">Riwayat Servis</div>

<div id="riwayatList"></div>

<button class="hapus-semua" onclick="hapusRiwayat()">Hapus Semua Riwayat</button>

</main>

<!-- ===== SCRIPT ===== -->
<script>
const list = document.getElementById("riwayatList");
const data = JSON.parse(localStorage.getItem("riwayat_servis")) || [];

if(data.length === 0){
  list.innerHTML = "<div class='kosong'>Belum ada riwayat servis.</div>";
}else{
  data.forEach(item=>{
    const div = document.createElement("div");
    div.className = "svc-card";
    div.innerHTML = `
      <b>${item.jenis}</b><br><br>
      Jarak: ${item.jarak} km<br>
      Durasi: ${item.durasi}<br>
      Waktu: ${item.waktu}<br>
      Interval: ${item.interval}<br><br>
      <small>${item.tanggal}</small>
    `;
    list.appendChild(div);
  });
}

function hapusRiwayat(){
  if(confirm("Yakin ingin menghapus semua riwayat servis?")){
    localStorage.removeItem("riwayat_servis");
    location.reload();
  }
}
</script>

</body>
</html>
