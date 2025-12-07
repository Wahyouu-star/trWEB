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
    <link rel="stylesheet" href="style.css">
    <style>
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

<!-- TOMBOL BACK KE TOKO SPAREPART -->
<div class="back-links">
    <a href="tokosparepart.php" class="back-btn">← Kembali ke Toko Sparepart</a>
</div>

<div class="container">

    <div class="pay-container">

        <!-- LEFT: LIST PRODUK -->
        <div class="cart-list-box">

            <?php if (count($cartItems) == 0): ?>

                <p>Keranjang masih kosong.</p>

            <?php else: ?>

                <?php foreach ($cartItems as $item): ?>
                <div class="cart-item2">

                    <!-- GAMBAR -->
                    <div class="cart-img2">
                      <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    </div>

                    <div>
                        <h4><?php echo $item['name']; ?></h4>
                        <p>Rp. <?php echo number_format($item['price']); ?></p>

                        <div style="margin-top:10px;">
                            Jumlah :

                            <!-- BUTTON MINUS -->
                            <a href="pembayaran.php?minus=<?php echo $item['id']; ?>">
                                <button type="button" class="qty-btn">-</button>
                            </a>

                            <?php echo $item['qty']; ?>

                            <!-- BUTTON PLUS -->
                            <a href="pembayaran.php?plus=<?php echo $item['id']; ?>">
                                <button type="button" class="qty-btn">+</button>
                            </a>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <!-- RIGHT: FORM PEMBAYARAN -->
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

<!-- POPUP KONFIRMASI TRANSFER -->
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

<!-- POPUP PEMBAYARAN -->
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
