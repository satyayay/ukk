<?php
/**
 * proses_assign.php (Admin)
 * Menugaskan petugas ke pengaduan
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
cek_peran('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/semua_pengaduan.php');
    exit;
}

$pengaduan_id = (int)($_POST['pengaduan_id'] ?? 0);
$petugas_id   = (int)($_POST['petugas_id'] ?? 0);
$redirect     = $_POST['redirect'] ?? 'list';

if ($pengaduan_id <= 0 || $petugas_id <= 0) {
    set_flash('error', 'Data tidak valid.');
    header('Location: /admin/semua_pengaduan.php');
    exit;
}

try {
    // Update petugas dan set status ke diproses jika masih menunggu
    $stmt = $pdo->prepare("UPDATE pengaduan SET petugas_id = :pid, status = CASE WHEN status = 'menunggu' THEN 'diproses' ELSE status END WHERE id = :id");
    $stmt->execute(['pid' => $petugas_id, 'id' => $pengaduan_id]);

    set_flash('success', 'Petugas berhasil ditugaskan.');
} catch (PDOException $e) {
    set_flash('error', 'Gagal menugaskan petugas.');
}

if ($redirect === 'detail') {
    header("Location: /admin/detail_pengaduan.php?id={$pengaduan_id}");
} else {
    header('Location: /admin/semua_pengaduan.php');
}
exit;
