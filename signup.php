<?php
// signup.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Panggil koneksi database
include "inc/koneksi.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // ================================
  // LOGIN SEBAGAI GUEST
  // ================================
  if (isset($_POST['login_guest'])) {

    $_SESSION['user'] = [
      'id' => 0, // ID 0 untuk Guest (Tidak terdaftar di database)
      'nama' => 'Guest',
      'username' => 'guest'
    ];
    header('Location: beranda.php');
    exit;

  } else {

    // ================================
    // SIGNUP BIASA (SIMPAN KE USERS TABLE)
    // ================================
    $nama       = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
    $username   = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $password   = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if ($password !== $konfirmasi) {
      echo "<script>alert('Password dan konfirmasi tidak sama!');</script>";
    } else {
      // 🚨 HASHING PASSWORD untuk keamanan
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Cek apakah username sudah ada
      $check_sql = "SELECT id FROM users WHERE username = '$username'";
      $check_result = mysqli_query($conn, $check_sql);

      if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Username sudah terdaftar. Silakan gunakan username lain.');</script>";
      } else {
        // Insert data ke tabel users
        $insert_sql = "INSERT INTO users (nama, username, password, created_at) 
                       VALUES ('$nama', '$username', '$hashed_password', NOW())";
        
        if (mysqli_query($conn, $insert_sql)) {
          // Redirect ke login dengan status sukses
          header('Location: login.php?signup=success');
          exit;
        } else {
          echo "<script>alert('Pendaftaran gagal: " . mysqli_error($conn) . "');</script>";
        }
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup - AUTO CARE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #ffffff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      position: relative;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .bg-overlay {
      background-image: url("img/logo.png");
      background-size: 70%;
      background-repeat: no-repeat;
      background-position: center;
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      z-index: 0;
      opacity: 0.15;
    }

    .signup-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 400px;
      padding: 20px;
    }

    .signup-box {
      background: rgba(255, 255, 255, 0.8); 
      padding: 25px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      backdrop-filter: blur(5px);
    }

    h5 {
      color: #942626;
      font-weight: bold;
      margin-bottom: 25px;
    }

    .form-group {
      position: relative;
      margin-bottom: 15px;
    }
    
    .form-group label {
      text-align: left;
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
      color: #333;
    }
    /* Menggeser ikon ke bawah agar di tengah input saat ada label */
    .form-group .form-icon {
        position: absolute;
        left: 15px;
        top: 60%; 
        transform: translateY(-50%);
        color: #a33e3e;
    }


    .form-control {
      border-radius: 10px;
      padding-left: 40px;
      border: none;
      height: 45px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .form-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #a33e3e;
    }

    .btn-signup {
      border-radius: 10px;
      background-color: #c24a4a;
      color: white;
      border: none;
      padding: 6px 20px;
      font-weight: 500;
      transition: all 0.3s ease-in-out;
    }

    .btn-signup:hover {
      background-color: #a83838;
      transform: scale(1.05);
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .btn-guest {
      border-radius: 10px;
      background-color: #ffffff;
      color: #c24a4a;
      border: 1px solid #c24a4a;
      padding: 6px 20px;
      font-weight: 500;
      transition: all 0.3s ease-in-out;
      margin-top: 10px;
    }

    .btn-guest:hover {
      background-color: #f7e4e4;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    /* CSS Chat CS Floating */
    .chat-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #c24a4a;
      color: #fff;
      border-radius: 50%;
      width: 55px;
      height: 55px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 3px 10px rgba(0,0,0,0.3);
      cursor: pointer;
      z-index: 10;
    }

    .chat-box {
      position: fixed;
      bottom: 85px;
      right: 20px;
      width: 280px;
      max-height: 380px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.25);
      display: none;
      flex-direction: column;
      overflow: hidden;
      z-index: 10;
    }

    .chat-header {
      background-color: #c24a4a;
      color: #fff;
      padding: 10px 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .chat-header span {
      font-weight: 600;
      font-size: 0.9rem;
    }

    .chat-body {
      padding: 10px;
      flex: 1;
      overflow-y: auto;
      font-size: 0.8rem;
      background-color: #f8f8f8;
      display: flex; 
      flex-direction: column; 
    }

    .chat-message {
      margin-bottom: 8px;
      max-width: 90%;
      padding: 6px 8px;
      border-radius: 8px;
    }

    .chat-message.cs {
      background-color: #ffffff;
      align-self: flex-start;
      border: 1px solid #e0e0e0;
    }

    .chat-message.user {
      background-color: #c24a4a;
      color: #fff;
      margin-left: auto;
      align-self: flex-end; 
    }

    .chat-footer {
      padding: 8px;
      background: #ffffff;
      border-top: 1px solid #eee;
      display: flex;
      gap: 5px;
    }

    .chat-footer input {
      flex: 1;
      border-radius: 20px;
      border: 1px solid #ddd;
      padding: 5px 12px;
      font-size: 0.8rem;
    }

    .chat-footer button {
      border-radius: 20px;
      border: none;
      background-color: #c24a4a;
      color: #fff;
      padding: 5px 10px;
      font-size: 0.8rem;
    }
  </style>
</head>
<body>

  <div class="bg-overlay"></div>

  <div class="signup-container">
    <div class="signup-box">
      <h5>DAFTAR AKUN<br>DI AUTO CARE</h5>

      <form method="POST">
        
        <div class="form-group">
          <label for="namaInput">Nama Lengkap</label>
          <i class="bi bi-person-fill form-icon" style="margin-top: 10px;"></i>
          <input type="text" id="namaInput" name="nama" class="form-control" placeholder="Nama Lengkap" required>
        </div>

        <div class="form-group">
          <label for="usernameInput">Username</label>
          <i class="bi bi-at form-icon" style="margin-top: 10px;"></i>
          <input type="text" id="usernameInput" name="username" class="form-control" placeholder="Username" required>
        </div>

        <div class="form-group">
          <label for="passwordInput">Password</label>
          <i class="bi bi-lock-fill form-icon" style="margin-top: 10px;"></i>
          <input type="password" id="passwordInput" name="password" class="form-control" placeholder="Password" required>
        </div>

        <div class="form-group">
          <label for="konfirmasiInput">Konfirmasi Password</label>
          <i class="bi bi-lock-fill form-icon" style="margin-top: 10px;"></i>
          <input type="password" id="konfirmasiInput" name="konfirmasi" class="form-control" placeholder="Konfirmasi Password" required>
        </div>

        <button type="submit" class="btn btn-signup mx-auto">Daftar</button>
      </form>
      
      <div class="text-center small small-links mt-2 mb-2">
        Sudah punya akun? <a href="login.php" class="text-danger">Log In disini</a>
      </div>
      <form method="POST" class="mt-2">
        <input type="hidden" name="login_guest" value="1">
        <button type="submit" class="btn btn-guest mx-auto">Masuk sebagai Guest</button>
      </form>

    </div>
  </div>

  <div class="chat-button" id="chatToggle" title="Chat CS">
    <i class="bi bi-chat-dots-fill" style="font-size: 1.3rem;"></i>
  </div>

  <div class="chat-box" id="chatBox">
    <div class="chat-header">
      <span><i class="bi bi-headset me-1"></i> CS Auto Care</span>
      <button type="button" class="btn btn-sm btn-light py-0 px-2" id="chatClose">
        <i class="bi bi-x-lg" style="font-size: 0.8rem;"></i>
      </button>
    </div>
    <div class="chat-body" id="chatMessages">
      <div class="chat-message cs">
        Halo! Ada yang bisa kami bantu?
      </div>
    </div>
    <div class="chat-footer">
      <input type="text" id="chatInput" placeholder="Tulis pesan..." />
      <button type="button" id="chatSend"><i class="bi bi-send"></i></button>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle Chat Box
    const chatToggle = document.getElementById('chatToggle');
    const chatBox = document.getElementById('chatBox');
    const chatClose = document.getElementById('chatClose');
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const chatSend = document.getElementById('chatSend');

    chatToggle.addEventListener('click', () => {
      chatBox.style.display = chatBox.style.display === 'flex' ? 'none' : 'flex';
    });

    chatClose.addEventListener('click', () => {
      chatBox.style.display = 'none';
    });

    function addMessage(text, sender) {
      const div = document.createElement('div');
      div.classList.add('chat-message', sender);
      div.textContent = text;
      chatMessages.appendChild(div);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function sendUserMessage() {
      const text = chatInput.value.trim();
      if (!text) return;
      addMessage(text, 'user');
      chatInput.value = '';

      setTimeout(() => {
        addMessage('Terima kasih, pesan Anda sudah kami terima. CS akan segera membalas.', 'cs');
      }, 800);
    }

    chatSend.addEventListener('click', sendUserMessage);
    chatInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        sendUserMessage();
      }
    });
  </script>

</body>
</html>