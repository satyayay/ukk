<?php
session_start();
include 'config/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];
$level    = $_POST['level'];

if ($level == 'siswa') {
    // LOGIKA SISWA
    $login = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$username'");
    $cek = mysqli_num_rows($login);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($login);
        
        $_SESSION['username'] = $username; // NIS
        $_SESSION['nama'] = $data['nama_siswa']; // Nama Asli
        $_SESSION['status'] = "login_siswa";
        
        header("location:siswa/index.php");
    } else {
        header("location:index.php?pesan=gagal");
    }

} elseif ($level == 'admin') {
    // LOGIKA ADMIN
    $login = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($login);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($login);
        
        $_SESSION['username'] = $username;
        
        // Cek apakah punya nama_petugas? Kalau tidak, pakai username
        if($data['nama_petugas'] == "" || $data['nama_petugas'] == null){
            $_SESSION['nama'] = $username;
        } else {
            $_SESSION['nama'] = $data['nama_petugas'];
        }

        $_SESSION['status'] = "login_admin";
        
        header("location:admin/index.php");
    } else {
        header("location:index.php?pesan=gagal");
    }
}
?>