<?php
/**
 * kelola_pengguna.php (Admin)
 * Mengelola semua pengguna sistem (CRUD)
 */
$page_title = 'Kelola Pengguna';
require_once __DIR__ . '/../layouts/header.php';
cek_peran('admin');

$pengguna_list = $pdo->query("SELECT id, nama, username, peran, email, created_at FROM pengguna ORDER BY peran, nama")->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h3>Daftar Pengguna</h3>
        <a href="/admin/tambah_pengguna.php" class="btn btn-primary btn-sm">+ Tambah Pengguna</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Peran</th>
                        <th>Email</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pengguna_list as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= e($row['nama']) ?></td>
                        <td><code><?= e($row['username']) ?></code></td>
                        <td><span class="role-badge role-<?= $row['peran'] ?>"><?= peran_label($row['peran']) ?></span></td>
                        <td><?= e($row['email'] ?? '-') ?></td>
                        <td class="td-date"><?= format_tanggal($row['created_at']) ?></td>
                        <td>
                            <a href="/admin/edit_pengguna.php?id=<?= $row['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                            <?php if ($row['id'] !== $_SESSION['user_id']): ?>
                                <a href="/admin/hapus_pengguna.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
