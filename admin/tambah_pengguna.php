<?php
/**
 * tambah_pengguna.php (Admin)
 * Form menambah pengguna baru
 */
$page_title = 'Tambah Pengguna';
require_once __DIR__ . '/../layouts/header.php';
cek_peran('admin');
?>

<div class="card">
    <div class="card-header">
        <h3>Tambah Pengguna Baru</h3>
    </div>
    <div class="card-body">
        <form action="/admin/proses_tambah_pengguna.php" method="POST" class="form-modern">
            <div class="form-row">
                <div class="form-group">
                    <label for="nama">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" id="nama" name="nama" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input type="text" id="username" name="username" required maxlength="50">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="peran">Peran <span class="required">*</span></label>
                    <select id="peran" name="peran" required>
                        <option value="">-- Pilih Peran --</option>
                        <option value="admin">Administrator</option>
                        <option value="petugas">Petugas</option>
                        <option value="pelapor">Pelapor</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="konfirmasi_password">Konfirmasi Password <span class="required">*</span></label>
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password" required minlength="6">
                </div>
            </div>

            <div class="form-actions">
                <a href="/admin/kelola_pengguna.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
