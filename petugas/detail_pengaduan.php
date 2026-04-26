<?php
/**
 * detail_pengaduan.php (Petugas)
 * Detail pengaduan dengan kemampuan membalas dan mengubah status
 */
$page_title = 'Detail Pengaduan';
require_once __DIR__ . '/../layouts/header.php';
cek_peran('petugas');

$id = (int)($_GET['id'] ?? 0);

// Ambil data pengaduan (hanya yang ditugaskan ke petugas ini)
$stmt = $pdo->prepare("SELECT p.*, k.nama_kategori, u.nama as nama_pelapor, u.email as email_pelapor
    FROM pengaduan p
    JOIN kategori k ON p.kategori_id = k.id
    JOIN pengguna u ON p.user_id = u.id
    WHERE p.id = :id AND p.petugas_id = :uid");
$stmt->execute(['id' => $id, 'uid' => $_SESSION['user_id']]);
$pengaduan = $stmt->fetch();

if (!$pengaduan) {
    set_flash('error', 'Pengaduan tidak ditemukan atau tidak ditugaskan kepada Anda.');
    header('Location: /petugas/daftar_pengaduan.php');
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

<a href="/petugas/daftar_pengaduan.php" class="btn btn-outline btn-sm" style="margin-bottom: 1rem;">&larr; Kembali</a>

<div class="card">
    <div class="card-header">
        <h3><?= e($pengaduan['judul']) ?></h3>
        <span class="status-badge <?= status_class($pengaduan['status']) ?>"><?= status_label($pengaduan['status']) ?></span>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Pelapor</span>
                <span class="detail-value"><?= e($pengaduan['nama_pelapor']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email Pelapor</span>
                <span class="detail-value"><?= e($pengaduan['email_pelapor'] ?? '-') ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Kategori</span>
                <span class="detail-value"><?= e($pengaduan['nama_kategori']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tanggal Dibuat</span>
                <span class="detail-value"><?= format_tanggal($pengaduan['created_at']) ?></span>
            </div>
        </div>

        <div class="detail-section">
            <h4>Deskripsi</h4>
            <div class="description-box"><?= nl2br(e($pengaduan['deskripsi'])) ?></div>
        </div>
    </div>
</div>

<!-- Ubah Status -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3>Ubah Status Tiket</h3>
    </div>
    <div class="card-body">
        <form action="/petugas/proses_status.php" method="POST" class="form-inline-status">
            <input type="hidden" name="pengaduan_id" value="<?= $pengaduan['id'] ?>">
            <select name="status" class="form-select-inline">
                <option value="menunggu" <?= $pengaduan['status'] === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                <option value="diproses" <?= $pengaduan['status'] === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                <option value="selesai"  <?= $pengaduan['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Perbarui Status</button>
        </form>
    </div>
</div>

<!-- Tanggapan -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3>Tanggapan (<?= count($tanggapan_list) ?>)</h3>
    </div>
    <div class="card-body">
        <?php if (!empty($tanggapan_list)): ?>
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

        <!-- Form Tanggapan Baru -->
        <form action="/petugas/proses_tanggapan.php" method="POST" class="form-modern" style="margin-top: 1.5rem;">
            <input type="hidden" name="pengaduan_id" value="<?= $pengaduan['id'] ?>">
            <div class="form-group">
                <label for="isi_tanggapan">Tulis Tanggapan</label>
                <textarea id="isi_tanggapan" name="isi_tanggapan" rows="4" placeholder="Tulis tanggapan atau balasan Anda..." required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
