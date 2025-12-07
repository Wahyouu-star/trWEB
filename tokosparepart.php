<?php
session_start();
include "products.php";

// Hitung total item di keranjang
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    // asumsi format: [id_produk => qty]
    $cartCount = array_sum($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Auto Care - Toko Sparepart</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
      /* === STYLING KHUSUS HALAMAN TOKO (tidak ganggu halaman lain) === */

      body{
        background:#f6f7fb;
        font-family:"Poppins", Arial, sans-serif;
      }

      .shop-wrapper{
        max-width:1100px;
        margin:90px auto 60px auto;   /* jarak dari header */
        padding:0 15px;
      }

      .shop-top-bar{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:18px;
      }

      .shop-search-box{
        background:#fff;
        border-radius:999px;
        padding:6px 14px;
        display:flex;
        align-items:center;
        gap:8px;
        box-shadow:0 3px 8px rgba(0,0,0,0.12);
      }

      .shop-search-box i{
        color:#b22929;
        font-size:0.9rem;
      }

      .shop-search-box input{
        border:none;
        outline:none;
        font-size:0.9rem;
        width:200px;
        background:transparent;
      }

      .shop-cart-icon{
        position:relative;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background:#fff;
        padding:8px 12px;
        border-radius:999px;
        text-decoration:none;
        box-shadow:0 3px 8px rgba(0,0,0,0.12);
        color:#333;
        gap:6px;
      }

      .shop-cart-icon img{
        width:24px;
        height:24px;
        object-fit:contain;
      }

      .shop-cart-notif{
        position:absolute;
        top:-6px;
        right:-6px;
        background:#b22929;
        color:#fff;
        border-radius:999px;
        width:20px;
        height:20px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:0.7rem;
      }

      .shop-title{
        font-weight:700;
        color:#333;
        margin-bottom:18px;
      }

      .shop-product-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:20px;
      }

      .shop-product-card{
        background:#fff;
        border-radius:16px;
        padding:14px 14px 16px 14px;
        box-shadow:0 4px 12px rgba(0,0,0,0.08);
        display:flex;
        flex-direction:column;
        align-items:center;
        text-align:center;
        transition:transform .2s, box-shadow .2s;
      }

      .shop-product-card:hover{
        transform:translateY(-4px);
        box-shadow:0 8px 18px rgba(0,0,0,0.12);
      }

      .shop-product-card .img{
        width:100%;
        height:130px;
        display:flex;
        align-items:center;
        justify-content:center;
        margin-bottom:10px;
      }

      .shop-product-card .img img{
        max-width:100%;
        max-height:100%;
        object-fit:contain;
      }

      .shop-product-card h4{
        font-size:0.95rem;
        margin-bottom:4px;
        min-height:40px;
      }

      .shop-product-card p{
        font-weight:600;
        color:#b22929;
        margin-bottom:6px;
        font-size:0.9rem;
      }

      /* kontrol jumlah */
      .shop-qty-wrapper{
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        margin-top:4px;
      }

      .shop-qty-btn{
        width:26px;
        height:26px;
        border-radius:50%;
        border:none;
        background:#f0f0f0;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        font-size:0.85rem;
        transition:background .2s, transform .1s;
      }

      .shop-qty-btn:hover{
        background:#e0e0e0;
        transform:scale(1.05);
      }

      .shop-qty-input{
        width:42px;
        text-align:center;
        border-radius:20px;
        border:1px solid #ddd;
        padding:2px 4px;
        font-size:0.85rem;
      }

      .shop-btn-add{
        margin-top:10px;
        width:100%;
        border:none;
        border-radius:999px;
        padding:7px 0;
        background:#b22929;
        color:#fff;
        font-size:0.9rem;
        font-weight:500;
        cursor:pointer;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:6px;
        transition:background .2s, transform .1s, box-shadow .2s;
      }

      .shop-btn-add:hover{
        background:#941f1f;
        transform:translateY(-1px);
        box-shadow:0 4px 10px rgba(0,0,0,0.15);
      }

      @media(max-width:576px){
        .shop-wrapper{
          margin-top:80px;
        }
        .shop-top-bar{
          flex-direction:column;
          align-items:flex-start;
          gap:10px;
        }
      }

      /* gambar terbang ke keranjang */
      .fly-img {
          position: fixed;
          z-index: 9999;
          pointer-events: none;
          width: 80px;
          height: 80px;
          border-radius: 12px;
          object-fit: cover;
          opacity: 1;
          transition: all 0.7s ease-in-out;
      }
    </style>
</head>
<body>

<?php include "inc/header.php"; ?>

<div class="shop-wrapper">

  <!-- TOP BAR: Search + Cart -->
  <div class="shop-top-bar">

      <!-- Search -->
      <div class="shop-search-box">
          <i class="fa fa-search"></i>
          <input type="text" id="searchInput" placeholder="Cari produk...">
      </div>

      <!-- Cart (target animasi: class cart-icon) -->
      <a href="pembayaran.php" class="shop-cart-icon cart-icon">
          <img src="IMG/cart.jpg" alt="Cart">
          <span class="shop-cart-notif" id="cartNotif" style="<?php echo ($cartCount > 0 ? '' : 'display:none'); ?>">
              <?php echo $cartCount; ?>
          </span>
      </a>

  </div>

  <h2 class="shop-title">Produk</h2>

  <div class="shop-product-grid" id="productGrid">
      <?php foreach ($products as $p): ?>
      <div class="shop-product-card">
          <div class="img">
            <img src="<?php echo $p['image']; ?>" 
                 alt="<?php echo htmlspecialchars($p['name']); ?>"
                 class="shop-product-img">
          </div>
          <h4 class="shop-product-name"><?php echo $p["name"]; ?></h4>
          <p>Rp <?php echo number_format($p["price"],0,',','.'); ?></p>

          <!-- kontrol jumlah -->
          <div class="shop-qty-wrapper">
            <button type="button" class="shop-qty-btn" onclick="changeQty(<?php echo $p['id']; ?>, -1)">
              <i class="fa fa-minus"></i>
            </button>
            <input type="text" id="qty-<?php echo $p['id']; ?>" class="shop-qty-input" value="1">
            <button type="button" class="shop-qty-btn" onclick="changeQty(<?php echo $p['id']; ?>, 1)">
              <i class="fa fa-plus"></i>
            </button>
          </div>

          <button class="shop-btn-add add-to-cart" data-id="<?php echo $p['id']; ?>">
            <i class="fa fa-cart-plus"></i> Tambahkan
          </button>
      </div>
      <?php endforeach; ?>
  </div>
</div>

<?php include "inc/footer.php"; ?>

<script>
// Ubah quantity
function changeQty(id, delta){
    const input = document.getElementById('qty-' + id);
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    input.value = val;
}

// Animasi 
function flyToCart(imgElement) {
    const cartImg = document.querySelector('.cart-icon img');
    if (!cartImg || !imgElement) return;

    const imgClone = imgElement.cloneNode(true);
    const rect = imgElement.getBoundingClientRect();

    imgClone.classList.add('fly-img');
    imgClone.style.left = rect.left + "px";
    imgClone.style.top = rect.top + "px";

    document.body.appendChild(imgClone);

    const cartRect = cartImg.getBoundingClientRect();
    setTimeout(()=>{
        imgClone.style.left = cartRect.left + "px";
        imgClone.style.top = cartRect.top + "px";
        imgClone.style.width = "20px";
        imgClone.style.height = "20px";
        imgClone.style.opacity = "0.2";
    },100);

    setTimeout(()=>{ imgClone.remove(); },800);
}

// Event listener setelah DOM siap
document.addEventListener('DOMContentLoaded', function () {

    // Tombol tambah ke keranjang + animasi
    document.querySelectorAll('.add-to-cart').forEach(function(btn){
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            const qtyInput = document.getElementById('qty-' + id);
            const qty = parseInt(qtyInput.value) || 1;

            // Animasi dari img produk
            const card = this.closest('.shop-product-card');
            const img = card.querySelector('.shop-product-img');
            flyToCart(img);

            // Request ke add_to_cart.php
            fetch("add_to_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id=" + encodeURIComponent(id) + "&qty=" + encodeURIComponent(qty)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    const notif = document.getElementById("cartNotif");
                    notif.innerText = data.count;
                    notif.style.display = "flex";
                } else {
                    alert("Gagal menambahkan ke keranjang");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Terjadi kesalahan");
            });
        });
    });

    // Pencarian produk (client-side)
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function () {
        const term = this.value.toLowerCase();
        const cards = document.querySelectorAll('.shop-product-card');

        cards.forEach(card => {
            const name = card.querySelector('.shop-product-name').innerText.toLowerCase();
            if (name.includes(term)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });
});
</script>

</body>
</html>
