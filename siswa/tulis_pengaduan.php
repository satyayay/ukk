<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_siswa") {
    header("location:../index.php");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📝 Tulis Laporan Kerusakan</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="proses_simpan.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Pengaduan</label>
                            <input type="text" class="form-control" value="<?php echo date('d-m-Y'); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Kategori</label>
                            <select name="id_kategori" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                                while($k = mysqli_fetch_array($kategori)){
                                    echo "<option value='".$k['id_kategori']."'>".$k['ket_kategori']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi Kejadian</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lab Komputer 1, WC Lantai 2" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Isi Keluhan</label>
                            <textarea name="ket" class="form-control" rows="4" placeholder="Jelaskan kerusakan secara detail..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bukti Foto (Opsional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <div class="form-text">Format: JPG, JPEG, PNG. Maks 2MB.</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-success px-4">Kirim Laporan</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>