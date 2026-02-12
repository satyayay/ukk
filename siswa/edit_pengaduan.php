<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_siswa") {
    header("location:../index.php");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';

$id = $_GET['id'];
$nis = $_SESSION['username'];

// Ambil data lama, pastikan yang diambil punya siswa yang login (biar gak edit punya orang lain)
$query = mysqli_query($conn, "SELECT * FROM input_aspirasi WHERE id_pelaporan='$id' AND nis='$nis'");
$data = mysqli_fetch_array($query);

// Cek apakah data ada?
if(mysqli_num_rows($query) < 1) {
    die("Data tidak ditemukan atau Anda tidak berhak mengedit ini.");
}

// Cek status lewat tabel aspirasi
$cek_status = mysqli_query($conn, "SELECT status FROM aspirasi WHERE id_pelaporan='$id'");
$status_data = mysqli_fetch_array($cek_status);
if(isset($status_data['status']) && $status_data['status'] != '0' && $status_data['status'] != '') {
    echo "<script>alert('Laporan sudah diproses, tidak bisa diedit!'); window.location='riwayat.php';</script>";
    exit;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h3 class="card-title fw-bold m-0 fs-4">Edit Laporan</h3>
            </div>
            <div class="card-body-custom">
                
                <form action="proses_update.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_pelaporan" value="<?php echo $data['id_pelaporan']; ?>">
                    <input type="hidden" name="foto_lama" value="<?php echo $data['foto']; ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Pilih Kategori</label>
                        <select name="id_kategori" class="form-select form-select-solid bg-light border-0 fw-bold" required>
                            <?php
                            $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                            while($k = mysqli_fetch_array($kategori)){
                                $selected = ($k['id_kategori'] == $data['id_kategori']) ? 'selected' : '';
                                echo "<option value='".$k['id_kategori']."' $selected>".$k['ket_kategori']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Lokasi Kejadian</label>
                        <input type="text" name="lokasi" class="form-control form-control-solid bg-light border-0 fw-bold" value="<?php echo $data['lokasi']; ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Isi Detail Laporan</label>
                        <textarea name="ket" class="form-control form-control-solid bg-light border-0 fw-bold" rows="5" required><?php echo $data['ket']; ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Bukti Foto</label>
                        <br>
                        <?php if($data['foto'] != "") { ?>
                            <img src="../assets/foto_bukti/<?php echo $data['foto']; ?>" width="100" class="mb-2 rounded">
                        <?php } ?>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti foto.</div>
                    </div>

                    <div class="d-flex justify-content-end mt-5">
                        <a href="riwayat.php" class="btn btn-light me-3 fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Perubahan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>