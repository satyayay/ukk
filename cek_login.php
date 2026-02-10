<?php
session_start();
include 'config/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];
$level    = $_POST['level'];

if ($level == 'siswa') {
    // LOGIKA SISWA
    // Cek apakah NIS ada di database siswa
    $login = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$username'");
    $cek = mysqli_num_rows($login);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($login);
        
        // Buat Session Siswa
        $_SESSION['username'] = $username; // Isinya NIS
        $_SESSION['nama'] = $data['nama_lengkap']; // Ambil nama asli
        $_SESSION['status'] = "login_siswa";
        
        // Kirim ke Dashboard Siswa
        header("location:siswa/index.php");
    } else {
        // Gagal
        header("location:index.php?pesan=gagal");
    }

} elseif ($level == 'admin') {
    // LOGIKA ADMIN
    // Cek Username DAN Password
    $login = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($login);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($login);
        
        // Buat Session Admin
        $_SESSION['username'] = $username;
        $_SESSION['nama'] = $username; // Karena tabel admin ga ada kolom nama lengkap, pake username aja
        $_SESSION['status'] = "login_admin";
        
        // Kirim ke Dashboard Admin
        header("location:admin/index.php");
    } else {
        // Gagal
        header("location:index.php?pesan=gagal");
    }
}
?>