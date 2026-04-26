<?php
/**
 * proses_pengaduan.php
 * Memproses data form pengaduan baru dari Pelapor
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
cek_peran('pelapor');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pelapor/buat_pengaduan.php');
    exit;
}

$judul       = trim($_POST['judul'] ?? '');
$kategori_id = (int)($_POST['kategori_id'] ?? 0);
$deskripsi   = trim($_POST['deskripsi'] ?? '');

// Validasi input
if (empty($judul) || $kategori_id <= 0 || empty($deskripsi)) {
    set_flash('error', 'Semua field wajib diisi.');
    header('Location: /pelapor/buat_pengaduan.php');
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO pengaduan (user_id, kategori_id, judul, deskripsi, status)
                           VALUES (:user_id, :kategori_id, :judul, :deskripsi, 'menunggu')");
    $stmt->execute([
        'user_id'     => $_SESSION['user_id'],
        'kategori_id' => $kategori_id,
        'judul'       => $judul,
        'deskripsi'   => $deskripsi,
    ]);

    set_flash('success', 'Pengaduan berhasil dikirim! Anda akan mendapat tanggapan segera.');
    header('Location: /pelapor/riwayat.php');
    exit;
} catch (PDOException $e) {
    set_flash('error', 'Gagal menyimpan pengaduan. Silakan coba lagi.');
    header('Location: /pelapor/buat_pengaduan.php');
    exit;
}
