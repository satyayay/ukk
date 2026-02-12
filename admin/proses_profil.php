<?php
session_start();
include '../config/koneksi.php';

$username = $_SESSION['username'];
$nama = $_POST['nama_petugas'];
$foto_lama = $_POST['foto_lama'];

$foto = $_FILES['foto']['name'];
$tmp = $_FILES['foto']['tmp_name'];

// 1. Jika user upload foto baru
if($foto != "") {
    $acak = rand(100, 9999);
    $nama_baru = $acak . "-admin-" . $foto;
    
    // Pastikan folder ada
    if (!file_exists('../assets/foto_profil')) {
        mkdir('../assets/foto_profil', 0777, true);
    }

    move_uploaded_file($tmp, '../assets/foto_profil/' . $nama_baru);
    
    // Hapus foto lama
    if($foto_lama != "" && file_exists("../assets/foto_profil/".$foto_lama)){
        unlink("../assets/foto_profil/".$foto_lama);
    }
    
    $query = "UPDATE admin SET nama_petugas='$nama', foto_profil='$nama_baru' WHERE username='$username'";
} else {
    // 2. Jika tidak ganti foto
    $query = "UPDATE admin SET nama_petugas='$nama' WHERE username='$username'";
}

$run = mysqli_query($conn, $query);

if($run) {
    // Update Session Nama
    $_SESSION['nama'] = $nama;
    
    echo "<script>
            alert('Profil Admin Berhasil Diperbarui!');
            window.location='profil.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal Update Profil!');
            window.location='profil.php';
          </script>";
}
?>