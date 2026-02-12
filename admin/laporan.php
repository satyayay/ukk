<?php
session_start();
// Cek Login Admin
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_admin") {
    header("location:../index.php?pesan=belum_login");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';

// Ambil SEMUA data laporan (Gabung tabel siswa, kategori, aspirasi)
$query = mysqli_query($conn, "SELECT 
            input_aspirasi.*, 
            siswa.nama_siswa, 
            kategori.ket_kategori, 
            aspirasi.status, 
            aspirasi.id_aspirasi 
        FROM input_aspirasi 
        JOIN siswa ON input_aspirasi.nis = siswa.nis
        JOIN kategori ON input_aspirasi.id_kategori = kategori.id_kategori
        LEFT JOIN aspirasi ON input_aspirasi.id_pelaporan = aspirasi.id_pelaporan
        ORDER BY input_aspirasi.tgl_pengaduan DESC");
?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h3 class="card-title fw-bold m-0 fs-4">Data Laporan Masuk</h3>
                <div class="card-toolbar">
                    <button class="btn btn-sm btn-light-primary fw-bold" onclick="window.print()">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                </div>
            </div>
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th class="ps-4" width="50">No</th>
                                <th>Tanggal</th>
                                <th>Pelapor</th>
                                <th>Detail Laporan</th>
                                <th>Status</th>
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
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold mb-1"><?php echo $data['nama_siswa']; ?></span>
                                        <span class="text-muted fs-7"><?php echo $data['nis']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark fw-bold"><?php echo $data['ket_kategori']; ?></div>
                                    <div class="text-muted fs-7 text-truncate" style="max-width: 200px;"><?php echo $data['ket']; ?></div>
                                    <div class="text-xs text-primary mt-1"><i class="bi bi-geo-alt-fill"></i> <?php echo $data['lokasi']; ?></div>
                                    
                                    <?php if($data['foto'] != "") { ?>
                                        <a href="../assets/foto_bukti/<?php echo $data['foto']; ?>" target="_blank" class="badge bg-light-info text-info mt-1 border-0">
                                            <i class="bi bi-image text-info"></i> Bukti
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php 
                                    $statusClass = 'bg-secondary';
                                    $statusText = 'Menunggu';
                                    
                                    if($data['status'] == 'proses') { 
                                        $statusClass = 'bg-primary'; 
                                        $statusText = 'Proses'; 
                                    } elseif($data['status'] == 'selesai') { 
                                        $statusClass = 'bg-success'; 
                                        $statusText = 'Selesai'; 
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="tanggapan.php?id=<?php echo $data['id_pelaporan']; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Tanggapi">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <a href="hapus_pengaduan.php?id=<?php echo $data['id_pelaporan']; ?>" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" onclick="return confirm('Yakin ingin menghapus laporan ini? Data yang dihapus tidak bisa dikembalikan.')" title="Hapus">
                                        <i class="bi bi-trash-fill text-danger"></i>
                                    </a>
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