<?php
/**
 * proses_edit_pengguna.php (Admin)
 * Memproses perubahan data pengguna
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
cek_peran('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/kelola_pengguna.php');
    exit;
}

$id       = (int)($_POST['id'] ?? 0);
$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$peran    = $_POST['peran'] ?? '';
$password = $_POST['password'] ?? '';

$allowed_peran = ['admin', 'petugas', 'pelapor'];

if ($id <= 0 || empty($nama) || empty($username) || !in_array($peran, $allowed_peran)) {
    set_flash('error', 'Data tidak valid.');
    header("Location: /admin/edit_pengguna.php?id={$id}");
    exit;
}

// Cek username unik (kecuali milik user ini sendiri)
$stmt = $pdo->prepare("SELECT id FROM pengguna WHERE username = :u AND id != :id");
$stmt->execute(['u' => $username, 'id' => $id]);
if ($stmt->fetch()) {
    set_flash('error', 'Username sudah digunakan pengguna lain.');
    header("Location: /admin/edit_pengguna.php?id={$id}");
    exit;
}

try {
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE pengguna SET nama = :nama, username = :username, email = :email, peran = :peran, password = :password WHERE id = :id");
        $stmt->execute(['nama' => $nama, 'username' => $username, 'email' => $email ?: null, 'peran' => $peran, 'password' => $hashed, 'id' => $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE pengguna SET nama = :nama, username = :username, email = :email, peran = :peran WHERE id = :id");
        $stmt->execute(['nama' => $nama, 'username' => $username, 'email' => $email ?: null, 'peran' => $peran, 'id' => $id]);
    }

    set_flash('success', 'Data pengguna berhasil diperbarui.');
    header('Location: /admin/kelola_pengguna.php');
    exit;
} catch (PDOException $e) {
    set_flash('error', 'Gagal memperbarui data.');
    header("Location: /admin/edit_pengguna.php?id={$id}");
    exit;
}
