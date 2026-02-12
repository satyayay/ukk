<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Pengaduan | Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bs-primary: #009ef7;
            --bs-body-bg: #f5f8fa;
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bs-body-bg);
            overflow-x: hidden;
        }

        /* --- SIDEBAR STYLE --- */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #1e1e2d;
            color: #9899ac;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar.active {
            margin-left: 0 !important;
        }

        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 25px;
            font-weight: 700;
            color: #fff;
            font-size: 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: #a2a3b7;
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
            border-left: 3px solid transparent;
        }

        .menu-item:hover, .menu-item.active {
            color: #fff;
            background-color: rgba(255,255,255,0.05);
            border-left: 3px solid var(--bs-primary);
        }

        .menu-item i {
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* --- MAIN CONTENT STYLE --- */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        /* --- TOPBAR STYLE --- */
        .topbar {
            height: 70px;
            background: white;
            box-shadow: 0px 10px 30px 0px rgba(82,63,105,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        /* --- CUSTOM UTILS --- */
        .card-custom {
            border: 0;
            box-shadow: 0px 0px 20px 0px rgba(76, 87, 125, 0.02);
            border-radius: 0.65rem;
            background: white;
        }
        
        .card-header-custom {
            background: transparent;
            border-bottom: 1px solid #eff2f5;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-body-custom {
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-check text-primary me-2 fs-2"></i> 
            <span>LaporPak!</span>
        </div>
        <div class="d-md-none ms-auto cursor-pointer" onclick="document.querySelector('.sidebar').classList.remove('active')">
            <i class="bi bi-x-lg text-muted"></i>
        </div>
    </div>
    
    <div class="sidebar-menu">
        <div class="px-4 mb-2 text-uppercase fs-8 fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Menu Utama</div>

        <?php 
        $page = basename($_SERVER['PHP_SELF']);
        ?>

        <?php if(isset($_SESSION['status']) && $_SESSION['status'] == "login_admin"): ?>
            <a href="../admin/index.php" class="menu-item <?php echo ($page == 'index.php') ? 'active' : ''; ?>">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="../admin/siswa.php" class="menu-item <?php echo ($page == 'siswa.php' || $page == 'tambah_siswa.php' || $page == 'edit_siswa.php') ? 'active' : ''; ?>">
                <i class="bi bi-people-fill"></i> Akun Siswa
            </a>
            <a href="../admin/laporan.php" class="menu-item <?php echo ($page == 'laporan.php') ? 'active' : ''; ?>">
                <i class="bi bi-chat-left-text-fill"></i> Laporan Masuk
            </a>

        <?php elseif(isset($_SESSION['status']) && $_SESSION['status'] == "login_siswa"): ?>
            <a href="../siswa/index.php" class="menu-item <?php echo ($page == 'index.php') ? 'active' : ''; ?>">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="../siswa/tulis_pengaduan.php" class="menu-item <?php echo ($page == 'tulis_pengaduan.php') ? 'active' : ''; ?>">
                <i class="bi bi-pencil-square"></i> Tulis Laporan
            </a>
            <a href="../siswa/riwayat.php" class="menu-item <?php echo ($page == 'riwayat.php') ? 'active' : ''; ?>">
                <i class="bi bi-clock-history"></i> Riwayat Pengaduan
            </a>
        <?php endif; ?>

        <div class="px-4 mt-4 mb-2 text-uppercase fs-8 fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Akun</div>
        <a href="../logout.php" class="menu-item text-danger">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</nav>

<div class="main-content">
    
    <header class="topbar">
        <div class="d-flex align-items-center">
            <button class="btn btn-light btn-sm d-md-none me-3" onclick="document.querySelector('.sidebar').classList.toggle('active')">
                <i class="bi bi-list fs-2"></i>
            </button>
            <h5 class="m-0 fw-bold text-dark">Dashboard</h5>
        </div>

        <div class="dropdown">
            <div class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                <div class="text-end me-3 d-none d-md-block">
                    <span class="d-block fw-bold text-dark fs-6">
                        <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'User'; ?>
                    </span>
                    <span class="d-block text-muted" style="font-size: 0.75rem;">
                        <?php echo isset($_SESSION['status']) && $_SESSION['status'] == 'login_admin' ? 'Administrator' : 'Siswa'; ?>
                    </span>
                </div>
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold border border-primary border-opacity-25" style="width: 40px; height: 40px;">
                    <?php echo substr(isset($_SESSION['nama']) ? $_SESSION['nama'] : 'U', 0, 1); ?>
                </div>
            </div>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-3 p-2" style="width: 220px;">
                <li>
                    <div class="d-flex align-items-center px-3 py-2 border-bottom mb-2">
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark"><?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'User'; ?></span>
                            <span class="text-muted small text-truncate"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?></span>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item rounded py-2 text-danger fw-bold" href="../logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i> Keluar Aplikasi
                    </a>
                </li>
            </ul>
        </div>
    </header>

    <div class="container-fluid p-4">