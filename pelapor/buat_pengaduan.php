<?php
/**
 * buat_pengaduan.php
 * Form pembuatan tiket pengaduan baru oleh Pelapor
 */
$page_title = 'Buat Pengaduan';
require_once __DIR__ . '/../layouts/header.php';
cek_peran('pelapor');

// Ambil daftar kategori untuk dropdown
$kategori_list = $pdo->query("SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori")->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h3>Formulir Pengaduan Baru</h3>
    </div>
    <div class="card-body">
        <form action="/pelapor/proses_pengaduan.php" method="POST" class="form-modern">
            <div class="form-group">
                <label for="judul">Judul Pengaduan <span class="required">*</span></label>
                <input type="text" id="judul" name="judul" placeholder="Ringkasan singkat masalah Anda" required maxlength="200">
            </div>

            <div class="form-group">
                <label for="kategori_id">Kategori Masalah <span class="required">*</span></label>
                <select id="kategori_id" name="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori_list as $kat): ?>
                        <option value="<?= $kat['id'] ?>"><?= e($kat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Detail <span class="required">*</span></label>
                <textarea id="deskripsi" name="deskripsi" rows="6" placeholder="Jelaskan masalah Anda secara detail: apa yang terjadi, kapan, dan langkah apa yang sudah Anda coba..." required></textarea>
            </div>

            <div class="form-actions">
                <a href="/dashboard.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Kirim Pengaduan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
