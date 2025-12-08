<?php
// inc/koneksi.php
$host = "localhost";
$user = "root";
$pass = ""; // Ganti jika password MySQL/MariaDB Anda tidak kosong
$db   = "auto_care";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}
?>