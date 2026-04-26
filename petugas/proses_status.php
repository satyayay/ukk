<?php
/**
 * proses_status.php (Petugas)
 * Mengubah status tiket pengaduan (Menunggu, Diproses, Selesai)
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
cek_peran('petugas');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /petugas/daftar_pengaduan.php');
    exit;
}

$pengaduan_id = (int)($_POST['pengaduan_id'] ?? 0);
$status       = $_POST['status'] ?? '';

// Validasi status yang diizinkan
$allowed_status = ['menunggu', 'diproses', 'selesai'];
if ($pengaduan_id <= 0 || !in_array($status, $allowed_status)) {
    set_flash('error', 'Data tidak valid.');
    header('Location: /petugas/daftar_pengaduan.php');
    exit;
}

// Pastikan pengaduan ditugaskan ke petugas ini
$stmt = $pdo->prepare("SELECT id FROM pengaduan WHERE id = :id AND petugas_id = :uid");
$stmt->execute(['id' => $pengaduan_id, 'uid' => $_SESSION['user_id']]);
if (!$stmt->fetch()) {
    set_flash('error', 'Anda tidak memiliki akses ke pengaduan ini.');
    header('Location: /petugas/daftar_pengaduan.php');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE pengaduan SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $pengaduan_id]);

    set_flash('success', 'Status pengaduan berhasil diperbarui menjadi "' . status_label($status) . '".');
} catch (PDOException $e) {
    set_flash('error', 'Gagal memperbarui status.');
}

header("Location: /petugas/detail_pengaduan.php?id={$pengaduan_id}");
exit;
