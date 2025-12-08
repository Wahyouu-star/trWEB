<?php
session_start();
// Panggil koneksi database
include "inc/koneksi.php"; 

$showSignupSuccess = isset($_GET['signup']) && $_GET['signup'] === 'success';

// PROSES LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  // =========================
  // LOGIN SEBAGAI GUEST
  // =========================
  if (isset($_POST['login_guest'])) {
    $_SESSION['user'] = [
      'id' => 0, 
      'nama' => 'Guest',
      'username' => 'guest'
    ];
    header('Location: beranda.php'); 
    exit;
  }

  // =========================
  // LOGIN USER/ADMIN DARI FORM UTAMA
  // =========================
  if (isset($_POST['login_admin'])) { 
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 1. Cek Login Admin Statis
    if ($username === 'admin' && $password === '12345') {
        $_SESSION['user'] = [
            'id' => -1, // Unique ID for Admin
            'nama' => 'Administrator',
            'username' => 'admin'
        ];
        header('Location: beranda.php'); 
        exit;
    }

    // 2. Cek Login User dari Database
    $db_username = mysqli_real_escape_string($conn, $username);
    
    $sql = "SELECT id, nama, username, password FROM users WHERE username = '$db_username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Login berhasil
        $_SESSION['user'] = [
            'id'       => $user['id'], 
            'nama'     => $user['nama'],
            'username' => $user['username']
        ];
        header('Location: beranda.php');
        exit;
    } else {
        echo "<script>alert('Username atau password salah!');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - AUTO CARE</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

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
      background-image: url("IMG/LOGO.png"); 
      background-size: 70%;
      background-repeat: no-repeat;
      background-position: center;
      position: absolute;
      inset: 0;
      z-index: 0;
      opacity: 0.15;
    }

    .login-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 400px;
      padding: 20px;
    }

    .login-box {
      background: rgba(255, 255, 255, 0.9);
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.2);
      text-align: center;
      backdrop-filter: blur(5px);
    }

    h5 {
      color: #942626;
      font-weight: bold;
      margin-bottom: 30px;
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
      margin-top: 10px; 
    }

    .small-links {
      font-size: 12px;
    }

    .small-links a {
      text-decoration: none;
      color: #5a5a5a;
    }

    .btn-login {
      border-radius: 10px;
      background-color: #c24a4a;
      color: white;
      border: none;
      padding: 6px 20px;
      font-weight: 500;
      transition: all 0.3s ease-in-out;
    }

    .btn-login:hover {
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

    .modal-content {
      border-radius: 15px;
      padding: 20px;
    }

    .btn-primary {
      background-color: #c24a4a;
      border: none;
    }

    .btn-primary:hover {
      background-color: #a83838;
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
      display: flex; /* Tambahkan ini agar pesan bisa diatur align-self */
      flex-direction: column; /* Tambahkan ini agar pesan berjejer ke bawah */
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
      align-self: flex-end; /* Tambahkan ini agar pesan user di kanan */
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

<div class="login-container">
  <div class="login-box">
    <h5>SELAMAT DATANG<br>DI AUTO CARE</h5>

    <form method="POST">
      <input type="hidden" name="login_admin" value="1"> 
      
      <div class="form-group">
        <label for="usernameInput">Username</label>
        <i class="bi bi-person-fill form-icon"></i>
        <input type="text" id="usernameInput" name="username" class="form-control" placeholder="Masukkan username" required />
      </div>

      <div class="form-group">
        <label for="passwordInput">Password</label>
        <i class="bi bi-lock-fill form-icon"></i>
        <input type="password" id="passwordInput" name="password" class="form-control" placeholder="Masukkan password" required />
      </div>

      <div class="text-start small small-links mb-3">
        <a href="#">Lupa password? <span class="text-danger">klik disini</span></a><br />
        <a href="signup.php">Belum punya akun? <span class="text-danger">daftar disini</span></a>
      </div>

      <button type="submit" class="btn btn-login d-flex align-items-center mx-auto">
        <i class="bi bi-box-arrow-in-right me-1"></i> Log In
      </button>
    </form>

    <form method="POST" class="mt-2">
      <input type="hidden" name="login_guest">
      <button type="submit" class="btn btn-guest d-flex align-items-center mx-auto">
        <i class="bi bi-person-circle me-1"></i> Login sebagai Guest
      </button>
    </form>
  </div>
</div>

<div class="modal fade" id="successModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <i class="bi bi-patch-check-fill" style="font-size:48px;color:#c24a4a;"></i>
      <h5 class="mt-2">Berhasil</h5>
      <p id="successText">Login berhasil! Selamat datang.</p>
      <button type="button" id="okButton" class="btn btn-primary mt-2" data-bs-dismiss="modal">Ok</button>
    </div>
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

<?php if ($showSignupSuccess): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const modal = new bootstrap.Modal(document.getElementById('successModal'));
  document.getElementById('successText').innerText = 'Akun Anda telah dibuat. Silakan login.';
  modal.show();
});
</script>
<?php endif; ?>

</body>
</html>