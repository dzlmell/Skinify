<?php
session_start();

// CEK LOGIN
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// --- SIMULASI DATABASE PERAWATAN (bisa diganti MYSQL) ---
$perawatan_data = [
    1 => ['id' => 1, 'nama' => 'Moisturizer', 'deskripsi' => 'Melembapkan dan menjaga kulit tetap sehat.', 'icon' => 'fa-droplet'],
    2 => ['id' => 2, 'nama' => 'Sunscreen', 'deskripsi' => 'Melindungi kulit dari sinar UV.', 'icon' => 'fa-sun'],
    3 => ['id' => 3, 'nama' => 'Serum', 'deskripsi' => 'Perawatan intensif untuk berbagai masalah kulit.', 'icon' => 'fa-wand-magic'],
];

// === VARIABEL UNTUK NOTIFIKASI ===
$message = "";

// === PROSES HAPUS DATA ===
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($perawatan_data[$id])) {
        unset($perawatan_data[$id]);
        $message = "Data perawatan berhasil dihapus.";
    }
}

// === PROSES TAMBAH / EDIT DATA ===
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $icon = $_POST['icon'];

    if (isset($_POST['id'])) {
        // MODE EDIT
        $id_edit = $_POST['id'];
        $perawatan_data[$id_edit] = [
            'id' => $id_edit,
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'icon' => $icon
        ];
        $message = "Data perawatan berhasil diperbarui.";
    } else {
        // MODE TAMBAH
        $new_id = max(array_keys($perawatan_data)) + 1;
        $perawatan_data[$new_id] = [
            'id' => $new_id,
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'icon' => $icon
        ];
        $message = "Perawatan baru berhasil ditambahkan.";
    }

    header("Location: perawatan.php?message=" . urlencode($message));
    exit();
}

// === CEK MODE HALAMAN (LIST / ADD / EDIT) ===
$action = $_GET['action'] ?? 'list';
$item_to_edit = null;

if ($action == 'edit' && isset($_GET['id'])) {
    $id_edit = $_GET['id'];
    if (isset($perawatan_data[$id_edit])) {
        $item_to_edit = $perawatan_data[$id_edit];
    } else {
        $action = 'list';
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Perawatan Kulit - Skinify Admin</title>

    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<header class="navbar">
    <div class="logo">Skinify <span>Admin</span></div>
    <ul class="nav-links">
        <li><a href="index.html">Lihat Situs</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</header>

<div class="dashboard-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h3>Menu</h3>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-list"></i> Kelola Masalah Kulit</a></li>
            <li><a href="perawatan.php" class="active"><i class="fas fa-spa"></i> Kelola Perawatan Kulit</a></li>
            <li><a href="konsultasi.php"><i class="fas fa-comments"></i> Lihat Konsultasi</a></li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <?php if ($action == 'list'): ?>

            <div class="content-header">
                <h1>Kelola Perawatan Kulit</h1>
                <a href="perawatan.php?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Baru
                </a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Perawatan</th>
                        <th>Deskripsi</th>
                        <th>Icon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($perawatan_data as $item): ?>
                    <tr>
                        <td><?= $item['id']; ?></td>
                        <td><?= htmlspecialchars($item['nama']); ?></td>
                        <td><?= htmlspecialchars($item['deskripsi']); ?></td>
                        <td><i class="fas <?= htmlspecialchars($item['icon']); ?>"></i></td>
                        <td class="actions">
                            <a href="perawatan.php?action=edit&id=<?= $item['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="perawatan.php?action=delete&id=<?= $item['id']; ?>" class="btn btn-danger"
                               onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>

            <h1><?= $action == 'edit' ? 'Edit Perawatan' : 'Tambah Perawatan'; ?></h1>

            <form action="perawatan.php" method="POST" class="form-container">

                <?php if ($action == 'edit'): ?>
                    <input type="hidden" name="id" value="<?= $item_to_edit['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Nama Perawatan</label>
                    <input type="text" name="nama" required
                        value="<?= $item_to_edit ? $item_to_edit['nama'] : '' ?>">
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="4" required><?= $item_to_edit ? $item_to_edit['deskripsi'] : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label>Icon (FontAwesome)</label>
                    <input type="text" name="icon" required placeholder="contoh: fa-sun"
                        value="<?= $item_to_edit ? $item_to_edit['icon'] : '' ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                    <a href="perawatan.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                </div>
            </form>

        <?php endif; ?>

    </main>
</div>

</body>
</html>
