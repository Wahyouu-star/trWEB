<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // ================================
  // LOGIN SEBAGAI GUEST
  // ================================
  if (isset($_POST['login_guest'])) {

    $_SESSION['user'] = [
      'nama' => 'Guest',
      'username' => 'guest'
    ];

    echo "
      <script>
        window.onload = function() {
          const modal = new bootstrap.Modal(document.getElementById('successModal'));
          document.getElementById('successText').innerText = 'Anda masuk sebagai Guest. Beberapa fitur mungkin terbatas.';
          modal.show();
          document.getElementById('okButton').addEventListener('click', function() {
            window.location.href = 'beranda.php';
          });
        }
      </script>
    ";

  } else {

    // ================================
    // SIGNUP BIASA (SESSION)
    // ================================
    $nama       = $_POST['nama'] ?? '';
    $username   = $_POST['username'] ?? '';
    $password   = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if ($password !== $konfirmasi) {
      echo "<script>alert('Password dan konfirmasi tidak sama!');</script>";
    } else {

      // Simpan user ke SESSION
      $_SESSION['users'][$username] = [
        'nama'     => $nama,
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT)
      ];

      echo "
        <script>
          window.onload = function() {
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            document.getElementById('successText').innerText = 'Akun Anda telah dibuat. Silakan login.';
            modal.show();
            document.getElementById('okButton').addEventListener('click', function() {
              window.location.href = 'login.php';
            });
          }
        </script>
      ";
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
  </style>
</head>
<body>

  <div class="bg-overlay"></div>

  <div class="signup-container">
    <div class="signup-box">
      <h5>DAFTAR AKUN<br>DI AUTO CARE</h5>

      <form method="POST">
        <div class="form-group">
          <i class="bi bi-person-fill form-icon"></i>
          <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
        </div>

        <div class="form-group">
          <i class="bi bi-at form-icon"></i>
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>

        <div class="form-group">
          <i class="bi bi-lock-fill form-icon"></i>
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <div class="form-group">
          <i class="bi bi-lock-fill form-icon"></i>
          <input type="password" name="konfirmasi" class="form-control" placeholder="Konfirmasi Password" required>
        </div>

        <button type="submit" class="btn btn-signup mx-auto">Daftar</button>
      </form>

      <form method="POST" class="mt-2">
        <input type="hidden" name="login_guest" value="1">
        <button type="submit" class="btn btn-guest mx-auto">Masuk sebagai Guest</button>
      </form>

    </div>
  </div>

  <!-- Modal sukses -->
  <div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <i class="bi bi-check-circle-fill" style="font-size: 48px; color: #c24a4a;"></i>
        <h5 class="mt-2">Berhasil</h5>
        <p id="successText"></p>
        <button type="button" id="okButton" class="btn btn-primary mt-2">Ok</button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
