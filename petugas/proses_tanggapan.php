<?php
/**
 * proses_tanggapan.php (Petugas)
 * Memproses tanggapan baru dari petugas
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
cek_peran('petugas');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /petugas/daftar_pengaduan.php');
    exit;
}

$pengaduan_id   = (int)($_POST['pengaduan_id'] ?? 0);
$isi_tanggapan  = trim($_POST['isi_tanggapan'] ?? '');

if ($pengaduan_id <= 0 || empty($isi_tanggapan)) {
    set_flash('error', 'Tanggapan tidak boleh kosong.');
    header("Location: /petugas/detail_pengaduan.php?id={$pengaduan_id}");
    exit;
}

// Pastikan pengaduan ini ditugaskan ke petugas yang login
$stmt = $pdo->prepare("SELECT id FROM pengaduan WHERE id = :id AND petugas_id = :uid");
$stmt->execute(['id' => $pengaduan_id, 'uid' => $_SESSION['user_id']]);
if (!$stmt->fetch()) {
    set_flash('error', 'Anda tidak memiliki akses ke pengaduan ini.');
    header('Location: /petugas/daftar_pengaduan.php');
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO tanggapan (pengaduan_id, user_id, isi_tanggapan) VALUES (:pid, :uid, :isi)");
    $stmt->execute([
        'pid' => $pengaduan_id,
        'uid' => $_SESSION['user_id'],
        'isi' => $isi_tanggapan,
    ]);

    set_flash('success', 'Tanggapan berhasil dikirim.');
} catch (PDOException $e) {
    set_flash('error', 'Gagal mengirim tanggapan.');
}

header("Location: /petugas/detail_pengaduan.php?id={$pengaduan_id}");
exit;
