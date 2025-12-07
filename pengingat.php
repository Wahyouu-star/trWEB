<?php
include "inc/header.php"; // pakai header template kamu
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengingat Servis</title>

<style>
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

<div id="list"></div>

<button class="btn-tambah" onclick="bukaModal()">Tambah +</button>

<!-- MODAL INPUT -->
<div class="modal" id="modal">
  <div class="modal-box">
    <h3>Tambah Jadwal</h3>

    <div class="form-group">
      <label>Jenis Servis</label>
      <input id="jenis">
    </div>

    <div class="form-group">
      <label>Jarak (km)</label>
      <input type="number" id="jarak">
    </div>

    <div class="form-group">
      <label>Durasi (jam)</label>
      <input type="number" id="durasi">
    </div>

    <div class="form-group">
      <label>Waktu</label>
      <input type="time" id="waktu">
    </div>

    <div class="form-group">
      <label>Interval</label>
      <select id="interval">
        <option>3 bulan / 5000 km</option>
        <option>6 bulan / 10000 km</option>
        <option>12 bulan / 20000 km</option>
      </select>
    </div>

    <div class="modal-footer">
      <button class="btn btn-hapus" onclick="tutupModal()">Batal</button>
      <button class="btn btn-selesai" onclick="simpan()">Tambah</button>
    </div>
  </div>
</div>

<!-- POPUP -->
<div class="success-popup" id="popup">
  <div class="success-card">
    <div class="success-icon">✔</div>
    <h3>Berhasil</h3>
    <p>Jadwal berhasil ditambahkan</p>
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

function simpan(){
  let jenis = document.getElementById("jenis").value;
  let jarak = document.getElementById("jarak").value;
  let durasi = document.getElementById("durasi").value;
  let waktu = document.getElementById("waktu").value;
  let interval = document.getElementById("interval").value;

  let card = `
  <div class="svc-card">
    <div class="svc-title">${jenis}</div>
    <div class="svc-meta">
      Jarak: ${jarak} km<br>
      Durasi: ${durasi} jam<br>
      Waktu: ${waktu}<br>
      Interval: ${interval}
    </div>

    <div class="card-footer">
      <small>${new Date().toLocaleString()}</small>
      <div>
        <button class="btn btn-hapus" onclick="this.closest('.svc-card').remove()">Hapus</button>
        <button class="btn btn-selesai" onclick="this.closest('.svc-card').classList.add('selesai')">Selesai</button>
      </div>
    </div>
  </div>`;

  document.getElementById("list").innerHTML += card;

  tutupModal();
  document.getElementById("popup").style.display="flex";
}

function tutupPopup(){
  document.getElementById("popup").style.display="none";
}
</script>

</body>
</html>
