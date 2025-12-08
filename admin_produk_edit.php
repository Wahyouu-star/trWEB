<?php
// admin_produk_edit.php
session_start();
include "inc/koneksi.php";
include "inc/header.php";

// Cek autentikasi: Hanya Admin (id -1) yang boleh mengakses
if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != -1) {
    header('Location: login.php');
    exit;
}

$message = '';
$upload_dir = 'IMG/'; 

// ===================================
// CRUD LOGIC (Insert, Update, Delete)
// ===================================

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id_to_delete = (int)$_GET['id'];
    
    $res = mysqli_query($conn, "SELECT image_path FROM products WHERE id=$id_to_delete");
    if ($row = mysqli_fetch_assoc($res)) {
        if (file_exists($row['image_path'])) {
            @unlink($row['image_path']);
        }
    }

    $sql_delete = "DELETE FROM products WHERE id=$id_to_delete";
    if (mysqli_query($conn, $sql_delete)) {
        $message = "<div class='alert alert-success'>Produk berhasil dihapus.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Gagal menghapus produk: " . mysqli_error($conn) . "</div>";
    }
    header("Location: admin_produk_edit.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int)($_POST['id'] ?? 0);
    $name  = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    
    $image_path = mysqli_real_escape_string($conn, $_POST['current_image_path'] ?? '');

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['image']['name']);
        $final_image_name = time() . "_" . preg_replace('/\s+/', '_', $image_name);
        $target_file = $upload_dir . $final_image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = mysqli_real_escape_string($conn, $target_file);
        } else {
            $message = "<div class='alert alert-danger'>Gagal upload gambar.</div>";
        }
    }

    if ($id > 0) {
        // UPDATE PRODUK
        $sql = "UPDATE products SET name='$name', price=$price";
        if (!empty($image_path)) {
            $sql .= ", image_path='$image_path'";
        }
        $sql .= " WHERE id=$id";
        
        if (mysqli_query($conn, $sql)) {
            $message = "<div class='alert alert-success'>Produk berhasil diperbarui.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Gagal memperbarui produk: " . mysqli_error($conn) . "</div>";
        }
    } else {
        // TAMBAH PRODUK BARU
        if (!empty($image_path)) {
            $sql = "INSERT INTO products (name, price, image_path) VALUES ('$name', $price, '$image_path')";
            if (mysqli_query($conn, $sql)) {
                $message = "<div class='alert alert-success'>Produk baru berhasil ditambahkan.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Gagal menambah produk: " . mysqli_error($conn) . "</div>";
            }
        } else {
             $message = "<div class='alert alert-danger'>Gagal menambah produk: File gambar harus diupload.</div>";
        }
    }
    
    // Redirect setelah INSERT/UPDATE/DELETE untuk membersihkan URL
    header("Location: admin_produk_edit.php");
    exit;
}


// TAMPILKAN DATA PRODUK
$result_products = mysqli_query($conn, "SELECT id, name, price, image_path FROM products ORDER BY id DESC");
$product_list = mysqli_fetch_all($result_products, MYSQLI_ASSOC);

// Data untuk form EDIT
$edit_product = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id_to_edit = (int)$_GET['id'];
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id=$id_to_edit");
    $edit_product = mysqli_fetch_assoc($res);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Produk - AUTO CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        main { padding-top: 100px; max-width: 1200px; margin: auto; }
        h1 { color: #b22929; margin-bottom: 30px; }
        h2 { margin-bottom: 25px; color: #c24a4a; }
        .data-section { padding: 20px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 30px; background: #fff; }
        .product-img { width: 50px; height: 50px; object-fit: contain; border-radius: 5px; }
        .table-responsive { max-height: 500px; overflow-y: auto; }
        th { background-color: #f8d7da; color: #721c24; position: sticky; top: 0; }
        /* .admin-menu dihapus */
    </style>
</head>
<body>
    <main>
        <h1 class="text-center">MANAJEMEN PRODUK (CRUD)</h1>

        <?php 
        // Menampilkan pesan setelah redirect
        if (isset($_SESSION['crud_message'])) {
            echo $_SESSION['crud_message'];
            unset($_SESSION['crud_message']);
        }
        ?>

        <div class="data-section mb-5">
            <h2><?= ($edit_product ? 'Edit Produk: ' . htmlspecialchars($edit_product['name']) : 'Tambah Produk Baru') ?></h2>
            <form method="POST" enctype="multipart/form-data" action="admin_produk_edit.php">
                <input type="hidden" name="id" value="<?= $edit_product['id'] ?? '' ?>">

                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="name" class="form-control" value="<?= $edit_product['name'] ?? '' ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" value="<?= $edit_product['price'] ?? '' ?>" required min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label">Gambar Produk (JPG/PNG)</label>
                    <input type="file" name="image" class="form-control" <?= ($edit_product ? '' : 'required') ?>>
                    <?php if ($edit_product && !empty($edit_product['image_path'])): ?>
                        <small class="text-muted">Gambar saat ini: <img src="<?= $edit_product['image_path'] ?>" class="product-img ms-2"></small>
                        <input type="hidden" name="current_image_path" value="<?= $edit_product['image_path'] ?>">
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn-primary" style="background:#2ecc71; border:none;">
                    <i class="bi bi-save"></i> <?= ($edit_product ? 'Simpan Perubahan' : 'Tambah Produk') ?>
                </button>
                <?php if ($edit_product): ?>
                    <a href="admin_produk_edit.php" class="btn btn-secondary">Batal Edit</a>
                <?php endif; ?>
            </form>
        </div>


        <div class="data-section">
            <h2>Daftar Semua Produk (<?= count($product_list) ?> item)</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($product_list)): ?>
                            <tr><td colspan="5" class="text-center">Belum ada produk.</td></tr>
                        <?php else: ?>
                            <?php foreach ($product_list as $p): ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><img src="<?= htmlspecialchars($p['image_path']) ?>" class="product-img"></td>
                                    <td><?= htmlspecialchars($p['name']) ?></td>
                                    <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="admin_produk_edit.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-info text-white">Edit</a>
                                        <a href="admin_produk_edit.php?action=delete&id=<?= $p['id'] ?>" 
                                           onclick="return confirm('Yakin ingin menghapus produk ini?')" 
                                           class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
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