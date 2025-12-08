<?php
session_start();
include "products.php";
include "inc/koneksi.php"; // Tambahkan koneksi database

$showSuccess = false;
$user_id = $_SESSION['user']['id'] ?? 0;

if ($user_id == 0) {
    // Redirect jika user belum login atau Guest
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];

// --- Logika Tambah/Kurang Kuantitas ---
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

// --- Kalkulasi Isi Keranjang ---
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

// ===================================
// 1. PROSES KONFIRMASI DAN SIMPAN KE DB
// ===================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_transfer'])) {
    if ($_POST['confirm_transfer'] === 'yes') {
        
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat_pengiriman'] ?? 'Tidak ada alamat');
        $total_amount = $total; // Menggunakan variabel $total yang sudah dikalkulasi di atas
        
        // Memulai transaksi database
        mysqli_begin_transaction($conn);
        
        try {
            // Cek apakah keranjang kosong
            if (empty($cartItems)) {
                throw new Exception("Keranjang belanja kosong.");
            }

            // 1. Insert ke tabel orders (transaksi utama)
            $sql_order = "INSERT INTO orders (user_id, total_amount, shipping_address, order_date) 
                          VALUES ($user_id, $total_amount, '$alamat', NOW())";
            
            if (!mysqli_query($conn, $sql_order)) {
                throw new Exception("Gagal membuat pesanan (Orders).");
            }
            
            $order_id = mysqli_insert_id($conn);
            
            // 2. Insert ke tabel order_details (detail barang)
            foreach ($cartItems as $item) {
                $product_name = mysqli_real_escape_string($conn, $item['name']);
                $unit_price = $item['price'];
                $quantity = $item['qty'];
                $product_id = $item['id'];
                
                $sql_detail = "INSERT INTO order_details (order_id, product_id, product_name, unit_price, quantity)
                               VALUES ($order_id, $product_id, '$product_name', $unit_price, $quantity)";
                
                if (!mysqli_query($conn, $sql_detail)) {
                    throw new Exception("Gagal menyimpan detail pesanan (Order Details).");
                }
            }
            
            // 3. Commit Transaksi dan Bersihkan Keranjang
            mysqli_commit($conn);
            unset($_SESSION['cart']); 
            $showSuccess = true;

        } catch (Exception $e) {
            mysqli_rollback($conn);
            // Tampilkan error ke pengguna
            echo "<script>alert('Terjadi kesalahan saat menyimpan pesanan: " . $e->getMessage() . "');</script>";
        }
    }
}
// ===================================

?>
<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
    <style>
      /* --- CSS Anda (TIDAK BERUBAH) --- */
      body {
          margin: 0;
          font-family: Poppins, sans-serif;
          background: #f5f5f5;
      }
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
      .container {
          padding: 30px;
      }
      .pay-container {
          margin-top: 30px;
          display: grid;
          grid-template-columns: 1.2fr 1fr;
          gap: 30px;
          max-width: 1100px;
          margin: 30px auto;
      }
      @media (max-width: 768px) {
          .pay-container {
              grid-template-columns: 1fr;
          }
      }
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
          align-items: center;
      }

      .cart-img2 {
          width: 80px;
          height: 80px;
          background: #e6e6e6;
          border-radius: 15px;
          display: flex;
          justify-content: center;
          align-items: center;
      }

      .cart-img2 img {
          max-width: 90%;
          max-height: 90%;
          object-fit: contain;
      }
      
      .pay-box {
          background: #fff;
          padding: 35px;
          border-radius: 20px;
          box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      }

      .pay-box label {
          font-weight: 600;
          margin-top: 10px;
          display: block;
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
      .qty-btn {
          background: #e0e0e0;
          border: 1px solid #ccc;
          padding: 0 8px;
          border-radius: 5px;
          cursor: pointer;
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
            <h2 style="text-align:center; margin-bottom:25px;">Keranjang Belanja</h2>

            <?php if (count($cartItems) == 0): ?>

                <p style="text-align:center;">Keranjang masih kosong.</p>

            <?php else: ?>

                <?php foreach ($cartItems as $item): ?>
                <div class="cart-item2">

                    <div class="cart-img2">
                      <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    </div>

                    <div style="flex-grow:1;">
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
                    <div style="font-weight:bold; color:#b22929;">
                        Rp. <?php echo number_format($item['total_price']); ?>
                    </div>
                </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <div class="pay-box">
            <h2 style="text-align:center; margin-bottom:25px;">Pembayaran</h2>
            
            <label>Harga Barang</label>
            <input type="text" value="Rp. <?php echo number_format($total); ?>" readonly>

            <label>Masukkan Voucher</label>
            <input type="text" placeholder="Masukkan voucher">

            <label>Alamat Pengiriman</label>
            <input type="text" id="inputAlamat" placeholder="Alamat lengkap" required>

            <label>Subtotal Bayar</label>
            <input type="text" value="Rp. <?php echo number_format($total); ?>" readonly>

            <label>Pilih Metode Pembayaran</label>
            <input type="text" value="Transfer Bank BCA / BRI" readonly>

            <?php if (count($cartItems) > 0): ?>
                <button class="btn-pay-new" onclick="showConfirm()">BAYAR</button>
            <?php else: ?>
                <button class="btn-pay-new" disabled>KERANJANG KOSONG</button>
            <?php endif; ?>

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

            <form method="POST" id="confirmForm" style="display:inline;">
                <input type="hidden" name="confirm_transfer" value="yes">
                <input type="hidden" name="alamat_pengiriman" id="alamatHidden">
                <button type="submit" class="popup-yes">Ya</button>
            </form>
        </div>
    </div>
</div>

<div id="popupSuccess" class="popup">
    <div class="popup-box">
        <div class="popup-icon" style="background:#2ecc71;">
            ✔
        </div>

        <h3>Berhasil</h3>

        <p>Pesanan berhasil dibuat</p>

        <button class="popup-ok" onclick="closeSuccess()">Ok</button>
    </div>
</div>

<script>
function showConfirm() {
    // 1. Ambil alamat dari input
    const alamat = document.getElementById("inputAlamat").value;
    
    if (alamat.trim() === '') {
        alert('Mohon lengkapi Alamat Pengiriman sebelum melanjutkan!');
        return; 
    }
    
    // 2. Masukkan alamat ke hidden field di form konfirmasi
    document.getElementById("alamatHidden").value = alamat;
    
    // 3. Tampilkan popup
    document.getElementById("popupConfirm").style.display = "flex";
}

function closeConfirm() {
    document.getElementById("popupConfirm").style.display = "none";
}

function closeSuccess() {
    document.getElementById("popupSuccess").style.display = "none";
    window.location.href = "beranda.php"; 
}
</script>

<?php if ($showSuccess): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    history.replaceState(null, null, 'pembayaran.php'); 
    document.getElementById("popupSuccess").style.display = "flex";
});
</script>
<?php endif; ?>

<?php include "inc/footer.php"; ?>

</body>
</html>