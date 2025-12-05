<?php
session_start();

// CEK APAKAH USER SUDAH LOGIN
// Jika tidak, alihkan ke halaman login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// --- SIMULASI DATABASE (kode Anda yang sudah ada) ---
 $masalah_kulit_data = [
    // ... data Anda
];
// ... logika CRUD Anda yang sudah ada


// Memulai session untuk manajemen login (jika diperlukan)
session_start();

// --- SIMULASI DATABASE ---
// Dalam aplikasi asli, data ini akan diambil dari database (MySQL, dll).
// Kami menggunakan array untuk kemudahan demonstrasi.
 $masalah_kulit_data = [
    1 => ['id' => 1, 'nama' => 'Normal', 'deskripsi' => 'Kulit normal memiliki kelembaban seimbang dan jarang bermasalah.', 'icon' => 'fa-smile'],
    2 => ['id' => 2, 'nama' => 'Kering', 'deskripsi' => 'Kulit kering terasa kaku, mudah pecah-pecah, dan kusam.', 'icon' => 'fa-snowflake'],
    3 => ['id' => 3, 'nama' => 'Berminyak', 'deskripsi' => 'Kulit berminyak berkilap, pori besar, dan rentan jerawat.', 'icon' => 'fa-oil-can'],
    4 => ['id' => 4, 'nama' => 'Sensitif', 'deskripsi' => 'Mudah iritasi, kemerahan, dan reaktif terhadap produk baru.', 'icon' => 'fa-exclamation-triangle'],
    // ... data lainnya
];

// --- LOGIKA CRUD (Create, Read, Update, Delete) ---

// Inisialisasi variabel pesan
 $message = '';

// Proses Hapus Data
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];
    if (isset($masalah_kulit_data[$id_to_delete])) {
        unset($masalah_kulit_data[$id_to_delete]);
        // Dalam aplikasi nyata, Anda akan menjalankan query DELETE di sini.
        // Contoh: DELETE FROM masalah_kulit WHERE id = ?
        $message = "Data dengan ID $id_to_delete berhasil dihapus.";
    }
}

// Proses Tambah & Edit Data (dari form submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $icon = $_POST['icon'];

    if (isset($_POST['id'])) { // Mode Edit
        $id_to_edit = $_POST['id'];
        if (isset($masalah_kulit_data[$id_to_edit])) {
            $masalah_kulit_data[$id_to_edit] = [
                'id' => $id_to_edit,
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'icon' => $icon
            ];
            // Dalam aplikasi nyata: UPDATE masalah_kulit SET ... WHERE id = ?
            $message = "Data berhasil diperbarui.";
        }
    } else { // Mode Tambah
        $new_id = max(array_keys($masalah_kulit_data)) + 1;
        $masalah_kulit_data[$new_id] = [
            'id' => $new_id,
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'icon' => $icon
        ];
        // Dalam aplikasi nyata: INSERT INTO masalah_kulit (nama, deskripsi, icon) VALUES (?,?,?)
        $message = "Data baru berhasil ditambahkan.";
    }
    // Redirect untuk menghindari resubmission form saat halaman di-refresh
    header("Location: dashboard.php?message=" . urlencode($message));
    exit();
}

// Menentukan aksi yang sedang dilakukan (lihat, tambah, atau edit)
 $action = isset($_GET['action']) ? $_GET['action'] : 'list';
 $item_to_edit = null;
if ($action == 'edit' && isset($_GET['id'])) {
    $id_to_edit = $_GET['id'];
    if (isset($masalah_kulit_data[$id_to_edit])) {
        $item_to_edit = $masalah_kulit_data[$id_to_edit];
    } else {
        $action = 'list'; // Kembali ke list jika ID tidak ditemukan
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Skinify</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Link ke CSS khusus dashboard -->
    <link rel="stylesheet" href="dashboard.css">
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
    <aside class="sidebar">
        <h3>Menu</h3>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-list"></i> Kelola Masalah Kulit</a></li>
            <li><a href="perawatan.php"><i class="fas fa-spa"></i> Kelola Perawatan Kulit</a></li>
            <li><a href="konsultasi.php"><i class="fas fa-comments"></i> Lihat Konsultasi</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <?php if ($action == 'list'): ?>
            <div class="content-header">
                <h1>Kelola Masalah Kulit</h1>
                <a href="dashboard.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Baru</a>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Icon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($masalah_kulit_data as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['nama']); ?></td>
                        <td><?php echo htmlspecialchars($item['deskripsi']); ?></td>
                        <td><i class="fas <?php echo htmlspecialchars($item['icon']); ?>"></i></td>
                        <td class="actions">
                            <a href="dashboard.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="dashboard.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php elseif ($action == 'add' || $action == 'edit'): ?>
            <h1><?php echo $action == 'edit' ? 'Edit' : 'Tambah'; ?> Masalah Kulit</h1>
            <form action="dashboard.php" method="POST" class="form-container">
                <?php if ($action == 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $item_to_edit['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="nama">Nama Masalah</label>
                    <input type="text" id="nama" name="nama" value="<?php echo $item_to_edit ? htmlspecialchars($item_to_edit['nama']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" required><?php echo $item_to_edit ? htmlspecialchars($item_to_edit['deskripsi']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="icon">Icon Class (FontAwesome)</label>
                    <input type="text" id="icon" name="icon" value="<?php echo $item_to_edit ? htmlspecialchars($item_to_edit['icon']) : ''; ?>" placeholder="contoh: fa-smile" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                    <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        <?php endif; ?>
    </main>
  </div>

</body>
</html>