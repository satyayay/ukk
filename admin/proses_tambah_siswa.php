<?php
session_start();
include '../config/koneksi.php';

$nis = $_POST['nis'];
$nama = $_POST['nama_siswa'];
$kelas = $_POST['kelas'];

// Cek apakah NIS sudah ada?
$cek = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");
if(mysqli_num_rows($cek) > 0){
    echo "<script>alert('NIS sudah terdaftar!'); window.location='tambah_siswa.php';</script>";
    die();
}

$query = mysqli_query($conn, "INSERT INTO siswa (nis, nama_siswa, kelas) VALUES ('$nis', '$nama', '$kelas')");

if($query) {
    echo "<script>alert('Siswa Berhasil Ditambahkan'); window.location='siswa.php';</script>";
} else {
    echo "<script>alert('Gagal Menambah Data'); window.location='tambah_siswa.php';</script>";
}
?>