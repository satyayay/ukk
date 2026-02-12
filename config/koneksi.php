<?php
// DATA DARI SCREENSHOT KAMU
$host = "sql202.infinityfree.com";  // Sesuai kolom MySQL Host Name
$user = "if0_41141695";             // Sesuai kolom MySQL User Name
$pass = "GANTI_DENGAN_PASSWORD_PANEL_KAMU"; // Password akun InfinityFree (Cek di Client Area)
$db   = "if0_41141695_pengaduan";   // Sesuai kolom MySQL DB Name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Gagal terhubung: " . mysqli_connect_error());
}

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}
?>