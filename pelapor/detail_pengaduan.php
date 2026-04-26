<?php
/**
 * detail_pengaduan.php (Pelapor)
 * Menampilkan detail lengkap pengaduan beserta tanggapan
 */
$page_title = 'Detail Pengaduan';
require_once __DIR__ . '/../layouts/header.php';
cek_peran('pelapor');

$id = (int)($_GET['id'] ?? 0);

// Ambil data pengaduan (pastikan milik user yang login)
$stmt = $pdo->prepare("SELECT p.*, k.nama_kategori
    FROM pengaduan p
    JOIN kategori k ON p.kategori_id = k.id
    WHERE p.id = :id AND p.user_id = :uid");
$stmt->execute(['id' => $id, 'uid' => $_SESSION['user_id']]);
$pengaduan = $stmt->fetch();

if (!$pengaduan) {
    set_flash('error', 'Pengaduan tidak ditemukan.');
    header('Location: /pelapor/riwayat.php');
    exit;
}

// Ambil tanggapan
$stmt = $pdo->prepare("SELECT t.*, u.nama, u.peran
    FROM tanggapan t
    JOIN pengguna u ON t.user_id = u.id
    WHERE t.pengaduan_id = :pid
    ORDER BY t.created_at ASC");
$stmt->execute(['pid' => $id]);
$tanggapan_list = $stmt->fetchAll();
?>

<a href="/pelapor/riwayat.php" class="btn btn-outline btn-sm" style="margin-bottom: 1rem;">&larr; Kembali</a>

<div class="card">
    <div class="card-header">
        <h3><?= e($pengaduan['judul']) ?></h3>
        <span class="status-badge <?= status_class($pengaduan['status']) ?>"><?= status_label($pengaduan['status']) ?></span>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Kategori</span>
                <span class="detail-value"><?= e($pengaduan['nama_kategori']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tanggal Dibuat</span>
                <span class="detail-value"><?= format_tanggal($pengaduan['created_at']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Terakhir Diperbarui</span>
                <span class="detail-value"><?= format_tanggal($pengaduan['updated_at']) ?></span>
            </div>
        </div>

        <div class="detail-section">
            <h4>Deskripsi</h4>
            <div class="description-box"><?= nl2br(e($pengaduan['deskripsi'])) ?></div>
        </div>
    </div>
</div>

<!-- Tanggapan -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3>Tanggapan (<?= count($tanggapan_list) ?>)</h3>
    </div>
    <div class="card-body">
        <?php if (empty($tanggapan_list)): ?>
            <div class="empty-state">
                <p>Belum ada tanggapan untuk pengaduan ini.</p>
            </div>
        <?php else: ?>
            <div class="tanggapan-list">
                <?php foreach ($tanggapan_list as $tg): ?>
                <div class="tanggapan-item">
                    <div class="tanggapan-header">
                        <div class="tanggapan-avatar"><?= strtoupper(substr($tg['nama'], 0, 1)) ?></div>
                        <div>
                            <strong><?= e($tg['nama']) ?></strong>
                            <span class="tanggapan-role"><?= peran_label($tg['peran']) ?></span>
                        </div>
                        <span class="tanggapan-date"><?= format_tanggal($tg['created_at']) ?></span>
                    </div>
                    <div class="tanggapan-body"><?= nl2br(e($tg['isi_tanggapan'])) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
