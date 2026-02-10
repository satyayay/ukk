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

<div class="container mt-4">
    <h3 class="mb-4">👋 Selamat Datang, Admin!</h3>
    
    <div class="card shadow border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0 text-primary">Daftar Laporan Masuk</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pelapor</th>
                            <th>Isi Laporan</th>
                            <th>Status</th>
                            <th>Aksi</th>
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
                            <td>
                                <b><?php echo $data['nama_siswa']; ?></b><br>
                                <small>NIS: <?php echo $data['nis']; ?></small>
                            </td>
                            <td>
                                <b><?php echo $data['ket_kategori']; ?></b><br>
                                <?php echo $data['ket']; ?><br>
                                <small class="text-muted">Lokasi: <?php echo $data['lokasi']; ?></small>
                                
                                <?php if($data['foto'] != "") { ?>
                                    <br>
                                    <a href="../assets/foto_bukti/<?php echo $data['foto']; ?>" target="_blank">
                                        <img src="../assets/foto_bukti/<?php echo $data['foto']; ?>" width="60" class="mt-1 rounded">
                                    </a>
                                <?php } ?>
                            </td>
                            
                            <td>
                                <?php if($data['status'] == 'selesai') { ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php } elseif($data['status'] == 'proses') { ?>
                                    <span class="badge bg-primary">Sedang Proses</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">Menunggu</span>
                                <?php } ?>
                            </td>

                            <td>
                                <a href="tanggapan.php?id=<?php echo $data['id_pelaporan']; ?>" class="btn btn-sm btn-primary">
                                    Tanggapi / Cek
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

<?php include '../layouts/footer.php'; ?>