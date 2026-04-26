<?php
/**
 * functions.php
 * Fungsi-fungsi helper yang digunakan di seluruh sistem
 */

/**
 * Cek apakah user sudah login
 * Redirect ke halaman login jika belum
 */
function cek_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
}

/**
 * Cek apakah user memiliki peran tertentu
 * @param string|array $peran - Peran yang diizinkan
 */
function cek_peran($peran) {
    cek_login();
    if (is_string($peran)) {
        $peran = [$peran];
    }
    if (!in_array($_SESSION['peran'], $peran)) {
        header('Location: /dashboard.php');
        exit;
    }
}

/**
 * Escape output HTML untuk mencegah XSS
 * @param string $string
 * @return string
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Format tanggal Indonesia
 * @param string $datetime
 * @return string
 */
function format_tanggal($datetime) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $dt = new DateTime($datetime);
    return $dt->format('d') . ' ' . $bulan[(int)$dt->format('m')] . ' ' . $dt->format('Y, H:i');
}

/**
 * Mendapatkan class CSS untuk label status
 * @param string $status
 * @return string
 */
function status_class($status) {
    switch ($status) {
        case 'menunggu': return 'status-menunggu';
        case 'diproses': return 'status-diproses';
        case 'selesai':  return 'status-selesai';
        default:         return '';
    }
}

/**
 * Mendapatkan label teks status
 * @param string $status
 * @return string
 */
function status_label($status) {
    switch ($status) {
        case 'menunggu': return 'Menunggu';
        case 'diproses': return 'Diproses';
        case 'selesai':  return 'Selesai';
        default:         return ucfirst($status);
    }
}

/**
 * Mendapatkan label peran
 * @param string $peran
 * @return string
 */
function peran_label($peran) {
    switch ($peran) {
        case 'admin':    return 'Administrator';
        case 'petugas':  return 'Petugas';
        case 'pelapor':  return 'Pelapor';
        default:         return ucfirst($peran);
    }
}

/**
 * Set pesan flash untuk ditampilkan sekali
 * @param string $type - success, error, warning, info
 * @param string $message
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Tampilkan pesan flash (jika ada) dan hapus dari session
 * @return string HTML pesan flash
 */
function tampilkan_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $type = e($flash['type']);
        $message = e($flash['message']);
        return "<div class=\"alert alert-{$type}\">{$message}</div>";
    }
    return '';
}
