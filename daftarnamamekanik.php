<?php
// dashboard.php
$mekanik = [
  "Pak Slamet", "Mas Surya", "Budi Sentosa", "Agus Hendro", "Rino Sanjaya",
  "Pak Min", "Ronny Nopirman", "Pak Hartanto", "Emanuele Rico", "Vallentino David"
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AUTO CARE - Dashboard Mekanik</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    * {
      box-sizing: border-box;
    }

    html, body {
      width: 100%;
      height: 100%;
      margin: 0;
      background-color: #f2f2f2;
      font-family: "Poppins", sans-serif;
    }

    /* Header atas */
    header {
      width: 100%;
      background: #fff;
      border-bottom: 1px solid #ccc;
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h5 {
      font-weight: 700;
      font-size: 22px;
      margin: 0;
    }

    header .menu {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 500;
    }

    header .menu i {
      font-size: 28px;
      color: #c33;
    }

    /* Search bar */
    .search-bar {
      display: flex;
      align-items: center;
      background: #fff;
      border-radius: 25px;
      padding: 8px 15px;
      width: 100%;
      max-width: 500px;
      margin: 25px auto;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .search-bar input {
      border: none;
      outline: none;
      flex: 1;
      font-size: 16px;
      padding-left: 10px;
    }

    .search-bar i {
      font-size: 18px;
      color: #555;
    }

    /* Container utama */
    .container-fluid {
      padding: 0 30px 50px 30px;
    }

    /* Kartu mekanik */
    .mekanik-card {
      border: 1px solid #ddd;
      border-radius: 12px;
      background: #fff;
      text-align: center;
      padding: 20px 10px;
      transition: transform 0.2s ease;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .mekanik-card:hover {
      transform: scale(1.03);
    }

    .mekanik-icon {
      font-size: 55px;
      color: #c33;
      margin-bottom: 10px;
    }

    .mekanik-card h6 {
      font-size: 15px;
      font-weight: 600;
      color: #000;
      margin-bottom: 10px;
    }

    .btn-chat {
      background-color: #c33;
      border: none;
      color: #fff;
      border-radius: 20px;
      padding: 6px 16px;
      font-size: 14px;
      transition: background 0.3s ease;
    }

    .btn-chat:hover {
      background-color: #a11;
    }

    /* Responsif */
    @media (max-width: 768px) {
      .mekanik-card {
        padding: 15px 8px;
      }
      header h5 {
        font-size: 18px;
      }
      .btn-chat {
        font-size: 12px;
        padding: 5px 12px;
      }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <h5>AUTO CARE</h5>
    <div class="menu">
      <a href="#" class="text-dark text-decoration-none">Beranda</a>
      <i class="bi bi-person-circle"></i>
    </div>
  </header>

  <!-- Search -->
  <div class="search-bar">
    <i class="bi bi-search"></i>
    <input type="text" id="searchInput" placeholder="Search mekanik...">
  </div>

  <!-- Grid Mekanik -->
  <div class="container-fluid">
    <div class="row g-4 justify-content-center" id="mekanikList">
      <?php foreach ($mekanik as $nama): ?>
      <div class="col-6 col-md-4 col-lg-2 mekanik-item">
        <div class="mekanik-card">
          <div class="mekanik-icon"><i class="bi bi-person-gear"></i></div>
          <h6><?php echo $nama; ?></h6>
          <a href="chat.php?mekanik=<?php echo urlencode($nama); ?>" class="btn btn-chat">Chat</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- JavaScript Search Function -->
  <script>
    const searchInput = document.getElementById('searchInput');
    const mekanikItems = document.querySelectorAll('.mekanik-item');

    searchInput.addEventListener('keyup', function() {
      const keyword = this.value.toLowerCase();

      mekanikItems.forEach(item => {
        const name = item.querySelector('h6').textContent.toLowerCase();
        item.style.display = name.includes(keyword) ? '' : 'none';
      });
    });
  </script>

</body>
</html>
