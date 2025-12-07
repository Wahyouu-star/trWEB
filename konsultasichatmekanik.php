<?php
// konsultasichatmekanik.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =======================
// Ambil nama & bidang mekanik
// =======================
if (isset($_GET['mekanik']) && $_GET['mekanik'] !== '') {
    $namaMekanik = htmlspecialchars($_GET['mekanik']);
    $_SESSION['mekanik_terpilih'] = $namaMekanik;
} elseif (isset($_SESSION['mekanik_terpilih'])) {
    $namaMekanik = $_SESSION['mekanik_terpilih'];
} else {
    $namaMekanik = "Mekanik Tidak Diketahui";
}

if (isset($_GET['bidang']) && $_GET['bidang'] !== '') {
    $bidangSpesialis = htmlspecialchars($_GET['bidang']);
    $_SESSION['bidang_terpilih'] = $bidangSpesialis;
} elseif (isset($_SESSION['bidang_terpilih'])) {
    $bidangSpesialis = $_SESSION['bidang_terpilih'];
} else {
    $bidangSpesialis = "Bidang Tidak Diketahui";
}

$status = "Online";

// =======================
// Daftar keluhan per mekanik (disesuaikan dengan bidang spesialis)
// =======================
$daftarKeluhan = [
    "Sistem Rem & Suspensi" => "Rem blong, kampas aus, shockbreaker bocor, suara berdecit saat pengereman.",
    "Mesin & Tune Up" => "Tarikan berat, boros bensin, mesin pincang, suara kasar saat idle.",
    "Body & Cat" => "Body penyok, goresan cat, warna pudar, karat pada bodi.",
    "Pendingin & AC" => "AC tidak dingin, freon habis, kipas radiator mati, thermostat rusak.",
    "Transmisi" => "Perpindahan gigi keras, oli transmisi bocor, suara saat ganti gigi.",
    "Kelistrikan" => "Aki soak, lampu tidak menyala, starter tidak berfungsi, sistem sensor error.",
    "Interior & Detailing" => "Kursi kotor, dashboard kusam, karpet bau, butuh pembersihan interior.",
    "Diesel & Injeksi" => "Asap hitam, sulit starter, injektor kotor, konsumsi bahan bakar tinggi."
];

// =======================
// Mapping mekanik ke bidang spesialis dan keluhan
// =======================
$mekanikBidang = [
    "Pak Slamet" => "Sistem Rem & Suspensi",
    "Mas Surya" => "Mesin & Tune Up",
    "Budi Sentosa" => "Body & Cat",
    "Agus Hendro" => "Mesin & Tune Up",
    "Rino Sanjaya" => "Pendingin & AC",
    "Pak Min" => "Sistem Rem & Suspensi",
    "Ronny Nopirman" => "Transmisi",
    "Pak Hartanto" => "Kelistrikan",
    "Emanuele Rico" => "Interior & Detailing",
    "Vallentino David" => "Diesel & Injeksi"
];

// Pastikan bidang diambil dari mapping jika belum ada
if ($bidangSpesialis === "Bidang Tidak Diketahui" && isset($mekanikBidang[$namaMekanik])) {
    $bidangSpesialis = $mekanikBidang[$namaMekanik];
}

$keluhanMekanik = $daftarKeluhan[$bidangSpesialis] ?? "Siap membantu segala jenis keluhan kendaraan Anda.";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>AUTO CARE - Chat <?= htmlspecialchars($namaMekanik) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  html,body { width:100%; height:100%; font-family:"Poppins",sans-serif; background:#f2f2f2; }
  .chat-wrapper { width:100%; min-height:100vh; background:#fff; display:flex; flex-direction:column; }
  .top-bar { flex-shrink:0; display:flex; justify-content:space-between; align-items:center; padding:14px 20px; background:#fff; border-bottom:1px solid #ccc; }
  .top-bar h1 { font-size:22px; font-weight:700; color:#000; }
  .profile { display:flex; align-items:center; gap:8px; }
  .profile span { cursor:pointer; font-weight:500; }
  .chat-area { flex:1; display:flex; flex-direction:column; padding:15px; background:#fdfdfd; }
  .chat-header { border:1.5px solid #000; border-radius:8px; padding:10px 15px; display:flex; flex-direction:column; background:#fff; position:relative; }
  .back-btn { position:absolute; left:10px; background:none; border:none; font-size:22px; cursor:pointer; color:#333; }
  .mechanic-header { display:flex; align-items:center; margin-left:40px; }
  .mechanic-avatar { width:45px; height:45px; border-radius:50%; background:#c33; display:flex; align-items:center; justify-content:center; color:white; font-size:22px; margin-right:10px; }
  .mechanic-details h3 { font-size:16px; font-weight:600; margin-bottom:2px; }
  .mechanic-details p { font-size:13px; color:#3a3a3a; margin:0; }
  .chat-box { flex:1; margin-top:12px; background:#fff; border:1.5px solid #000; border-radius:8px; overflow-y:auto; padding:10px; }
  .chat-message { margin:8px 0; display:flex; }
  .chat-message.user { justify-content:flex-end; }
  .chat-message p, .chat-message img, .chat-message a { padding:10px 14px; border-radius:16px; max-width:70%; font-size:14px; line-height:1.4; word-wrap:break-word; }
  .chat-message.user p { background:#c33; color:#fff; border-bottom-right-radius:0; }
  .chat-message.mekanik p { background:#e0e0e0; color:#000; border-bottom-left-radius:0; }
  .input-area { border:1.5px solid #000; border-radius:8px; margin-top:10px; display:flex; align-items:center; background:#f2f2f2; padding:8px 10px; }
  .input-area input { flex:1; border:none; outline:none; background:transparent; font-size:14px; padding:6px; color:#333; }
  .input-icons { display:flex; align-items:center; gap:10px; }
  .input-icons button { background:none; border:none; cursor:pointer; font-size:18px; color:#333; }
  .send-btn { font-size:20px; margin-left:3px; cursor:pointer; }
</style>
</head>
<body>
  <div class="chat-wrapper">
    <div class="top-bar">
      <h1>AUTO CARE</h1>
      <div class="profile">
        <span onclick="window.location.href='beranda.php'">Beranda</span>
        <div class="mechanic-avatar">👤</div>
      </div>
    </div>

    <div class="chat-area">
      <div class="chat-header">
        <button class="back-btn" onclick="history.back()">←</button>
        <div class="mechanic-header">
          <div class="mechanic-avatar"><i class="bi bi-person-gear"></i></div>
          <div class="mechanic-details">
            <h3><?= htmlspecialchars($namaMekanik) ?></h3>
            <p><strong>Spesialis:</strong> <?= htmlspecialchars($bidangSpesialis) ?></p>
            <p><?= $status ?></p>
          </div>
        </div>
      </div>

      <div class="chat-box" id="chatBox"></div>

      <div class="input-area">
        <input type="text" id="messageInput" placeholder="Ketik pesan...">
        <div class="input-icons">
          <input type="file" id="fileInput" accept="*/*" hidden>
          <input type="file" id="imageInput" accept="image/*" hidden>
          <button id="linkBtn">🔗</button>
          <button id="cameraBtn">📷</button>
          <button id="sendBtn">▶</button>
        </div>
      </div>
    </div>
  </div>

<script>
  const chatBox = document.getElementById("chatBox");
  const sendBtn = document.getElementById("sendBtn");
  const messageInput = document.getElementById("messageInput");
  const fileInput = document.getElementById("fileInput");
  const imageInput = document.getElementById("imageInput");
  const linkBtn = document.getElementById("linkBtn");
  const cameraBtn = document.getElementById("cameraBtn");

  // --- Pesan awal otomatis sesuai bidang mekanik ---
  window.addEventListener("DOMContentLoaded", () => {
    tambahPesan(
      `<p><strong><?= addslashes($namaMekanik) ?> (<?= addslashes($bidangSpesialis) ?>):</strong><br>
      Halo, saya siap membantu Anda. Berikut beberapa keluhan umum di bidang saya:<br>
      <em><?= addslashes($keluhanMekanik) ?></em></p>`,
      "mekanik"
    );
  });

  function tambahPesan(html, kelas = "user") {
    const msg = document.createElement("div");
    msg.classList.add("chat-message", kelas);
    msg.innerHTML = html;
    chatBox.appendChild(msg);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function kirimPesan() {
    const text = messageInput.value.trim();
    if (text === "") return;
    tambahPesan(`<p>${escapeHtml(text)}</p>`);
    messageInput.value = "";

    setTimeout(() => {
      tambahPesan(`<p><strong><?= addslashes($namaMekanik) ?>:</strong> Pesan Anda sudah saya terima: "${escapeHtml(text)}"</p>`, "mekanik");
    }, 700);
  }

  sendBtn.addEventListener("click", kirimPesan);
  messageInput.addEventListener("keydown", e => {
    if (e.key === "Enter") {
      e.preventDefault();
      kirimPesan();
    }
  });

  linkBtn.addEventListener("click", () => fileInput.click());
  fileInput.addEventListener("change", e => {
    const file = e.target.files[0];
    if (file) tambahPesan(`<a href="#" download="${file.name}">📎 ${escapeHtml(file.name)}</a>`);
  });

  cameraBtn.addEventListener("click", () => imageInput.click());
  imageInput.addEventListener("change", e => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = ev => tambahPesan(`<img src="${ev.target.result}" alt="foto">`);
      reader.readAsDataURL(file);
    }
  });

  function escapeHtml(unsafe) {
    return unsafe.replace(/[&<>"']/g, m => ({
      "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
    }[m]));
  }
</script>
</body>
</html>
