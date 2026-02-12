<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_admin") {
    header("location:../index.php?pesan=belum_login");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';

// HITUNG DATA STATISTIK
// 1. Total Laporan Masuk
$sql_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM input_aspirasi");
$row_total = mysqli_fetch_assoc($sql_total);
$total_laporan = $row_total['total'];

// 2. Total Selesai
$sql_selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi WHERE status='selesai'");
$row_selesai = mysqli_fetch_assoc($sql_selesai);
$total_selesai = $row_selesai['total'];

// 3. Total Proses
$sql_proses = mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi WHERE status='proses'");
$row_proses = mysqli_fetch_assoc($sql_proses);
$total_proses = $row_proses['total'];

// 4. Total Menunggu (Total Laporan - (Selesai + Proses))
// Asumsinya laporan yang belum ada di tabel 'aspirasi' atau statusnya belum diset adalah 'Menunggu'
$total_menunggu = $total_laporan - ($total_selesai + $total_proses);
?>

<div class="row g-4">
    <div class="col-12">
        <div class="card card-custom bg-primary text-white border-0">
            <div class="card-body-custom d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold">Selamat Datang, Admin!</h2>
                    <p class="mb-0 op-7">Ini adalah halaman statistik pengelolaan pengaduan sekolah.</p>
                </div>
                <i class="bi bi-trophy-fill fs-1 opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom border-start border-4 border-info h-100">
            <div class="card-body-custom p-4">
                <div class="fs-6 text-muted mb-1">Total Laporan</div>
                <div class="fs-2 fw-bold text-dark"><?php echo $total_laporan; ?></div>
                <div class="text-info small fw-bold mt-2">Semua Laporan Masuk</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom border-start border-4 border-danger h-100">
            <div class="card-body-custom p-4">
                <div class="fs-6 text-muted mb-1">Menunggu</div>
                <div class="fs-2 fw-bold text-dark"><?php echo $total_menunggu; ?></div>
                <div class="text-danger small fw-bold mt-2">Belum Ditanggapi</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom border-start border-4 border-warning h-100">
            <div class="card-body-custom p-4">
                <div class="fs-6 text-muted mb-1">Sedang Proses</div>
                <div class="fs-2 fw-bold text-dark"><?php echo $total_proses; ?></div>
                <div class="text-warning small fw-bold mt-2">Dalam Pengerjaan</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom border-start border-4 border-success h-100">
            <div class="card-body-custom p-4">
                <div class="fs-6 text-muted mb-1">Selesai</div>
                <div class="fs-2 fw-bold text-dark"><?php echo $total_selesai; ?></div>
                <div class="text-success small fw-bold mt-2">Masalah Teratasi</div>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>