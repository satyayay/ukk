<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_admin") {
    header("location:../index.php");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';

$username = $_SESSION['username'];
$query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
$data = mysqli_fetch_array($query);
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h3 class="card-title fw-bold m-0 fs-4">Edit Profil Admin</h3>
            </div>
            <div class="card-body-custom">
                
                <form action="proses_profil.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="foto_lama" value="<?php echo $data['foto_profil']; ?>">

                    <div class="text-center mb-4">
                        <?php if($data['foto_profil'] != "") { ?>
                            <img src="../assets/foto_profil/<?php echo $data['foto_profil']; ?>" class="rounded-circle border border-3 border-light shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                        <?php } else { ?>
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto text-primary fw-bold fs-1" style="width: 120px; height: 120px;">
                                <?php echo substr($username, 0, 1); ?>
                            </div>
                        <?php } ?>
                        <div class="mt-2 text-muted small">Foto Profil Saat Ini</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Username (Login)</label>
                        <input type="text" class="form-control bg-light" value="<?php echo $data['username']; ?>" readonly>
                        <div class="form-text">Username tidak bisa diubah demi keamanan.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Petugas / Admin</label>
                        <input type="text" name="nama_petugas" class="form-control" value="<?php echo $data['nama_petugas']; ?>" placeholder="Masukkan Nama Lengkap" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Ganti Foto Profil</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text text-muted">Format: JPG/PNG. Maks 2MB.</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="index.php" class="btn btn-light me-2 fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>