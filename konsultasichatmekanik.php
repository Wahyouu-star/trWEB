<?php
// konsultasichatmekanik.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "inc/koneksi.php"; // Panggil koneksi DB

// Pastikan user sudah login (tidak disarankan untuk Guest)
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] == 0) {
    // Jika Guest, diarahkan ke login, tetapi untuk sementara biarkan Guest chat (ID 0)
    $user_id = 0; 
    $username = 'guest';
} else {
    $user_id = $_SESSION['user']['id'];
    $username = $_SESSION['user']['username'];
}


// =======================
// SETUP CHAT & SESSION
// =======================
$namaMekanik = htmlspecialchars($_GET['mekanik'] ?? $_SESSION['mekanik_terpilih'] ?? "Mekanik Tidak Diketahui");
$bidangSpesialis = htmlspecialchars($_GET['bidang'] ?? $_SESSION['bidang_spesialis'] ?? "Bidang Tidak Diketahui");

$_SESSION['mekanik_terpilih'] = $namaMekanik;
$_SESSION['bidang_spesialis'] = $bidangSpesialis;

$status = "Online";

// Kunci session chat unik per user dan mekanik
$chatKey = 'chat_history_' . preg_replace('/[^a-zA-Z0-9]/', '', $username . $namaMekanik);

// =======================
// INI PENTING: LOGGING SESI KE DATABASE (Hanya saat halaman dimuat pertama kali via GET)
// =======================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['mekanik'])) {
    if ($user_id > 0) { // Hanya log jika user terdaftar
        // Hapus sesi lama dan buat sesi baru
        if (isset($_SESSION[$chatKey])) {
             unset($_SESSION[$chatKey]);
        }
        
        // Cek apakah log sudah ada di DB
        $sql_check = "SELECT id FROM consultation_log WHERE user_id=$user_id AND chat_key='$chatKey'";
        $res_check = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($res_check) == 0) {
            // Insert log sesi baru
            $sql_log = "INSERT INTO consultation_log (user_id, mekanik_name, mekanik_bidang, chat_key, last_message_time, status)
                        VALUES ($user_id, '$namaMekanik', '$bidangSpesialis', '$chatKey', NOW(), 'Active')";
            mysqli_query($conn, $sql_log);
        } else {
            // Update waktu sesi jika sudah ada
            $sql_update = "UPDATE consultation_log SET last_message_time=NOW(), status='Active' WHERE user_id=$user_id AND chat_key='$chatKey'";
            mysqli_query($conn, $sql_update);
        }
    }
}


// =======================
// Logika Chat History (Sama seperti sebelumnya)
// =======================
if (!isset($_SESSION[$chatKey])) {
    $welcomeMessage = "Halo! Saya siap membantu konsultasi Anda terkait **{$bidangSpesialis}**. Apa keluhan utama Anda? (Contoh: rem saya blong, mesin saya bunyi aneh)";
    $_SESSION[$chatKey] = [
        ['sender' => 'mekanik', 'message' => $welcomeMessage, 'time' => time()]
    ];
}

$spesialisasi = [
    "Mesin & Tune Up" => [
        "valid_keywords" => ["oli", "mesin", "tune up", "bensin", "panas", "service", "ganti", "bunyi"],
        "auto_diag" => "Berdasarkan keluhan mesin, **kemungkinan Mesin Anda membutuhkan Tune Up, Ganti Oli, atau ada masalah di sistem pengapian**. Apakah ada suara kasar saat idle, atau boros bensin?",
        "invalid_resp" => "Maaf, saya fokus pada Mesin & Tune Up. Untuk Rem atau AC, silakan kembali ke Daftar Mekanik dan pilih spesialis yang tepat.",
        "closing_topic" => "Servis Mesin"
    ],
    "Kelistrikan & AC" => [
        "valid_keywords" => ["aki", "starter", "kelistrikan", "lampu", "ac", "dingin", "freon", "diagnostik", "ganti"],
        "auto_diag" => "Keluhan Kelistrikan/AC. **Kemungkinan besar masalah ada di Kompresor AC, Freon, atau Tegangan Aki**. Apa gejala spesifiknya? (Contoh: AC tidak dingin sama sekali, atau sulit distarter).",
        "invalid_resp" => "Maaf, saya fokus pada Kelistrikan & AC. Mohon tanyakan masalah Bodi atau Kaki-kaki ke spesialis lain.",
        "closing_topic" => "Perbaikan Kelistrikan/AC"
    ],
    "Suspensi & Rem" => [
        "valid_keywords" => ["rem", "shock", "suspensi", "kaki-kaki", "decit", "kampas", "ganti", "blong"],
        "auto_diag" => "Keluhan Kaki-kaki/Rem. **Kemungkinan kampas rem sudah tipis, Shockbreaker bocor, atau minyak rem kurang**. Gejala spesifik apa yang terdengar? (Contoh: suara 'guduk-guduk' saat melewati polisi tidur, atau rem berdecit).",
        "invalid_resp" => "Saya spesialis Suspensi & Rem. Untuk keluhan Mesin atau Tune Up, silakan cari mekanik yang sesuai.",
        "closing_topic" => "Perbaikan Rem/Suspensi"
    ],
    "Body Repair & Detailing" => [
        "valid_keywords" => ["body", "cat", "penyok", "karat", "goresan", "detailing", "interior", "bersih"],
        "auto_diag" => "Keluhan Bodi atau Detailing. **Kami menduga ada kerusakan pada lapisan cat atau body frame**. Apakah penyok parah atau hanya goresan ringan? Anda bisa mengirim foto area yang rusak.",
        "invalid_resp" => "Saya spesialis Body Repair & Detailing. Kami tidak menangani masalah Mesin atau Kelistrikan.",
        "closing_topic" => "Perbaikan Bodi & Detailing"
    ],
    "Bidang Tidak Diketahui" => [
        "valid_keywords" => [],
        "auto_diag" => "Mohon berikan detail keluhan Anda agar saya dapat memberikan solusi terbaik.",
        "invalid_resp" => "Mohon berikan detail keluhan Anda agar saya dapat memberikan solusi terbaik.",
        "closing_topic" => "Konsultasi Umum"
    ]
];

$mekanikData = $spesialisasi[$bidangSpesialis] ?? $spesialisasi["Bidang Tidak Diketahui"];

// =======================
// Logic untuk memproses pesan baru dari AJAX (Hanya dijalankan jika ada POST)
// =======================
if (isset($_POST['message'])) {
    $userMsg = trim(strtolower($_POST['message']));
    $botResponse = $mekanikData['invalid_resp']; 
    $isClosingTopic = false;
    
    $chatHistory = $_SESSION[$chatKey] ?? [];
    $messageCount = count($chatHistory); 

    // 1. Cek Kata Kunci Valid Sesuai Spesialisasi
    $foundKeyword = false;
    foreach ($mekanikData['valid_keywords'] as $keyword) {
        if (stripos($userMsg, $keyword) !== false) {
            $foundKeyword = true;
            break;
        }
    }
    
    // 2. Logic Diagnosis Multi-Step dan Closing
    $lastBotMessage = end($chatHistory)['message'] ?? ''; 

    if ($messageCount === 1 && $foundKeyword) {
        // --- STEP 2: RESPON PERTAMA USER (DIAGNOSIS Awal) ---
        $botResponse = $mekanikData['auto_diag'];

    } elseif ($messageCount >= 2 && $messageCount <= 4 && (stripos($userMsg, 'iya') !== false || stripos($userMsg, 'berdecit') !== false || stripos($userMsg, 'bocor') !== false || stripos($userMsg, 'km') !== false || preg_match('/\d+/', $userMsg))) {
        // --- STEP 4: RESPON CLOSING OTOMATIS (User mengonfirmasi/memberi detail setelah diagnosis) ---
        $botResponse = "Baik, kami sudah mencatat detail keluhan Anda, dan **diagnosis awal terkonfirmasi**. Masalah ini perlu penanganan langsung di bengkel.";
        $isClosingTopic = true;
        
    } elseif ($messageCount > 4 || stripos($userMsg, 'lanjut') !== false || stripos($userMsg, 'booking') !== false || stripos($userMsg, 'ok') !== false) {
        // --- RESPON CLOSING OTOMATIS (User meminta Booking setelah diskusi panjang) ---
         $isClosingTopic = true;
         $botResponse = "Sempurna! Berdasarkan keluhan Anda, kami sarankan **melanjutkan ke proses Booking** agar kendaraan bisa segera dikerjakan. Silakan **[KLIK DI SINI UNTUK BOOKING]**.";
    
    } else {
        // --- STEP 3: RESPON STANDAR (Bot meminta lebih banyak detail) ---
        $botResponse = "Mohon berikan detail keluhan Anda (misal: kapan terjadi, suara seperti apa, atau berapa jarak tempuh terakhir) agar saya dapat memberikan diagnosis yang akurat.";
    }

    if ($isClosingTopic) {
        // Final Response Closing yang memuaskan
        $bookingLink = "BUATJANJILANGKAH2.PHP"; 
        $botResponse = str_replace(
            "[KLIK DI SINI UNTUK BOOKING]", 
            "<a href='{$bookingLink}' style='color:#c33; font-weight:bold;'>KLIK DI SINI UNTUK BOOKING</a>", 
            $botResponse
        );
    } 
    
    // Simpan pesan user dan bot ke history
    $_SESSION[$chatKey][] = ['sender' => 'user', 'message' => $_POST['message'], 'time' => time()];
    $_SESSION[$chatKey][] = ['sender' => 'mekanik', 'message' => $botResponse, 'time' => time()];

    echo json_encode([
        'status' => 'success',
        'bot_response' => $botResponse,
        'chat_history' => $_SESSION[$chatKey]
    ]);
    exit;
}

// Logic untuk mendapatkan history chat (digunakan Admin dan User)
$chatHistory = $_SESSION[$chatKey];
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
  .input-area { flex-shrink: 0; border:1.5px solid #000; border-radius:8px; margin-top:10px; display:flex; align-items:center; background:#f2f2f2; padding:8px 10px; }
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
        <div class="mechanic-avatar"><i class="bi bi-person-fill"></i></div>
      </div>
    </div>

    <div class="chat-area">
      <div class="chat-header">
        <button class="back-btn" onclick="history.back()">&#8592;</button>
        <div class="mechanic-header">
          <div class="mechanic-avatar"><i class="bi bi-person-gear"></i></div>
          <div class="mechanic-details">
            <h3><?= htmlspecialchars($namaMekanik) ?></h3>
            <p><strong>Spesialis:</strong> <?= htmlspecialchars($bidangSpesialis) ?></p>
            <p><?= $status ?></p>
          </div>
        </div>
      </div>

      <div class="chat-box" id="chatBox">
         </div>

      <div class="input-area">
        <input type="text" id="messageInput" placeholder="Ketik pesan...">
        <div class="input-icons">
          <button id="sendBtn">&#x27A4;</button>
        </div>
      </div>
    </div>
  </div>

<script>
  const chatBox = document.getElementById("chatBox");
  const sendBtn = document.getElementById("sendBtn");
  const messageInput = document.getElementById("messageInput");
  const namaMekanik = "<?= addslashes($namaMekanik) ?>";
  const bidangSpesialis = "<?= addslashes($bidangSpesialis) ?>";
  
  let chatHistory = <?= json_encode($chatHistory); ?>;

  function tambahPesan(html, kelas = "user") {
    const msg = document.createElement("div");
    msg.classList.add("chat-message", kelas);
    msg.innerHTML = html;
    chatBox.appendChild(msg);
    chatBox.scrollTop = chatBox.scrollHeight;
  }
  
  function muatHistory() {
    // Muat pesan dari history session
    chatHistory.forEach(item => {
        const senderClass = item.sender === 'user' ? 'user' : 'mekanik';
        tambahPesan(`<p>${item.message}</p>`, senderClass);
    });
  }

  function kirimPesan() {
    const text = messageInput.value.trim();
    if (text === "") return;
    
    // 1. Tampilkan pesan user (di-escape untuk keamanan)
    tambahPesan(`<p>${escapeHtml(text)}</p>`, 'user');
    
    // 2. Kirim ke server (untuk mendapatkan respons bot dan menyimpan history)
    const formData = new FormData();
    formData.append('message', text);

    messageInput.value = "";
    messageInput.focus();

    fetch('konsultasichatmekanik.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      // 3. Tampilkan respons bot setelah jeda
      setTimeout(() => {
        // Pesan bot tidak di-escape di sini karena mungkin mengandung link HTML untuk booking
        tambahPesan(`<p><strong>${namaMekanik}:</strong> ${data.bot_response}</p>`, "mekanik");
      }, 700);
      chatHistory = data.chat_history; // Update history lokal
    })
    .catch(error => {
      console.error('Error:', error);
      tambahPesan(`<p><strong>Sistem:</strong> Maaf, terjadi kesalahan koneksi.</p>`, "mekanik");
    });
  }

  sendBtn.addEventListener("click", kirimPesan);
  messageInput.addEventListener("keydown", e => {
    if (e.key === "Enter") {
      e.preventDefault();
      kirimPesan();
    }
  });
  
  window.addEventListener("DOMContentLoaded", muatHistory);

  function escapeHtml(unsafe) {
    // Digunakan untuk membersihkan input user sebelum ditampilkan (mencegah XSS)
    return unsafe.replace(/[&<>"']/g, m => ({
      "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
    }[m]));
  }
</script>
</body>
</html>