<?php
// inc/koneksi.php
$host = "localhost";
$user = "root";
$pass = ""; // Ganti sesuai password Anda
$db   = "auto_care"; // HARUS 'auto_care'

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}
?>