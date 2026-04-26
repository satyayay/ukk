<?php
/**
 * edit_pengguna.php (Admin)
 * Form edit data pengguna
 */
$page_title = 'Edit Pengguna';
require_once __DIR__ . '/../layouts/header.php';
cek_peran('admin');

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT id, nama, username, peran, email FROM pengguna WHERE id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch();

if (!$user) {
    set_flash('error', 'Pengguna tidak ditemukan.');
    header('Location: /admin/kelola_pengguna.php');
    exit;
}
?>

<div class="card">
    <div class="card-header">
        <h3>Edit Pengguna: <?= e($user['nama']) ?></h3>
    </div>
    <div class="card-body">
        <form action="/admin/proses_edit_pengguna.php" method="POST" class="form-modern">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="nama">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" id="nama" name="nama" value="<?= e($user['nama']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input type="text" id="username" name="username" value="<?= e($user['username']) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= e($user['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="peran">Peran <span class="required">*</span></label>
                    <select id="peran" name="peran" required>
                        <option value="admin" <?= $user['peran'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                        <option value="petugas" <?= $user['peran'] === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                        <option value="pelapor" <?= $user['peran'] === 'pelapor' ? 'selected' : '' ?>>Pelapor</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password Baru <small>(kosongkan jika tidak ingin mengubah)</small></label>
                <input type="password" id="password" name="password" minlength="6">
            </div>

            <div class="form-actions">
                <a href="/admin/kelola_pengguna.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
