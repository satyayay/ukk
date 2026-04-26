<?php
/**
 * hapus_pengguna.php (Admin)
 * Menghapus pengguna dari sistem
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
cek_peran('admin');

$id = (int)($_GET['id'] ?? 0);

// Jangan hapus diri sendiri
if ($id === $_SESSION['user_id']) {
    set_flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    header('Location: /admin/kelola_pengguna.php');
    exit;
}

if ($id <= 0) {
    set_flash('error', 'Pengguna tidak valid.');
    header('Location: /admin/kelola_pengguna.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM pengguna WHERE id = :id");
    $stmt->execute(['id' => $id]);
    set_flash('success', 'Pengguna berhasil dihapus.');
} catch (PDOException $e) {
    set_flash('error', 'Gagal menghapus pengguna. Mungkin masih memiliki data terkait.');
}

header('Location: /admin/kelola_pengguna.php');
exit;
