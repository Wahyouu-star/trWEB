<?php
session_start();
include "products.php";

$showSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_transfer'])) {
    if ($_POST['confirm_transfer'] === 'yes') {
        unset($_SESSION['cart']); // reset keranjang
        $showSuccess = true;
    }
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];

if (isset($_GET['plus'])) {
    $id = (int)$_GET['plus'];
    if (isset($cart[$id])) {
        $cart[$id]++;
    } else {
        $cart[$id] = 1;
    }
    $_SESSION['cart'] = $cart;
    header("Location: pembayaran.php");
    exit;
}

if (isset($_GET['minus'])) {
    $id = (int)$_GET['minus'];
    if (isset($cart[$id])) {
        $cart[$id]--;
        if ($cart[$id] <= 0) {
            unset($cart[$id]);
        }
    }
    $_SESSION['cart'] = $cart;
    header("Location: pembayaran.php");
    exit;
}

$cartItems = [];
$total = 0;

if (!empty($cart)) {
    foreach ($cart as $id => $qty) {
        foreach ($products as $p) {
            if ($p['id'] == $id) {
                $p['qty'] = $qty;
                $p['total_price'] = $p['price'] * $qty;
                $cartItems[] = $p;
                $total += $p['total_price'];
                break;
            }
        }
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
    <style>
      body {
          margin: 0;
          font-family: Poppins, sans-serif;
          background: #f5f5f5;
      }

      .header {
          width: 100%;
          background: white;
          padding: 15px 40px;
          display: flex;
          justify-content: space-between;
          align-items: center;
          border-bottom: 2px solid #eee;
      }

      .right {
          display: flex;
          align-items: center;
          gap: 35px;
          margin-right: 70px;
      }

      .menu-text {
          margin-right: 10px;
      }


      .profile-icon {
          text-decoration: none;
      }

      .avatar {
          width: 35px;
          height: 35px;
          border-radius: 50%;
          overflow: hidden;
          display: flex;
          justify-content: center;
          align-items: center;
          background: #ddd;
      }

      .avatar-img {
          width: 100%;
          height: 100%;
          object-fit: cover;
      }



      .logo {
          font-size: 22px;
          font-weight: 700;
      }

      .top-bar {
          width: 100%;
          display: flex;
          justify-content: center;
          align-items: center;
          padding: 15px 40px;
          margin-top: 10px;
      }

      .search-box {
          width: 70%;
          max-width: 600px;
          background: #f8f8f8;
          padding: 10px 15px;
          border-radius: 10px;
          display: flex;
          align-items: center;
          border: 1px solid #ddd;
      }

      .search-box i {
          margin-right: 10px;
          color: #777;
      }

      .search-box input {
          width: 100%;
          border: none;
          outline: none;
          font-size: 15px;
          background: transparent;
      }

      .cart-page-icon {
          position: relative;
      }

      .cart-page-icon img {
          width: 30px;
      }

      .notif {
          position: absolute;
          top: -6px;
          right: -8px;
          background: red;
          color: white;
          padding: 3px 7px;
          border-radius: 50%;
          font-size: 12px;
      }


      /* PRODUCT GRID */
      .container {
          padding: 30px;
      }

      .title {
          margin-bottom: 15px;
      }

      .product-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
          gap: 20px;
      }

      .product-card {
          background: white;
          padding: 15px;
          border-radius: 12px;
          box-shadow: 0px 4px 10px rgba(0,0,0,0.05);
          text-align: center;
      }

      .product-card .img {
          width: 100%;
          height: 120px;
          background: #ddd;
          border-radius: 10px;
          margin-bottom: 10px;
      }

      .product-card .img {
          width: 100%;
          height: 140px;
          background: #ddd;
          border-radius: 10px;
          margin-bottom: 10px;
          overflow: hidden;
          display: flex;
          justify-content: center;
          align-items: center;
      }

      .product-card .img img {
          width: 100%;
          height: 100%;
          object-fit: cover;
      }

      .cart-img2 {
          width: 80px;
          height: 80px;
          background: #f5f5f5;
          border-radius: 10px;
          overflow: hidden;
          display: flex;
          justify-content: center;
          align-items: center;
      }

      .cart-img2 img {
          width: 100%;
          height: 100%;
          object-fit: contain;
      }



      .btn-add {
          background: #c0392b;
          color: white;
          padding: 8px 15px;
          border-radius: 8px;
          text-decoration: none;
          display: inline-block;
      }

      /* CART PAGE */
      .cart-box {
          background: white;
          padding: 20px;
          border-radius: 12px;
          max-width: 500px;
      }

      .cart-item {
          display: flex;
          gap: 15px;
          margin-bottom: 15px;
      }

      .img-small {
          width: 60px;
          height: 60px;
          background: #ddd;
          border-radius: 10px;
      }

      .btn-pay {
          background: #c0392b;
          width: 100%;
          padding: 12px;
          border: none;
          border-radius: 10px;
          color: white;
          font-size: 16px;
      }

      .pay-container {
          margin-top: 30px;
          display: grid;
          grid-template-columns: 1.2fr 1fr;
          gap: 30px;
      }

      /* LIST PRODUK KIRI */
      .cart-list-box {
          background: #fff;
          padding: 25px;
          border-radius: 15px;
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
          height: fit-content;
      }

      .cart-item2 {
          display: flex;
          gap: 15px;
          padding-bottom: 20px;
          margin-bottom: 20px;
          border-bottom: 1px solid #eee;
      }

      .cart-img2 {
          width: 80px;
          height: 80px;
          background: #e6e6e6;
          border-radius: 15px;
      }

      /* FORM PEMBAYARAN */
      .pay-box {
          background: #fff;
          padding: 35px;
          border-radius: 20px;
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      }

      .pay-box input {
          width: 100%;
          padding: 10px 0;
          border: none;
          border-bottom: 1px solid #bbb;
          margin-bottom: 20px;
          outline: none;
      }

      .btn-pay-new {
          width: 100%;
          padding: 15px;
          font-size: 18px;
          background: #c0392b;
          color: white;
          border: none;
          border-radius: 25px;
          cursor: pointer;
          margin-top: 20px;
          box-shadow: 0 4px 8px rgba(192,57,43,0.3);
      }

      .btn-pay-new:hover {
          background: #a83226;
      }

      /* POPUP BACKGROUND */
      .popup {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0,0,0,0.5);
          display: none;
          justify-content: center;
          align-items: center;
          z-index: 9999;
      }

      /* POPUP BOX */
      .popup-box {
          width: 260px;
          background: white;
          border-radius: 15px;
          padding: 20px;
          text-align: center;
          box-shadow: 0px 6px 15px rgba(0,0,0,0.2);
      }

      /* ICON CIRCLE */
      .popup-icon {
          width: 70px;
          height: 70px;
          margin: 0 auto 10px;
          background: #c34646;
          color: white;
          font-size: 40px;
          font-weight: bold;
          border-radius: 50%;
          display: flex;
          justify-content: center;
          align-items: center;
      }

      /* TITLE */
      .popup-box h3 {
          font-size: 22px;
          margin-top: 5px;
      }

      /* TEXT */
      .popup-box p {
          margin: 10px 0 20px;
          font-size: 14px;
      }

      /* OK BUTTON */
      .popup-ok {
          background: #c34646;
          color: white;
          border: none;
          padding: 8px 25px;
          border-radius: 10px;
          font-size: 15px;
          cursor: pointer;
      }

      .popup-ok:hover {
          background: #a63434;
      }

      /* ================================
         POPUP SUCCESS (GLOBAL STYLE)
         ================================ */

      .popup-overlay {
          position: fixed;
          inset: 0;
          background: rgba(0,0,0,0.45);
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 9999;
          animation: fadeInBg 0.3s ease;
      }

      .popup-box {
          width: 240px;
          background: #ffffff;
          padding: 25px;
          border-radius: 15px;
          text-align: center;
          box-shadow: 0px 5px 12px rgba(0,0,0,0.2);
          animation: fadeInPopup 0.35s ease;
      }

      /* ICON BULAT MERAH */
      .popup-icon {
          width: 70px;
          height: 70px;
          background: #c34646;
          border-radius: 50%;
          color: #fff;
          font-size: 38px;
          display: flex;
          justify-content: center;
          align-items: center;
          margin: 0 auto 12px;
      }

      /* TEXT */
      .popup-box h3 {
          font-size: 22px;
          margin-bottom: 8px;
      }

      .popup-box p {
          font-size: 14px;
          margin-bottom: 18px;
      }

      /* BUTTON */
      .popup-btn {
          background: #c34646;
          color: #fff;
          border: none;
          padding: 8px 25px;
          border-radius: 20px;
          font-size: 15px;
          cursor: pointer;
          box-shadow: 0px 3px 6px rgba(0,0,0,0.25);
          transition: 0.2s;
      }

      .popup-btn:hover {
          background: #a73737;
      }

      /* ANIMASI */
      @keyframes fadeInPopup {
          from { transform: scale(0.85); opacity: 0; }
          to { transform: scale(1); opacity: 1; }
      }

      @keyframes fadeInBg {
          from { opacity: 0; }
          to { opacity: 1; }
      }

      /* Container Card */
      .profile-container {
        width: 450px;
        margin: 50px auto;
        padding: 25px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      }

      /* Title */
      .profile-container h2 {
        text-align: center;
        margin-bottom: 25px;
      }

      /* Label */
      .profile-container label {
        font-weight: 600;
        margin-top: 10px;
        display: block;
      }

      /* Input */
      .profile-container input[type="text"],
      .profile-container input[type="date"],
      .profile-container input[type="tel"],
      .profile-container input[type="email"] {
        width: 100%;
        padding: 8px 5px;
        margin-top: 3px;
        border: none;
        border-bottom: 1px solid #888;
        outline: none;
        font-size: 15px;
      }

      /* Date Input with Icon */
      .date-input {
        position: relative;
      }

      .date-input i {
        position: absolute;
        right: 10px;
        top: 10px;
        color: #b30000;
        font-size: 20px;
      }

      /* Gender Button */
      .gender-btns {
        display: flex;
        gap: 10px;
        margin: 10px 0;
      }

      .gender-btn {
        flex: 1;
        padding: 12px 0;
        border: none;
        background: #d9534f;
        color: white;
        font-weight: bold;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
      }

      .gender-btn i {
        margin-right: 5px;
      }

      .gender-btn.active {
        background: #c9302c;
        transform: scale(1.05);
      }

      .gender-btn:hover {
        background: #c9302c;
      }

      .edit-btn {
        margin-top: 25px;
        background: #d9534f;
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 8px;
        cursor: pointer;
        display: block;
        margin-left: auto;
      }


      .edit-btn:hover {
        background: #c9302c;
      }

      /* Popup */
      .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .popup-box {
        background: white;
        padding: 20px 30px;
        border-radius: 12px;
        text-align: center;
        width: 300px;
      }

      .popup-icon {
        font-size: 40px;
        color: green;
        margin-bottom: 10px;
      }

      .popup-btn {
        margin-top: 12px;
        background: #d9534f;
        color: white;
        padding: 8px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
      }

      .footer {
          width: 100%;
          background: #ffffff;
          border-top: 2px solid #eee;
          padding: 15px 0;
          margin-top: 40px;
      }

      .footer-container {
          text-align: center;
          font-size: 14px;
          color: #777;
      }

      /* Internal CSS from pembayaran.php */
      .back-links{
        max-width:1100px;
        margin:100px auto 10px auto;
        padding:0 15px;
      }
      .back-btn{
        display:inline-block;
        padding:6px 14px;
        border-radius:999px;
        background:#f0f0f0;
        color:#333;
        text-decoration:none;
        font-size:0.9rem;
        transition:.2s;
      }
      .back-btn:hover{
        background:#e0e0e0;
      }

      .popup .popup-actions{
        margin-top:15px;
        display:flex;
        justify-content:center;
        gap:10px;
      }
      .popup-yes, .popup-no{
        padding:6px 18px;
        border-radius:8px;
        border:none;
        cursor:pointer;
        font-weight:500;
      }
      .popup-yes{
        background:#b22929;
        color:#fff;
      }
      .popup-no{
        background:#e0e0e0;
        color:#333;
      }
      .popup ul{
        text-align:left;
        padding-left:18px;
        margin-top:8px;
        font-size:0.9rem;
      }
    </style>
</head>

<body>

<?php include "inc/header.php"; ?>

<div class="back-links">
    <a href="tokosparepart.php" class="back-btn">← Kembali ke Toko Sparepart</a>
</div>

<div class="container">

    <div class="pay-container">

        <div class="cart-list-box">

            <?php if (count($cartItems) == 0): ?>

                <p>Keranjang masih kosong.</p>

            <?php else: ?>

                <?php foreach ($cartItems as $item): ?>
                <div class="cart-item2">

                    <div class="cart-img2">
                      <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    </div>

                    <div>
                        <h4><?php echo $item['name']; ?></h4>
                        <p>Rp. <?php echo number_format($item['price']); ?></p>

                        <div style="margin-top:10px;">
                            Jumlah :

                            <a href="pembayaran.php?minus=<?php echo $item['id']; ?>">
                                <button type="button" class="qty-btn">-</button>
                            </a>

                            <?php echo $item['qty']; ?>

                            <a href="pembayaran.php?plus=<?php echo $item['id']; ?>">
                                <button type="button" class="qty-btn">+</button>
                            </a>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <div class="pay-box">
            <h2 style="text-align:center; margin-bottom:25px;">Pembayaran</h2>

            <label>Harga</label>
            <input type="text" value="Rp. <?php echo number_format($total); ?>" readonly>

            <label>Masukkan Voucher</label>
            <input type="text" placeholder="Masukkan voucher">

            <label>Alamat</label>
            <input type="text" placeholder="Alamat lengkap">

            <label>Subtotal Bayar</label>
            <input type="text" value="Rp. <?php echo number_format($total); ?>" readonly>

            <label>Pilih Metode Pembayaran</label>
            <input type="text" value="Transfer Bank BCA / BRI" readonly>

            <button class="btn-pay-new" onclick="showConfirm()">BAYAR</button>

        </div>

    </div>

</div>

<div id="popupConfirm" class="popup">
    <div class="popup-box">
        <div class="popup-icon">
            💳
        </div>

        <h3>Konfirmasi Pembayaran</h3>

        <p>Silakan lakukan transfer ke salah satu rekening berikut:</p>
        <ul>
            <li>BCA – 1234567890 a.n <b>AUTOCARE</b></li>
            <li>BRI – 9876543210 a.n <b>AUTOCARE</b></li>
        </ul>
        <p>Setelah transfer, klik <b>Ya</b> untuk menyelesaikan pesanan.</p>

        <div class="popup-actions">
            <button type="button" class="popup-no" onclick="closeConfirm()">Tidak</button>

            <form method="POST" style="display:inline;">
                <input type="hidden" name="confirm_transfer" value="yes">
                <button type="submit" class="popup-yes">Ya</button>
            </form>
        </div>
    </div>
</div>

<div id="popupSuccess" class="popup">
    <div class="popup-box">
        <div class="popup-icon">
            ✔
        </div>

        <h3>Berhasil</h3>

        <p>Anda berhasil melakukan pembayaran melalui transfer bank.</p>

        <button class="popup-ok" onclick="closeSuccess()">Ok</button>
    </div>
</div>

<script>
function showConfirm() {
    document.getElementById("popupConfirm").style.display = "flex";
}

function closeConfirm() {
    document.getElementById("popupConfirm").style.display = "none";
}

function closeSuccess() {
    document.getElementById("popupSuccess").style.display = "none";
}
</script>

<?php if ($showSuccess): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById("popupSuccess").style.display = "flex";
});
</script>
<?php endif; ?>

<?php include "inc/footer.php"; ?>

</body>
</html>