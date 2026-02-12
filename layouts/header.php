<?php
// Logika Foto Profil (Tetap sama)
if(isset($conn) && isset($_SESSION['status'])) {
    $foto_profil_session = "";
    if($_SESSION['status'] == "login_siswa") {
        $user_id = $_SESSION['username'];
        $q_profil = mysqli_query($conn, "SELECT foto_profil FROM siswa WHERE nis='$user_id'");
        if($d_profil = mysqli_fetch_assoc($q_profil)) { $foto_profil_session = $d_profil['foto_profil']; }
    } elseif($_SESSION['status'] == "login_admin") {
        $user_id = $_SESSION['username'];
        $q_profil = mysqli_query($conn, "SELECT foto_profil FROM admin WHERE username='$user_id'");
        if($d_profil = mysqli_fetch_assoc($q_profil)) { $foto_profil_session = $d_profil['foto_profil']; }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaporPak! | E-Pengaduan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f5f8fa; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #1e1e2d; z-index: 1000; transition: 0.3s; }
        .main-content { margin-left: 260px; transition: 0.3s; }
        .nav-link { color: #9899ac; padding: 12px 25px; display: flex; align-items: center; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.05); color: #fff; border-left: 4px solid #009ef7; }
        .nav-link i { margin-right: 10px; }
        @media (max-width: 768px) { .sidebar { margin-left: -260px; } .main-content { margin-left: 0; } .sidebar.active { margin-left: 0; } }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="p-4 border-bottom border-secondary d-flex align-items-center">
        <img src="../assets/logo.png" height="35" class="me-2">
        <h4 class="text-white m-0">LaporPak!</h4>
    </div>
    <div class="py-4">
        <?php $page = basename($_SERVER['PHP_SELF']); ?>
        <a href="index.php" class="nav-link <?php echo $page=='index.php'?'active':''; ?>"><i class="bi bi-grid"></i> Dashboard</a>
        
        <?php if($_SESSION['status'] == "login_admin"): ?>
            <a href="siswa.php" class="nav-link <?php echo $page=='siswa.php'?'active':''; ?>"><i class="bi bi-people"></i> Data Siswa</a>
            <a href="laporan.php" class="nav-link <?php echo $page=='laporan.php'?'active':''; ?>"><i class="bi bi-chat-left-text"></i> Laporan</a>
        <?php else: ?>
            <a href="tulis_pengaduan.php" class="nav-link"><i class="bi bi-pencil-square"></i> Tulis Laporan</a>
            <a href="riwayat.php" class="nav-link"><i class="bi bi-clock-history"></i> Riwayat</a>
        <?php endif; ?>
        
        <a href="../logout.php" class="nav-link text-danger mt-4"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top px-4 py-3">
        <button class="btn btn-light d-md-none me-3" onclick="document.getElementById('sidebar').classList.toggle('active')">
            <i class="bi bi-list fs-4"></i>
        </button>
        <h5 class="m-0 fw-bold">Dashboard</h5>
        <div class="ms-auto d-flex align-items-center">
            <div class="text-end me-3 d-none d-md-block">
                <small class="d-block fw-bold"><?php echo $_SESSION['nama']; ?></small>
                <small class="text-muted"><?php echo $_SESSION['status']; ?></small>
            </div>
            <img src="../assets/logo.png" width="40" height="40" class="rounded-circle border border-primary">
        </div>
    </nav>
    <div class="container-fluid p-4">