<?php
/**
 * riwayat.php
 * Menampilkan riwayat semua pengaduan milik Pelapor
 */
$page_title = 'Riwayat Pengaduan';
require_once __DIR__ . '/../layouts/header.php';
cek_peran('pelapor');

// Ambil semua pengaduan milik user yang login
$stmt = $pdo->prepare("SELECT p.*, k.nama_kategori
    FROM pengaduan p
    JOIN kategori k ON p.kategori_id = k.id
    WHERE p.user_id = :uid
    ORDER BY p.created_at DESC");
$stmt->execute(['uid' => $_SESSION['user_id']]);
$pengaduan_list = $stmt->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h3>Riwayat Pengaduan Saya</h3>
        <a href="/pelapor/buat_pengaduan.php" class="btn btn-primary btn-sm">+ Buat Pengaduan</a>
    </div>
    <div class="card-body">
        <?php if (empty($pengaduan_list)): ?>
            <div class="empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p>Anda belum memiliki pengaduan. <a href="/pelapor/buat_pengaduan.php">Buat pengaduan pertama</a>.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pengaduan_list as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td class="td-title"><?= e($row['judul']) ?></td>
                            <td><?= e($row['nama_kategori']) ?></td>
                            <td><span class="status-badge <?= status_class($row['status']) ?>"><?= status_label($row['status']) ?></span></td>
                            <td class="td-date"><?= format_tanggal($row['created_at']) ?></td>
                            <td>
                                <a href="/pelapor/detail_pengaduan.php?id=<?= $row['id'] ?>" class="btn btn-outline btn-sm">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
