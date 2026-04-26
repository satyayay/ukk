<?php
/**
 * koneksi.php
 * Konfigurasi koneksi database menggunakan PDO
 * Wajib menggunakan PDO untuk keamanan dari SQL Injection
 */

$host   = 'localhost';
$dbname = 'e_pengaduan';
$user   = 'root';
$pass   = '';

try {
    // Membuat koneksi PDO dengan opsi keamanan
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,     // Aktifkan exception untuk error
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Default fetch mode: associative array
            PDO::ATTR_EMULATE_PREPARES   => false,                      // Gunakan prepared statement native
        ]
    );
} catch (PDOException $e) {
    // Tampilkan pesan error yang aman (tanpa detail teknis di production)
    die('<div style="text-align:center;padding:50px;font-family:sans-serif;">
        <h2>Koneksi Database Gagal</h2>
        <p>Silakan periksa konfigurasi database Anda.</p>
    </div>');
}

// Mulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
