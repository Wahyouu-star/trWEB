<?php
// admin_dashboard.php
session_start();
include "inc/koneksi.php";
include "inc/header.php";

// Cek autentikasi: Hanya Admin (id -1) yang boleh mengakses
if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != -1) {
    header('Location: login.php');
    exit;
}

// 1. Ambil data Pengingat Servis dari SEMUA user
$sql_pengingat = "SELECT p.id, u.username, p.jenis_servis, p.jarak, p.waktu, p.interval_servis, p.created_at 
                  FROM pengingat p 
                  JOIN users u ON p.user_id = u.id 
                  ORDER BY p.created_at DESC";
$result_pengingat = mysqli_query($conn, $sql_pengingat);
$pengingat_list = mysqli_fetch_all($result_pengingat, MYSQLI_ASSOC);

// 2. Ambil data Pesanan (Orders) dari SEMUA user
$sql_orders = "SELECT o.*, u.username 
               FROM orders o
               JOIN users u ON o.user_id = u.id
               ORDER BY o.order_date DESC";
$result_orders = mysqli_query($conn, $sql_orders);
$orders_list = mysqli_fetch_all($result_orders, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Admin Dashboard - AUTO CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        main { padding-top: 100px; max-width: 1200px; margin: auto; }
        h1 { color: #b22929; margin-bottom: 30px; }
        h2 { margin-bottom: 25px; color: #c24a4a; }
        .data-section { margin-bottom: 50px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: #fff; }
        .table-responsive { max-height: 400px; overflow-y: auto; }
        th { background-color: #f8d7da; color: #721c24; position: sticky; top: 0; }
        /* .admin-menu dihapus */
    </style>
</head>
<body>
    <main>
        <h1 class="text-center">ADMIN DASHBOARD</h1>
        
        <div class="data-section">
            <h2>Daftar Pengingat Servis User</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Jenis Servis</th>
                            <th>Jarak (km)</th>
                            <th>Waktu</th>
                            <th>Interval</th>
                            <th>Tgl Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pengingat_list)): ?>
                            <tr><td colspan="7" class="text-center">Tidak ada data pengingat.</td></tr>
                        <?php else: ?>
                            <?php foreach ($pengingat_list as $item): ?>
                                <tr>
                                    <td><?= $item['id'] ?></td>
                                    <td><?= htmlspecialchars($item['username']) ?></td>
                                    <td><?= htmlspecialchars($item['jenis_servis']) ?></td>
                                    <td><?= htmlspecialchars($item['jarak']) ?></td>
                                    <td><?= htmlspecialchars($item['waktu']) ?></td>
                                    <td><?= htmlspecialchars($item['interval_servis']) ?></td>
                                    <td><?= date('d M Y H:i', strtotime($item['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="data-section">
            <h2>Daftar Pesanan Sparepart</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Username</th>
                            <th>Total</th>
                            <th>Alamat Kirim</th>
                            <th>Status</th>
                            <th>Tgl Pesan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders_list)): ?>
                            <tr><td colspan="6" class="text-center">Tidak ada data pesanan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders_list as $item): ?>
                                <tr>
                                    <td><?= $item['id'] ?></td>
                                    <td><?= htmlspecialchars($item['username']) ?></td>
                                    <td>Rp <?= number_format($item['total_amount'], 0, ',', '.') ?></td>
                                    <td><?= htmlspecialchars(substr($item['shipping_address'], 0, 50)) ?>...</td>
                                    <td><?= htmlspecialchars($item['status']) ?></td>
                                    <td><?= date('d M Y H:i', strtotime($item['order_date'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>