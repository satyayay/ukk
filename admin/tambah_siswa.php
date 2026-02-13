<?php
session_start();

// 1. PANGGIL KONEKSI DULUAN (Wajib paling atas)
include '../config/koneksi.php';

// 2. BARU PANGGIL HEADER (Supaya header bisa baca koneksi)
include '../layouts/header.php';

// Cek apakah tombol simpan ditekan
if (isset($_POST['submit'])) {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    // Default password sama dengan NIS
    $password = md5($nis); 
    $telp = $_POST['telp']; // Jika ada kolom telp
    
    // Cek NIS duplikat
    $cek = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('NIS sudah terdaftar!');</script>";
    } else {
        // Query Simpan
        $query = mysqli_query($conn, "INSERT INTO siswa (nis, nama, password, telp, kelas) VALUES ('$nis', '$nama', '$password', '$telp', '$kelas')");
        if ($query) {
            echo "<script>alert('Data Berhasil Disimpan'); window.location='siswa.php';</script>";
        } else {
            echo "<script>alert('Gagal Menyimpan');</script>";
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold">Tambah Siswa Baru</h4>
                </div>
                <div class="card-body p-4">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIS</label>
                            <input type="number" name="nis" class="form-control" placeholder="Masukkan NIS" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama Siswa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kelas</label>
                            <input type="text" name="kelas" class="form-control" placeholder="Contoh: XII RPL 1" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">No. Telepon</label>
                            <input type="number" name="telp" class="form-control" placeholder="08..." required>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="siswa.php" class="btn btn-light fw-bold">Batal</a>
                            <button type="submit" name="submit" class="btn btn-primary fw-bold">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>