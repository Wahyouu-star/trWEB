<?php
session_start();

// PROSES LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // =========================
  // LOGIN SEBAGAI GUEST
  // =========================
  if (isset($_POST['login_guest'])) {
    $_SESSION['user'] = [
      'nama' => 'Guest',
      'username' => 'guest'
    ];

    echo "
      <script>
        window.onload = function() {
          const modal = new bootstrap.Modal(document.getElementById('successModal'));
          document.getElementById('successText').innerText = 'Anda masuk sebagai Guest.';
          modal.show();
          document.getElementById('okButton').onclick = function() {
            window.location.href = 'beranda.php';
          }
        }
      </script>
    ";
  }

  // =========================
  // LOGIN USER DARI SIGNUP (SESSION)
  // =========================
  if (isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (
      isset($_SESSION['akun']) &&
      $username === $_SESSION['akun']['username'] &&
      $password === $_SESSION['akun']['password']
    ) {
      $_SESSION['user'] = $_SESSION['akun'];

      echo "
        <script>
          window.onload = function() {
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            document.getElementById('successText').innerText = 'Login berhasil! Selamat datang.';
            modal.show();
            document.getElementById('okButton').onclick = function() {
              window.location.href = 'beranda.php';
            }
          }
        </script>
      ";
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
      background-image: url("img/logo.png");
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
  </style>
</head>
<body>

<div class="bg-overlay"></div>

<div class="login-container">
  <div class="login-box">
    <h5>SELAMAT DATANG<br>DI AUTO CARE</h5>

    <!-- FORM LOGIN USER -->
    <form method="POST">
      <div class="form-group">
        <i class="bi bi-person-fill form-icon"></i>
        <input type="text" name="username" class="form-control" placeholder="Username" required />
      </div>

      <div class="form-group">
        <i class="bi bi-lock-fill form-icon"></i>
        <input type="password" name="password" class="form-control" placeholder="Password" required />
      </div>

      <div class="text-start small small-links mb-3">
        <a href="#">Lupa password? <span class="text-danger">klik disini</span></a><br />
        <a href="signup.php">Belum punya akun? <span class="text-danger">daftar disini</span></a>
      </div>

      <button type="submit" name="login_user" class="btn btn-login d-flex align-items-center mx-auto">
        <i class="bi bi-box-arrow-in-right me-1"></i> Log In
      </button>
    </form>

    <!-- FORM LOGIN GUEST -->
    <form method="POST" class="mt-2">
      <input type="hidden" name="login_guest">
      <button type="submit" class="btn btn-guest d-flex align-items-center mx-auto">
        <i class="bi bi-person-circle me-1"></i> Login sebagai Guest
      </button>
    </form>
  </div>
</div>

<!-- MODAL SUKSES -->
<div class="modal fade" id="successModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <i class="bi bi-patch-check-fill" style="font-size:48px;color:#c24a4a;"></i>
      <h5 class="mt-2">Berhasil</h5>
      <p id="successText"></p>
      <button type="button" id="okButton" class="btn btn-primary mt-2">Ok</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
