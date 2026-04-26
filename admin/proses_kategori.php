<?php
/**
 * proses_kategori.php (Admin)
 * Memproses tambah, edit, dan hapus kategori
 */
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
cek_peran('admin');

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

switch ($aksi) {
    case 'tambah':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') break;
        $nama = trim($_POST['nama_kategori'] ?? '');
        if (empty($nama)) {
            set_flash('error', 'Nama kategori wajib diisi.');
            break;
        }
        try {
            $stmt = $pdo->prepare("INSERT INTO kategori (nama_kategori) VALUES (:nama)");
            $stmt->execute(['nama' => $nama]);
            set_flash('success', 'Kategori berhasil ditambahkan.');
        } catch (PDOException $e) {
            set_flash('error', 'Gagal menambahkan kategori.');
        }
        break;

    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') break;
        $id   = (int)($_POST['id'] ?? 0);
        $nama = trim($_POST['nama_kategori'] ?? '');
        if ($id <= 0 || empty($nama)) {
            set_flash('error', 'Data tidak valid.');
            break;
        }
        try {
            $stmt = $pdo->prepare("UPDATE kategori SET nama_kategori = :nama WHERE id = :id");
            $stmt->execute(['nama' => $nama, 'id' => $id]);
            set_flash('success', 'Kategori berhasil diperbarui.');
        } catch (PDOException $e) {
            set_flash('error', 'Gagal memperbarui kategori.');
        }
        break;

    case 'hapus':
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            set_flash('error', 'Kategori tidak valid.');
            break;
        }
        // Cek apakah masih ada pengaduan
        $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM pengaduan WHERE kategori_id = :id");
        $stmt->execute(['id' => $id]);
        if ($stmt->fetch()['cnt'] > 0) {
            set_flash('error', 'Tidak dapat menghapus kategori yang masih memiliki pengaduan.');
            break;
        }
        try {
            $stmt = $pdo->prepare("DELETE FROM kategori WHERE id = :id");
            $stmt->execute(['id' => $id]);
            set_flash('success', 'Kategori berhasil dihapus.');
        } catch (PDOException $e) {
            set_flash('error', 'Gagal menghapus kategori.');
        }
        break;

    default:
        set_flash('error', 'Aksi tidak dikenal.');
}

header('Location: /admin/kelola_kategori.php');
exit;
