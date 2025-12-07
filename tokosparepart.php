<?php
session_start();
include "products.php";

$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
  $cartCount = array_sum($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Auto Care - Toko Sparepart</title>

  <!-- BOOTSTRAP & ICON -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: Poppins, Arial, sans-serif;
      background: #f6f7fb;
      overflow-x: hidden;
    }

    .page-shop {
      min-height: 100vh;
      background: #f6f7fb;
    }

    .page-shop .shop-wrapper {
      max-width: 1100px;
      margin: 120px auto 60px auto;
      padding: 0 15px;
    }

    .shop-top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 18px;
    }

    .shop-search-box {
      background: #fff;
      border-radius: 999px;
      padding: 6px 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    }

    .shop-search-box input {
      border: none;
      outline: none;
      font-size: 0.9rem;
      width: 200px;
      background: transparent;
    }

    .shop-cart-icon {
      position: relative;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #fff;
      padding: 8px 12px;
      border-radius: 999px;
      text-decoration: none;
      box-shadow: 0 3px 8px rgba(0,0,0,0.12);
      color: #333;
      gap: 6px;
    }

    .shop-cart-icon img {
      width: 24px;
      height: 24px;
    }

    .shop-cart-notif {
      position: absolute;
      top: -6px;
      right: -6px;
      background: #b22929;
      color: #fff;
      border-radius: 999px;
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
    }

    .shop-title {
      font-weight: 700;
      color: #333;
      margin-bottom: 18px;
    }

    .shop-product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 20px;
    }

    .shop-product-card {
      background: #fff;
      border-radius: 16px;
      padding: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .shop-product-card .img {
      width: 100%;
      height: 130px;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 10px;
    }

    .shop-product-card img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .shop-product-card h4 {
      font-size: 0.95rem;
      min-height: 40px;
    }

    .shop-product-card p {
      font-weight: 600;
      color: #b22929;
      margin-bottom: 8px;
    }

    .shop-btn-add {
      margin-top: 6px;
      width: 100%;
      border: none;
      border-radius: 999px;
      padding: 7px 0;
      background: #b22929;
      color: #fff;
      cursor: pointer;
    }

    .shop-btn-add:hover {
      background: #941f1f;
    }

    @media (max-width:576px) {
      .shop-wrapper { margin-top: 100px; }
      .shop-top-bar { flex-direction: column; align-items: flex-start; gap: 10px; }
    }

    .footer, .footer-container {
      display: none !important;
    }
  </style>
</head>

<body>

<?php include "inc/header.php"; ?>

<div class="page-shop">
  <div class="shop-wrapper">

    <div class="shop-top-bar">
      <div class="shop-search-box">
        🔍 <input type="text" id="searchInput" placeholder="Cari produk...">
      </div>

      <a href="pembayaran.php" class="shop-cart-icon cart-icon">
        <img src="IMG/cart.jpg">
        <span class="shop-cart-notif" id="cartNotif" style="<?= ($cartCount > 0 ? '' : 'display:none') ?>">
          <?= $cartCount ?>
        </span>
      </a>
    </div>

    <h2 class="shop-title">Produk</h2>

    <div class="shop-product-grid" id="productGrid">
      <?php foreach ($products as $p): ?>
        <div class="shop-product-card">
          <div class="img">
            <img src="<?= $p['image'] ?>">
          </div>

          <h4><?= htmlspecialchars($p["name"]) ?></h4>
          <p>Rp <?= number_format($p["price"],0,',','.') ?></p>

          <button class="shop-btn-add add-to-cart"
                  data-id="<?= $p['id'] ?>"
                  data-name="<?= htmlspecialchars($p['name']) ?>">
            🛒 Tambahkan
          </button>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<!-- MODAL INPUT JUMLAH -->
<div class="modal fade" id="qtyModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">

      <h5 id="qtyProductName">Masukkan Jumlah</h5>

      <input type="number" id="inputQty" class="form-control text-center mt-3" value="1" min="1">

      <div class="d-flex justify-content-center gap-3 mt-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Batal
        </button>
        <button type="button" id="confirmAddToCart" class="btn btn-primary">
          OK
        </button>
      </div>

    </div>
  </div>
</div>

<!-- MODAL SUKSES -->
<div class="modal fade" id="cartSuccessModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">

      <i class="bi bi-patch-check-fill" style="font-size:48px;color:#c24a4a;"></i>
      <h5 class="mt-2">Berhasil</h5>
      <p>Produk berhasil ditambahkan ke keranjang</p>

      <div class="d-flex justify-content-center gap-3 mt-3">
        <button type="button" id="btnLanjutBelanja" class="btn btn-secondary">
          Lanjut Belanja
        </button>
        <button type="button" id="btnKeKeranjang" class="btn btn-primary">
          Lihat Keranjang
        </button>
      </div>

    </div>
  </div>
</div>

<?php include "inc/footer.php"; ?>

<script>
let selectedProductId = null;

// Klik tombol Tambahkan
document.querySelectorAll('.add-to-cart').forEach(btn => {
  btn.addEventListener('click', function(){

    selectedProductId = this.dataset.id;
    document.getElementById("inputQty").value = 1;
    document.getElementById("qtyProductName").innerText =
      "Jumlah untuk: " + this.dataset.name;

    const qtyModal = new bootstrap.Modal(document.getElementById('qtyModal'));
    qtyModal.show();
  });
});

// Klik OK di input jumlah
document.getElementById("confirmAddToCart").addEventListener("click", function () {

  const qty = parseInt(document.getElementById("inputQty").value) || 1;

  fetch("add_to_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "id=" + encodeURIComponent(selectedProductId) +
          "&qty=" + encodeURIComponent(qty)
  })
  .then(res => res.json())
  .then(data => {

    bootstrap.Modal.getInstance(document.getElementById('qtyModal')).hide();

    if (data.status === "success") {

      const notif = document.getElementById("cartNotif");
      notif.innerText = data.count;
      notif.style.display = "flex";

      const successModal = new bootstrap.Modal(document.getElementById('cartSuccessModal'));
      successModal.show();

      document.getElementById('btnLanjutBelanja').onclick = function () {
        successModal.hide();
      };

      document.getElementById('btnKeKeranjang').onclick = function () {
        window.location.href = "pembayaran.php";
      };

    } else {
      alert("Gagal menambahkan ke keranjang");
    }
  })
  .catch(() => alert("Terjadi kesalahan"));
});
</script>

</body>
</html>
