<?php
/**
 * proses_login.php
 * Memproses data login dan melakukan autentikasi
 * Menggunakan password_verify() untuk keamanan password
 */
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/functions.php';

// Hanya terima metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input tidak kosong
if (empty($username) || empty($password)) {
    set_flash('error', 'Username dan password wajib diisi.');
    header('Location: /index.php');
    exit;
}

try {
    // Query menggunakan prepared statement (PDO) untuk mencegah SQL Injection
    $stmt = $pdo->prepare("SELECT id, nama, username, password, peran FROM pengguna WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Verifikasi password menggunakan bcrypt
    if ($user && password_verify($password, $user['password'])) {
        // Set session data
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['nama']     = $user['nama'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['peran']    = $user['peran'];

        // Redirect berdasarkan peran pengguna
        header('Location: /dashboard.php');
        exit;
    } else {
        set_flash('error', 'Username atau password salah.');
        header('Location: /index.php');
        exit;
    }
} catch (PDOException $e) {
    set_flash('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
    header('Location: /index.php');
    exit;
}
