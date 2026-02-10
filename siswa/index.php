<?php
session_start();
// Cek apakah siswa sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_siswa") {
    header("location:../index.php?pesan=belum_login");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';

// Ambil NIS siswa yang sedang login
$nis = $_SESSION['username'];

// Query canggih: Gabungkan Tabel Laporan + Kategori + Aspirasi (Respon)
// Kita pakai LEFT JOIN di aspirasi supaya laporan yang BELUM DIRESPON tetap muncul
$query = mysqli_query($conn, "SELECT 
            input_aspirasi.*, 
            kategori.ket_kategori, 
            aspirasi.status, 
            aspirasi.feedback 
        FROM input_aspirasi 
        JOIN kategori ON input_aspirasi.id_kategori = kategori.id_kategori
        LEFT JOIN aspirasi ON input_aspirasi.id_pelaporan = aspirasi.id_pelaporan
        WHERE input_aspirasi.nis='$nis'
        ORDER BY input_aspirasi.tgl_pengaduan DESC");
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>👋 Halo, <?php echo $_SESSION['nama']; ?></h3>
                <a href="tulis_pengaduan.php" class="btn btn-primary shadow">
                    + Tulis Laporan Baru
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">Riwayat Pengaduan Saya</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Isi Laporan</th>
                                    <th>Status</th>
                                    <th>Tanggapan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($data = mysqli_fetch_array($query)) { 
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $data['tgl_pengaduan']; ?></td>
                                    <td><?php echo $data['ket_kategori']; ?></td>
                                    <td>
                                        <p class="mb-1 fw-bold"><?php echo $data['lokasi']; ?></p>
                                        <small class="text-muted"><?php echo $data['ket']; ?></small>
                                        
                                        <?php if($data['foto'] != "") { ?>
                                            <br>
                                            <img src="../assets/foto_bukti/<?php echo $data['foto']; ?>" width="80" class="mt-2 rounded border">
                                        <?php } ?>
                                    </td>
                                    
                                    <td>
                                        <?php if($data['status'] == 'selesai') { ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php } elseif($data['status'] == 'proses') { ?>
                                            <span class="badge bg-primary">Diproses</span>
                                        <?php } else { ?>
                                            <span class="badge bg-danger">Menunggu</span>
                                        <?php } ?>
                                    </td>
                                    
                                    <td>
                                        <?php 
                                        if ($data['feedback'] != null) {
                                            echo $data['feedback'];
                                        } else {
                                            echo "<i class='text-muted'>Belum ada tanggapan</i>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>