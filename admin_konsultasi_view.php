<?php
// admin_konsultasi_view.php
session_start();
include "inc/koneksi.php";
include "inc/header.php";

// Cek autentikasi: Hanya Admin (id -1) yang boleh mengakses
if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != -1) {
    header('Location: login.php');
    exit;
}

// ===================================
// LOGIC VIEW CHAT HISTORY
// ===================================

$view_user_id = (int)($_GET['user_id'] ?? 0);
$user_chat_data = null;
$chat_history_display = [];
$mekanik_name = "Chatbot Mekanik"; // Default untuk simulasi

if ($view_user_id > 0) {
    // 1. Ambil data user yang sedang dimonitor
    $sql_user = "SELECT id, username, nama, telp, email FROM users WHERE id = $view_user_id";
    $res_user = mysqli_query($conn, $sql_user);
    $user_chat_data = mysqli_fetch_assoc($res_user);

    // 2. Tentukan kunci session chat (simulasi)
    // Karena user memilih mekanik statis (misal Pak Slamet), kita ambil kunci chat sesuai default
    // Di aplikasi nyata, ini memerlukan DB untuk mencatat siapa chatting dengan siapa.
    // Kita asumsikan user terakhir chatting dengan 'Pak Slamet' untuk memuat history-nya.
    $chatKey = 'chat_history_PakSlamet'; // Ganti ini jika Anda tahu mekanik default user

    // 3. Ambil history dari Session (Admin melihat Session user)
    // CATATAN: Dalam arsitektur web nyata, Admin tidak bisa langsung melihat $_SESSION user lain. 
    // Data ini harusnya diambil dari tabel database (misal: 'chat_logs')
    $chat_history_display = $_SESSION[$chatKey] ?? [];

    $mekanik_name = $_SESSION['mekanik_terpilih'] ?? "Pak Slamet"; // Tampilkan nama mekanik simulasi
}


// ===================================
// LOGIC DAFTAR USER UNTUK MONITORING
// ===================================
$sql_users = "SELECT id, username, nama, telp, email FROM users WHERE id > 0 ORDER BY id DESC";
$result_users = mysqli_query($conn, $sql_users);
$user_list = mysqli_fetch_all($result_users, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Konsultasi - AUTO CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        main { padding-top: 100px; max-width: 1200px; margin: auto; }
        h1 { color: #b22929; margin-bottom: 30px; }
        h2 { margin-bottom: 25px; color: #c24a4a; }
        .data-section { margin-bottom: 50px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: #fff; }
        th { background-color: #f8d7da; color: #721c24; position: sticky; top: 0; }
        .chat-container { border: 1px solid #ccc; border-radius: 8px; padding: 15px; height: 400px; overflow-y: scroll; background: #f9f9f9; }
        .chat-message-display { margin-bottom: 10px; padding: 8px; border-radius: 6px; max-width: 80%; }
        .user-msg { background: #d1e7dd; margin-left: auto; text-align: right; }
        .mekanik-msg { background: #f8d7da; margin-right: auto; }
    </style>
</head>
<body>
    <main>
        <h1 class="text-center">MONITORING CHAT / KONSULTASI</h1>

        <div class="data-section">
            <p class="alert alert-info">
                Anda dapat melihat detail riwayat chat (simulasi) dari user yang sedang aktif. 
                <a href="admin_konsultasi_view.php" class="btn btn-sm btn-secondary ms-2">Lihat Semua User</a>
            </p>

            <?php if ($view_user_id > 0 && $user_chat_data): ?>
                <h2>Riwayat Chat User: <?= htmlspecialchars($user_chat_data['nama']) ?></h2>
                <p>Mekanik Simulan: <strong><?= htmlspecialchars($mekanik_name) ?></strong></p>
                
                <div class="chat-container">
                    <?php if (empty($chat_history_display)): ?>
                        <p class="text-center text-muted">Belum ada percakapan tersimpan untuk user ini.</p>
                    <?php else: ?>
                        <?php foreach ($chat_history_display as $msg): ?>
                            <div class="chat-message-display <?= ($msg['sender'] === 'user' ? 'user-msg' : 'mekanik-msg') ?>">
                                <strong><?= ($msg['sender'] === 'user' ? 'Anda' : htmlspecialchars($mekanik_name)) ?>:</strong>
                                <?= htmlspecialchars($msg['message']) ?>
                                <small class="text-muted d-block" style="font-size:10px;"><?= date('H:i:s', $msg['time']) ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <h2>Daftar User yang Dapat Dimonitor</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th>ID User</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>No. Telp</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($user_list)): ?>
                                <tr><td colspan="5" class="text-center">Tidak ada user terdaftar.</td></tr>
                            <?php else: ?>
                                <?php foreach ($user_list as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><?= htmlspecialchars($user['nama']) ?></td>
                                        <td><?= htmlspecialchars($user['telp']) ?></td>
                                        <td>
                                            <a href="admin_konsultasi_view.php?user_id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Lihat Percakapan</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include "inc/footer.php"; ?>
</body>
</html>