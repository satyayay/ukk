<?php
session_start();
include '../config/koneksi.php';

// Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_admin") {
    header("location:../index.php");
    die();
}

$id = $_GET['id'];

// 1. Ambil nama foto dulu sebelum dihapus datanya
$cek = mysqli_query($conn, "SELECT foto FROM input_aspirasi WHERE id_pelaporan='$id'");
$data = mysqli_fetch_array($cek);
$foto_lama = $data['foto'];

// 2. Hapus file foto jika ada
if($foto_lama != "" && file_exists("../assets/foto_bukti/".$foto_lama)){
    unlink("../assets/foto_bukti/".$foto_lama);
}

// 3. Hapus data di database (Data di tabel aspirasi/tanggapan juga ikut terhapus otomatis jika pakai ON DELETE CASCADE, tapi kita hapus manual input_aspirasi saja cukup)
// Note: Kita hapus dulu tanggapannya biar bersih (opsional, tergantung struktur DB)
mysqli_query($conn, "DELETE FROM aspirasi WHERE id_pelaporan='$id'");
$query = mysqli_query($conn, "DELETE FROM input_aspirasi WHERE id_pelaporan='$id'");

if($query) {
    echo "<script>
            alert('Data Berhasil Dihapus!');
            window.location='laporan.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal Menghapus Data!');
            window.location='laporan.php';
          </script>";
}
?>