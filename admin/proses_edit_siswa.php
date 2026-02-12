<?php
session_start();
include '../config/koneksi.php';

$nis = $_POST['nis'];
$nama = $_POST['nama_siswa'];
$kelas = $_POST['kelas'];

$query = mysqli_query($conn, "UPDATE siswa SET nama_siswa='$nama', kelas='$kelas' WHERE nis='$nis'");

if($query) {
    echo "<script>alert('Data Siswa Berhasil Diupdate'); window.location='siswa.php';</script>";
} else {
    echo "<script>alert('Gagal Update Data'); window.location='edit_siswa.php?nis=$nis';</script>";
}
?>