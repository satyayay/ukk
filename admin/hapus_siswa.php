<?php
session_start();
include '../config/koneksi.php';

$nis = $_GET['nis'];

// 1. Hapus Foto-foto Bukti Laporan Siswa Ini
$get_foto = mysqli_query($conn, "SELECT foto FROM input_aspirasi WHERE nis='$nis'");
while($f = mysqli_fetch_array($get_foto)){
    if($f['foto'] != "" && file_exists("../assets/foto_bukti/".$f['foto'])){
        unlink("../assets/foto_bukti/".$f['foto']);
    }
}

// 2. Hapus Data Tanggapan (Aspirasi) yang terkait dengan laporan siswa ini
$get_id = mysqli_query($conn, "SELECT id_pelaporan FROM input_aspirasi WHERE nis='$nis'");
while($row = mysqli_fetch_array($get_id)){
    $id_pel = $row['id_pelaporan'];
    mysqli_query($conn, "DELETE FROM aspirasi WHERE id_pelaporan='$id_pel'");
}

// 3. Hapus Laporan (Input Aspirasi)
mysqli_query($conn, "DELETE FROM input_aspirasi WHERE nis='$nis'");

// 4. Terakhir, Hapus Akun Siswanya
$query = mysqli_query($conn, "DELETE FROM siswa WHERE nis='$nis'");

if($query) {
    echo "<script>alert('Data Siswa & Riwayat Laporannya Berhasil Dihapus'); window.location='siswa.php';</script>";
} else {
    echo "<script>alert('Gagal Menghapus Data'); window.location='siswa.php';</script>";
}
?>