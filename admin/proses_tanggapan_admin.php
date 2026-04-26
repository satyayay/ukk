<?php
/**
 * proses_tanggapan_admin.php
 * Admin mengirim tanggapan pada pengaduan
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
cek_peran('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/semua_pengaduan.php');
    exit;
}

$pengaduan_id  = (int)($_POST['pengaduan_id'] ?? 0);
$isi_tanggapan = trim($_POST['isi_tanggapan'] ?? '');

if ($pengaduan_id <= 0 || empty($isi_tanggapan)) {
    set_flash('error', 'Tanggapan tidak boleh kosong.');
    header("Location: /admin/detail_pengaduan.php?id={$pengaduan_id}");
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

header("Location: /admin/detail_pengaduan.php?id={$pengaduan_id}");
exit;
