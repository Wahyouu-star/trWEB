<?php
// products.php
// Mengambil data produk dari database secara aman

// Gunakan require_once untuk memastikan koneksi HANYA dibuat jika belum ada.
require_once "inc/koneksi.php";

$products = [];

// Pastikan koneksi ($conn) ada dan berhasil sebelum menjalankan query
if (isset($conn) && $conn !== false) {
    // Ambil data dari tabel products, urutkan berdasarkan ID terbaru
    $sql = "SELECT id, name, price, image_path FROM products ORDER BY id DESC";
    // @ digunakan untuk menekan error jika ada masalah query mendadak
    $result = @mysqli_query($conn, $sql); 

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = [
                "id"    => (int)$row['id'],
                "name"  => $row['name'],
                "price" => (float)$row['price'],
                "image" => $row['image_path'] 
            ];
        }
    }
}
?>