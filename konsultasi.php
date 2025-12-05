<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Contoh data konsultasi
$konsultasi = [
    ["nama" => "Putri", "pesan" => "Kulit saya kering, butuh saran."],
    ["nama" => "Aulia", "pesan" => "Cara hilangkan jerawat yang aman?"]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Konsultasi - Skinify Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

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
            <li><a href="konsultasi.php" class="active"><i class="fas fa-comments"></i> Lihat Konsultasi</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <h1>Daftar Konsultasi</h1>

        <table>
            <thead>
                <tr>
                    <th>Nama Pengirim</th>
                    <th>Pertanyaan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($konsultasi as $k): ?>
                <tr>
                    <td><?= htmlspecialchars($k['nama']); ?></td>
                    <td><?= htmlspecialchars($k['pesan']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </main>
</div>

</body>
</html>
