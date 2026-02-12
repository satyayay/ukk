<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_admin") {
    header("location:../index.php");
    die();
}

include '../config/koneksi.php';
include '../layouts/header.php';

$query = mysqli_query($conn, "SELECT * FROM siswa ORDER BY kelas ASC, nama_siswa ASC");
?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h3 class="card-title fw-bold m-0 fs-4">Akun Siswa</h3>
                <a href="tambah_siswa.php" class="btn btn-sm btn-primary fw-bold">
                    <i class="bi bi-plus-lg"></i> Tambah Akun Siswa
                </a>
            </div>
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th class="ps-4" width="50">No</th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
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
                                <td><span class="badge bg-light text-dark fw-bold"><?php echo $data['nis']; ?></span></td>
                                <td class="fw-bold text-dark"><?php echo $data['nama_siswa']; ?></td>
                                <td><span class="badge bg-light-primary text-primary"><?php echo $data['kelas']; ?></span></td>
                                <td class="text-end pe-4">
                                    <a href="edit_siswa.php?nis=<?php echo $data['nis']; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="hapus_siswa.php?nis=<?php echo $data['nis']; ?>" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" onclick="return confirm('Hapus siswa ini? Semua laporan yang dibuat siswa ini juga akan terhapus.')" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
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