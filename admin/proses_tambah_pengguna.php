<?php
/**
 * proses_tambah_pengguna.php (Admin)
 * Memproses penambahan pengguna baru
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
cek_peran('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/kelola_pengguna.php');
    exit;
}

$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$peran    = $_POST['peran'] ?? '';
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';

// Validasi
$allowed_peran = ['admin', 'petugas', 'pelapor'];
if (empty($nama) || empty($username) || empty($peran) || empty($password)) {
    set_flash('error', 'Semua field wajib diisi.');
    header('Location: /admin/tambah_pengguna.php');
    exit;
}

if (!in_array($peran, $allowed_peran)) {
    set_flash('error', 'Peran tidak valid.');
    header('Location: /admin/tambah_pengguna.php');
    exit;
}

if ($password !== $konfirmasi) {
    set_flash('error', 'Konfirmasi password tidak cocok.');
    header('Location: /admin/tambah_pengguna.php');
    exit;
}

// Cek username unik
$stmt = $pdo->prepare("SELECT id FROM pengguna WHERE username = :u");
$stmt->execute(['u' => $username]);
if ($stmt->fetch()) {
    set_flash('error', 'Username sudah digunakan.');
    header('Location: /admin/tambah_pengguna.php');
    exit;
}

try {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO pengguna (nama, username, password, peran, email) VALUES (:nama, :username, :password, :peran, :email)");
    $stmt->execute([
        'nama'     => $nama,
        'username' => $username,
        'password' => $hashed,
        'peran'    => $peran,
        'email'    => $email ?: null,
    ]);

    set_flash('success', 'Pengguna berhasil ditambahkan.');
    header('Location: /admin/kelola_pengguna.php');
    exit;
} catch (PDOException $e) {
    set_flash('error', 'Gagal menambahkan pengguna.');
    header('Location: /admin/tambah_pengguna.php');
    exit;
}
