<?php
$conn = mysqli_connect("localhost", "root", "", "pengaduan");

if (!$conn) {
    die("Gagal terhubung: " . mysqli_connect_error());
}

// Fungsi bantu query biar kodingan di halaman lain pendek
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