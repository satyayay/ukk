<?php
/**
 * dashboard.php - Dashboard utama
 * Menampilkan ringkasan pengaduan sesuai peran pengguna
 */
$page_title = 'Dashboard';
require_once __DIR__ . '/layouts/header.php';

$peran   = $_SESSION['peran'];
$user_id = $_SESSION['user_id'];

// Query statistik berdasarkan peran
if ($peran === 'admin') {
    // Admin: lihat semua pengaduan
    $stmt = $pdo->query("SELECT
        COUNT(*) as total,
        SUM(status = 'menunggu') as menunggu,
        SUM(status = 'diproses') as diproses,
        SUM(status = 'selesai') as selesai
    FROM pengaduan");
} elseif ($peran === 'petugas') {
    // Petugas: hanya pengaduan yang ditugaskan
    $stmt = $pdo->prepare("SELECT
        COUNT(*) as total,
        SUM(status = 'menunggu') as menunggu,
        SUM(status = 'diproses') as diproses,
        SUM(status = 'selesai') as selesai
    FROM pengaduan WHERE petugas_id = :uid");
    $stmt->execute(['uid' => $user_id]);
} else {
    // Pelapor: hanya pengaduan milik sendiri
    $stmt = $pdo->prepare("SELECT
        COUNT(*) as total,
        SUM(status = 'menunggu') as menunggu,
        SUM(status = 'diproses') as diproses,
        SUM(status = 'selesai') as selesai
    FROM pengaduan WHERE user_id = :uid");
    $stmt->execute(['uid' => $user_id]);
}

$stats = $stmt->fetch();

// Ambil pengaduan terbaru (5 terakhir)
if ($peran === 'admin') {
    $stmt = $pdo->query("SELECT p.*, k.nama_kategori, u.nama as nama_pelapor
        FROM pengaduan p
        JOIN kategori k ON p.kategori_id = k.id
        JOIN pengguna u ON p.user_id = u.id
        ORDER BY p.created_at DESC LIMIT 5");
} elseif ($peran === 'petugas') {
    $stmt = $pdo->prepare("SELECT p.*, k.nama_kategori, u.nama as nama_pelapor
        FROM pengaduan p
        JOIN kategori k ON p.kategori_id = k.id
        JOIN pengguna u ON p.user_id = u.id
        WHERE p.petugas_id = :uid
        ORDER BY p.created_at DESC LIMIT 5");
    $stmt->execute(['uid' => $user_id]);
} else {
    $stmt = $pdo->prepare("SELECT p.*, k.nama_kategori
        FROM pengaduan p
        JOIN kategori k ON p.kategori_id = k.id
        WHERE p.user_id = :uid
        ORDER BY p.created_at DESC LIMIT 5");
    $stmt->execute(['uid' => $user_id]);
}

$pengaduan_terbaru = $stmt->fetchAll();
?>

<div class="welcome-banner">
    <h3>Selamat datang, <?= e($_SESSION['nama']) ?></h3>
    <p>
        <?php if ($peran === 'admin'): ?>
            Anda login sebagai Administrator. Kelola seluruh pengaduan, pengguna, dan kategori dari sini.
        <?php elseif ($peran === 'petugas'): ?>
            Anda login sebagai Petugas. Lihat dan tanggapi pengaduan yang ditugaskan kepada Anda.
        <?php else: ?>
            Anda login sebagai Pelapor. Buat pengaduan baru atau pantau status pengaduan Anda.
        <?php endif; ?>
    </p>
</div>

<!-- Kartu Statistik -->
<div class="stats-grid">
    <div class="stat-card stat-total">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-number"><?= (int)$stats['total'] ?></span>
            <span class="stat-label">Total Pengaduan</span>
        </div>
    </div>

    <div class="stat-card stat-menunggu">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-number"><?= (int)$stats['menunggu'] ?></span>
            <span class="stat-label">Menunggu</span>
        </div>
    </div>

    <div class="stat-card stat-diproses-card">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-number"><?= (int)$stats['diproses'] ?></span>
            <span class="stat-label">Diproses</span>
        </div>
    </div>

    <div class="stat-card stat-selesai-card">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-number"><?= (int)$stats['selesai'] ?></span>
            <span class="stat-label">Selesai</span>
        </div>
    </div>
</div>

<!-- Tabel Pengaduan Terbaru -->
<div class="card">
    <div class="card-header">
        <h3>Pengaduan Terbaru</h3>
        <?php if ($peran === 'pelapor'): ?>
            <a href="/pelapor/buat_pengaduan.php" class="btn btn-primary btn-sm">+ Buat Pengaduan</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (empty($pengaduan_terbaru)): ?>
            <div class="empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p>Belum ada pengaduan.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <?php if ($peran !== 'pelapor'): ?><th>Pelapor</th><?php endif; ?>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pengaduan_terbaru as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td class="td-title"><?= e($row['judul']) ?></td>
                            <td><?= e($row['nama_kategori']) ?></td>
                            <?php if ($peran !== 'pelapor'): ?>
                                <td><?= e($row['nama_pelapor']) ?></td>
                            <?php endif; ?>
                            <td><span class="status-badge <?= status_class($row['status']) ?>"><?= status_label($row['status']) ?></span></td>
                            <td class="td-date"><?= format_tanggal($row['created_at']) ?></td>
                            <td>
                                <?php
                                    if ($peran === 'admin') {
                                        $detail_url = '/admin/detail_pengaduan.php?id=' . $row['id'];
                                    } elseif ($peran === 'petugas') {
                                        $detail_url = '/petugas/detail_pengaduan.php?id=' . $row['id'];
                                    } else {
                                        $detail_url = '/pelapor/detail_pengaduan.php?id=' . $row['id'];
                                    }
                                ?>
                                <a href="<?= $detail_url ?>" class="btn btn-outline btn-sm">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
