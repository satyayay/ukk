<?php
session_start();
include '../config/koneksi.php';

$id_pelaporan = $_POST['id_pelaporan'];
$id_kategori = $_POST['id_kategori'];
$status = $_POST['status'];
$feedback = $_POST['feedback'];
// Kita kosongkan id_admin dulu atau set 1 (sesuai dummy), karena tabel admin belum ada session id-nya yang fix
// Tapi di tabel aspirasi butuh id_admin? Cek struktur database. 
// Kalau di database aspirasi ga ada id_admin, hapus saja bagian itu.
// Asumsi: Kita abaikan kolom id_admin dulu biar simpel, atau isi default '1' (Admin Utama).

// CEK: Apakah laporan ini sudah pernah dibalas?
$cek = mysqli_query($conn, "SELECT * FROM aspirasi WHERE id_pelaporan='$id_pelaporan'");

if(mysqli_num_rows($cek) > 0) {
    // KALAU SUDAH ADA -> UPDATE
    $query = mysqli_query($conn, "UPDATE aspirasi SET 
        status='$status', 
        feedback='$feedback' 
        WHERE id_pelaporan='$id_pelaporan'");
} else {
    // KALAU BELUM ADA -> INSERT
    $query = mysqli_query($conn, "INSERT INTO aspirasi 
        (id_pelaporan, id_kategori, status, feedback) 
        VALUES 
        ('$id_pelaporan', '$id_kategori', '$status', '$feedback')");
}

if($query) {
    echo "<script>
            alert('Tanggapan Berhasil Disimpan!');
            window.location='index.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal Menyimpan!');
            window.location='tanggapan.php?id=$id_pelaporan';
          </script>";
}
?>