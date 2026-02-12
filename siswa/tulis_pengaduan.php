<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_siswa") {
    header("location:../index.php");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h3 class="card-title fw-bold m-0 fs-4">Tulis Laporan Baru</h3>
            </div>
            <div class="card-body-custom">
                
                <form action="proses_simpan.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Pilih Kategori</label>
                        <select name="id_kategori" class="form-select form-select-solid bg-light border-0 fw-bold" required>
                            <option value="">-- Pilih Kategori Kerusakan --</option>
                            <?php
                            $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                            while($k = mysqli_fetch_array($kategori)){
                                echo "<option value='".$k['id_kategori']."'>".$k['ket_kategori']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Lokasi Kejadian</label>
                        <input type="text" name="lokasi" class="form-control form-control-solid bg-light border-0 fw-bold" placeholder="Contoh: Lab Komputer 1, WC Lantai 2" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Isi Detail Laporan</label>
                        <textarea name="ket" class="form-control form-control-solid bg-light border-0 fw-bold" rows="5" placeholder="Jelaskan kerusakan apa yang terjadi..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Bukti Foto (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                    </div>

                    <div class="d-flex justify-content-end mt-5">
                        <a href="riwayat.php" class="btn btn-light me-3 fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="bi bi-send-fill me-2"></i> Kirim Laporan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>