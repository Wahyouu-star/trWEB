<?php
// pindah_ke_riwayat.php
session_start();
include "inc/koneksi.php";

// Pastikan user sudah login dan bukan Guest
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id']) || $_SESSION['user']['id'] == 0) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$id_pengingat = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_pengingat <= 0) {
    header('Location: pengingat.php');
    exit;
}

// 1. Ambil data dari tabel pengingat
$sql_select = "SELECT * FROM pengingat WHERE id = $id_pengingat AND user_id = $user_id";
$result = mysqli_query($conn, $sql_select);
$data = mysqli_fetch_assoc($result);

if ($data) {
    // 2. Masukkan data ke tabel riwayat
    $jenis          = mysqli_real_escape_string($conn, $data['jenis_servis']);
    $jarak          = (int)$data['jarak'];
    $durasi         = (int)$data['durasi'];
    $waktu          = mysqli_real_escape_string($conn, $data['waktu']);
    $interval_servis = mysqli_real_escape_string($conn, $data['interval_servis']);

    $sql_insert_riwayat = "INSERT INTO riwayat (user_id, jenis_servis, jarak, durasi, waktu, interval_servis, selesai_pada) 
                           VALUES ($user_id, '$jenis', $jarak, $durasi, '$waktu', '$interval_servis', NOW())";
    
    if (mysqli_query($conn, $sql_insert_riwayat)) {
        // 3. Hapus data dari tabel pengingat
        $sql_delete_pengingat = "DELETE FROM pengingat WHERE id = $id_pengingat AND user_id = $user_id";
        mysqli_query($conn, $sql_delete_pengingat);

        // Redirect kembali ke halaman pengingat dengan pesan sukses
        header('Location: pengingat.php?status=moved');
        exit;
    } else {
        // Jika gagal insert ke riwayat
        echo "<script>alert('Gagal memindahkan ke riwayat: " . mysqli_error($conn) . "'); window.location='pengingat.php';</script>";
        exit;
    }
} else {
    // Jika data pengingat tidak ditemukan (ID tidak valid)
    header('Location: pengingat.php');
    exit;
}
?>