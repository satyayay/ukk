<?php
session_start();
// Cek Login Siswa
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_siswa") {
    header("location:../index.php?pesan=belum_login");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';

$nis = $_SESSION['username'];

// Ambil data laporan KHUSUS milik siswa yang login
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

<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h3 class="card-title fw-bold m-0 fs-4">Riwayat Laporan Saya</h3>
                <a href="tulis_pengaduan.php" class="btn btn-sm btn-primary fw-bold">
                    <i class="bi bi-plus-lg"></i> Buat Laporan
                </a>
            </div>
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th class="ps-4" width="50">No</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Isi Laporan</th>
                                <th>Status</th>
                                <th>Tanggapan</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($data = mysqli_fetch_array($query)) { 
                            ?>
                            <tr>
                                <td class="ps-4"><?php echo $no++; ?></td>
                                <td><span class="badge bg-light text-dark"><?php echo $data['tgl_pengaduan']; ?></span></td>
                                <td><span class="fw-bold text-dark"><?php echo $data['ket_kategori']; ?></span></td>
                                <td>
                                    <p class="text-dark fw-bold mb-1"><?php echo $data['lokasi']; ?></p>
                                    <small class="text-muted d-block text-truncate" style="max-width: 250px;">
                                        <?php echo $data['ket']; ?>
                                    </small>
                                    <?php if($data['foto'] != "") { ?>
                                        <a href="../assets/foto_bukti/<?php echo $data['foto']; ?>" target="_blank" class="btn btn-sm btn-light-primary mt-2 py-1 px-2 fs-8">
                                            <i class="bi bi-image"></i> Lihat Foto
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php 
                                    if($data['status'] == 'selesai') { 
                                        echo '<span class="badge bg-success">Selesai</span>'; 
                                    } elseif($data['status'] == 'proses') { 
                                        echo '<span class="badge bg-primary">Diproses</span>'; 
                                    } else { 
                                        echo '<span class="badge bg-secondary">Menunggu</span>'; 
                                    } 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if ($data['feedback'] != null) {
                                        echo "<div class='alert alert-secondary border-0 m-0 p-2 fs-7'>".$data['feedback']."</div>";
                                    } else {
                                        echo "<span class='text-muted fs-7'>-</span>";
                                    }
                                    ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if($data['status'] == '0' || $data['status'] == null) { ?>
                                        <a href="edit_pengaduan.php?id=<?php echo $data['id_pelaporan']; ?>" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm" title="Edit Laporan">
                                            <i class="bi bi-pencil-square fs-5 text-warning"></i>
                                        </a>
                                    <?php } else { ?>
                                        <span class="badge bg-light text-muted" title="Laporan sudah diproses tidak bisa diedit">Terkunci</span>
                                    <?php } ?>
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

<?php include '../layouts/footer.php'; ?>