<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_admin") {
    header("location:../index.php");
    die();
}
include '../layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h3 class="card-title fw-bold m-0 fs-4">Tambah Siswa Baru</h3>
            </div>
            <div class="card-body-custom">
                <form action="proses_tambah_siswa.php" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">NIS</label>
                        <input type="number" name="nis" class="form-control" placeholder="Masukkan NIS" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_siswa" class="form-control" placeholder="Nama Siswa" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Kelas</label>
                        <input type="text" name="kelas" class="form-control" placeholder="Contoh: XII RPL 1" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="siswa.php" class="btn btn-light me-2 fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>