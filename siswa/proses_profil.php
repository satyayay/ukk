<?php
session_start();
include '../config/koneksi.php';

$nis = $_SESSION['username'];
$nama = $_POST['nama_siswa'];
$foto_lama = $_POST['foto_lama'];

$foto = $_FILES['foto']['name'];
$tmp = $_FILES['foto']['tmp_name'];

// 1. Jika user upload foto baru
if($foto != "") {
    $acak = rand(100, 9999);
    $nama_baru = $acak . "-profil-" . $foto;
    
    // Pastikan folder assets/foto_profil sudah dibuat
    if (!file_exists('../assets/foto_profil')) {
        mkdir('../assets/foto_profil', 0777, true);
    }

    move_uploaded_file($tmp, '../assets/foto_profil/' . $nama_baru);
    
    // Hapus foto lama jika ada
    if($foto_lama != "" && file_exists("../assets/foto_profil/".$foto_lama)){
        unlink("../assets/foto_profil/".$foto_lama);
    }
    
    $query = "UPDATE siswa SET nama_siswa='$nama', foto_profil='$nama_baru' WHERE nis='$nis'";
} else {
    // 2. Jika tidak ganti foto, cuma ganti nama
    $query = "UPDATE siswa SET nama_siswa='$nama' WHERE nis='$nis'";
}

$run = mysqli_query($conn, $query);

if($run) {
    // Update Session Nama biar langsung berubah di header tanpa logout
    $_SESSION['nama'] = $nama;
    
    echo "<script>
            alert('Profil Berhasil Diperbarui!');
            window.location='profil.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal Update Profil!');
            window.location='profil.php';
          </script>";
}
?>