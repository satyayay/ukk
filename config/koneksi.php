<?php
// KONFIGURASI UNTUK LARAGON (LOCALHOST)
$host = "localhost";
$user = "root";       // User default Laragon
$pass = "";           // PASSWORD HARUS KOSONG (Default Laragon)
$db   = "pengaduan";  // Pastikan nama database di HeidiSQL/phpMyAdmin benar

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Gagal Terhubung: " . mysqli_connect_error());
}

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }
    return $rows;
}
?>