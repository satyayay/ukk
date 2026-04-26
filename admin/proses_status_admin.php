<?php
/**
 * proses_status_admin.php
 * Admin mengubah status pengaduan
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
cek_peran('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/semua_pengaduan.php');
    exit;
}

$pengaduan_id = (int)($_POST['pengaduan_id'] ?? 0);
$status       = $_POST['status'] ?? '';

$allowed = ['menunggu', 'diproses', 'selesai'];
if ($pengaduan_id <= 0 || !in_array($status, $allowed)) {
    set_flash('error', 'Data tidak valid.');
    header('Location: /admin/semua_pengaduan.php');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE pengaduan SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $pengaduan_id]);
    set_flash('success', 'Status berhasil diperbarui.');
} catch (PDOException $e) {
    set_flash('error', 'Gagal memperbarui status.');
}

header("Location: /admin/detail_pengaduan.php?id={$pengaduan_id}");
exit;
