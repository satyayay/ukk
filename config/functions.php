<?php
// Panggil koneksi sekali di sini, jadi file lain cukup panggil functions.php
include 'koneksi.php';

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Nanti kita tambah fungsi tambah() dan hapus() di sini
?>