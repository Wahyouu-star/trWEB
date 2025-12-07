<?php
session_start();
header('Content-Type: application/json');

// Ambil data dari AJAX
$id  = isset($_POST['id'])  ? (int)$_POST['id']  : 0;
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

if ($id <= 0) {
    echo json_encode([
        "status"  => "error",
        "message" => "ID produk tidak valid"
    ]);
    exit;
}

if ($qty < 1) {
    $qty = 1;
}

// Inisialisasi cart kalau belum ada
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Format cart: [id_produk => qty]
if (!isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] = 0;
}
$_SESSION['cart'][$id] += $qty;

// Hitung total semua qty untuk badge keranjang
$count = array_sum($_SESSION['cart']);

echo json_encode([
    "status" => "success",
    "count"  => $count
]);
