<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_admin") {
    header("location:../index.php");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';

$nis = $_GET['nis'];
$query = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");
$data = mysqli_fetch_array($query);
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h3 class="card-title fw-bold m-0 fs-4">Edit Data Siswa</h3>
            </div>
            <div class="card-body-custom">
                <form action="proses_edit_siswa.php" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">NIS (Tidak bisa diubah)</label>
                        <input type="text" name="nis" class="form-control bg-light" value="<?php echo $data['nis']; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_siswa" class="form-control" value="<?php echo $data['nama_siswa']; ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Kelas</label>
                        <input type="text" name="kelas" class="form-control" value="<?php echo $data['kelas']; ?>" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="siswa.php" class="btn btn-light me-2 fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>