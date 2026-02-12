<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_siswa") {
    header("location:../index.php?pesan=belum_login");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';

$nis = $_SESSION['username'];

// HITUNG STATISTIK SISWA
// 1. Total Laporan Saya
$sql_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM input_aspirasi WHERE nis='$nis'");
$row_total = mysqli_fetch_assoc($sql_total);
$total_laporan = $row_total['total'];

// 2. Total Selesai
$sql_selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM input_aspirasi JOIN aspirasi ON input_aspirasi.id_pelaporan = aspirasi.id_pelaporan WHERE input_aspirasi.nis='$nis' AND aspirasi.status='selesai'");
$row_selesai = mysqli_fetch_assoc($sql_selesai);
$total_selesai = $row_selesai['total'];

// 3. Total Proses
$sql_proses = mysqli_query($conn, "SELECT COUNT(*) as total FROM input_aspirasi JOIN aspirasi ON input_aspirasi.id_pelaporan = aspirasi.id_pelaporan WHERE input_aspirasi.nis='$nis' AND aspirasi.status='proses'");
$row_proses = mysqli_fetch_assoc($sql_proses);
$total_proses = $row_proses['total'];

// 4. Menunggu
$total_menunggu = $total_laporan - ($total_selesai + $total_proses);
?>

<div class="row g-4">
    <div class="col-12">
        <div class="card card-custom bg-info text-white border-0">
            <div class="card-body-custom d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold">Halo, <?php echo $_SESSION['nama']; ?>!</h2>
                    <p class="mb-0 op-7">Selamat datang di panel siswa. Laporkan kerusakan sarana sekolah disini.</p>
                </div>
                <i class="bi bi-person-workspace fs-1 opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom border-start border-4 border-primary h-100">
            <div class="card-body-custom p-4">
                <div class="fs-6 text-muted mb-1">Total Laporan</div>
                <div class="fs-2 fw-bold text-dark"><?php echo $total_laporan; ?></div>
                <div class="text-primary small fw-bold mt-2">Pernah Dikirim</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom border-start border-4 border-warning h-100">
            <div class="card-body-custom p-4">
                <div class="fs-6 text-muted mb-1">Sedang Proses</div>
                <div class="fs-2 fw-bold text-dark"><?php echo $total_proses; ?></div>
                <div class="text-warning small fw-bold mt-2">Tindak Lanjut</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom border-start border-4 border-success h-100">
            <div class="card-body-custom p-4">
                <div class="fs-6 text-muted mb-1">Selesai</div>
                <div class="fs-2 fw-bold text-dark"><?php echo $total_selesai; ?></div>
                <div class="text-success small fw-bold mt-2">Sudah Beres</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom border-start border-4 border-secondary h-100">
            <div class="card-body-custom p-4">
                <div class="fs-6 text-muted mb-1">Menunggu</div>
                <div class="fs-2 fw-bold text-dark"><?php echo $total_menunggu; ?></div>
                <div class="text-muted small fw-bold mt-2">Belum Direspon</div>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>