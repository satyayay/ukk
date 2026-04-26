<?php
/**
 * detail_pengaduan.php (Admin)
 * Detail lengkap pengaduan, termasuk assign petugas dan tanggapan
 */
$page_title = 'Detail Pengaduan';
require_once __DIR__ . '/../layouts/header.php';
cek_peran('admin');

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT p.*, k.nama_kategori, u.nama as nama_pelapor, u.email as email_pelapor, pt.nama as nama_petugas
    FROM pengaduan p
    JOIN kategori k ON p.kategori_id = k.id
    JOIN pengguna u ON p.user_id = u.id
    LEFT JOIN pengguna pt ON p.petugas_id = pt.id
    WHERE p.id = :id");
$stmt->execute(['id' => $id]);
$pengaduan = $stmt->fetch();

if (!$pengaduan) {
    set_flash('error', 'Pengaduan tidak ditemukan.');
    header('Location: /admin/semua_pengaduan.php');
    exit;
}

// Tanggapan
$stmt = $pdo->prepare("SELECT t.*, u.nama, u.peran FROM tanggapan t JOIN pengguna u ON t.user_id = u.id WHERE t.pengaduan_id = :pid ORDER BY t.created_at ASC");
$stmt->execute(['pid' => $id]);
$tanggapan_list = $stmt->fetchAll();

// Daftar petugas
$petugas_list = $pdo->query("SELECT id, nama FROM pengguna WHERE peran = 'petugas' ORDER BY nama")->fetchAll();
?>

<a href="/admin/semua_pengaduan.php" class="btn btn-outline btn-sm" style="margin-bottom: 1rem;">&larr; Kembali</a>

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
                <span class="detail-label">Email</span>
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
            <div class="detail-item">
                <span class="detail-label">Ditugaskan ke</span>
                <span class="detail-value"><?= e($pengaduan['nama_petugas'] ?? 'Belum ditugaskan') ?></span>
            </div>
        </div>

        <div class="detail-section">
            <h4>Deskripsi</h4>
            <div class="description-box"><?= nl2br(e($pengaduan['deskripsi'])) ?></div>
        </div>
    </div>
</div>

<!-- Assign Petugas & Status -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header"><h3>Kelola Tiket</h3></div>
    <div class="card-body">
        <div class="admin-actions-grid">
            <form action="/admin/proses_assign.php" method="POST" class="form-modern">
                <div class="form-group">
                    <label>Tugaskan ke Petugas</label>
                    <select name="petugas_id" required>
                        <option value="">-- Pilih Petugas --</option>
                        <?php foreach ($petugas_list as $pt): ?>
                            <option value="<?= $pt['id'] ?>" <?= $pengaduan['petugas_id'] == $pt['id'] ? 'selected' : '' ?>><?= e($pt['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="pengaduan_id" value="<?= $pengaduan['id'] ?>">
                <input type="hidden" name="redirect" value="detail">
                <button type="submit" class="btn btn-primary btn-sm">Tugaskan</button>
            </form>

            <form action="/admin/proses_status_admin.php" method="POST" class="form-modern">
                <div class="form-group">
                    <label>Ubah Status</label>
                    <select name="status" required>
                        <option value="menunggu" <?= $pengaduan['status'] === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="diproses" <?= $pengaduan['status'] === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                        <option value="selesai"  <?= $pengaduan['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                    </select>
                </div>
                <input type="hidden" name="pengaduan_id" value="<?= $pengaduan['id'] ?>">
                <button type="submit" class="btn btn-primary btn-sm">Perbarui</button>
            </form>
        </div>
    </div>
</div>

<!-- Tanggapan -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header"><h3>Tanggapan (<?= count($tanggapan_list) ?>)</h3></div>
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
        <?php else: ?>
            <p class="text-muted">Belum ada tanggapan.</p>
        <?php endif; ?>

        <form action="/admin/proses_tanggapan_admin.php" method="POST" class="form-modern" style="margin-top: 1.5rem;">
            <input type="hidden" name="pengaduan_id" value="<?= $pengaduan['id'] ?>">
            <div class="form-group">
                <label for="isi_tanggapan">Tulis Tanggapan (Admin)</label>
                <textarea id="isi_tanggapan" name="isi_tanggapan" rows="4" placeholder="Tulis tanggapan..." required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
