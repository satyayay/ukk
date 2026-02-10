<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_admin") {
    header("location:../index.php");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';

// Tangkap ID dari URL
$id = $_GET['id'];

// Ambil data detail laporan + tanggapannya (kalau ada)
$query = mysqli_query($conn, "SELECT 
            input_aspirasi.*, 
            siswa.nama_siswa, 
            kategori.ket_kategori, 
            aspirasi.status, 
            aspirasi.feedback,
            aspirasi.id_aspirasi
        FROM input_aspirasi 
        JOIN siswa ON input_aspirasi.nis = siswa.nis
        JOIN kategori ON input_aspirasi.id_kategori = kategori.id_kategori
        LEFT JOIN aspirasi ON input_aspirasi.id_pelaporan = aspirasi.id_pelaporan
        WHERE input_aspirasi.id_pelaporan='$id'");

$data = mysqli_fetch_array($query);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📋 Detail Laporan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Pelapor</th>
                            <td>: <b><?php echo $data['nama_siswa']; ?></b> (<?php echo $data['nis']; ?>)</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: <?php echo $data['tgl_pengaduan']; ?></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>: <?php echo $data['ket_kategori']; ?></td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>: <?php echo $data['lokasi']; ?></td>
                        </tr>
                        <tr>
                            <th>Isi Laporan</th>
                            <td>: <?php echo $data['ket']; ?></td>
                        </tr>
                        <?php if($data['foto'] != "") { ?>
                        <tr>
                            <th>Bukti Foto</th>
                            <td>: <img src="../assets/foto_bukti/<?php echo $data['foto']; ?>" width="100%" class="rounded"></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <a href="index.php" class="btn btn-secondary mt-3">&laquo; Kembali</a>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">✍️ Berikan Tanggapan</h5>
                </div>
                <div class="card-body">
                    <form action="proses_tanggapan.php" method="POST">
                        
                        <input type="hidden" name="id_pelaporan" value="<?php echo $data['id_pelaporan']; ?>">
                        <input type="hidden" name="id_kategori" value="<?php echo $data['id_kategori']; ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Update Status</label>
                            <select name="status" class="form-select" required>
                                <option value="proses" <?php if($data['status']=='proses') echo 'selected'; ?>>Sedang Proses</option>
                                <option value="selesai" <?php if($data['status']=='selesai') echo 'selected'; ?>>Selesai</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggapan / Feedback</label>
                            <textarea name="feedback" class="form-control" rows="5" required placeholder="Tulis tanggapan untuk siswa..."><?php echo $data['feedback']; ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Simpan Tanggapan</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>