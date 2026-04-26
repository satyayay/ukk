<?php
/**
 * kelola_kategori.php (Admin)
 * Mengelola kategori pengaduan (tambah, edit, hapus)
 */
$page_title = 'Kelola Kategori';
require_once __DIR__ . '/../layouts/header.php';
cek_peran('admin');

$kategori_list = $pdo->query("SELECT k.*, (SELECT COUNT(*) FROM pengaduan p WHERE p.kategori_id = k.id) as jumlah_pengaduan FROM kategori k ORDER BY k.nama_kategori")->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h3>Daftar Kategori</h3>
    </div>
    <div class="card-body">
        <!-- Form Tambah Kategori -->
        <form action="/admin/proses_kategori.php" method="POST" class="form-inline-add">
            <input type="hidden" name="aksi" value="tambah">
            <input type="text" name="nama_kategori" placeholder="Nama kategori baru..." required maxlength="100" class="input-inline">
            <button type="submit" class="btn btn-primary btn-sm">+ Tambah</button>
        </form>

        <div class="table-responsive" style="margin-top: 1.5rem;">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Pengaduan</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kategori_list as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <form action="/admin/proses_kategori.php" method="POST" class="form-edit-inline">
                                <input type="hidden" name="aksi" value="edit">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="text" name="nama_kategori" value="<?= e($row['nama_kategori']) ?>" required class="input-inline input-edit">
                                <button type="submit" class="btn btn-outline btn-xs">Simpan</button>
                            </form>
                        </td>
                        <td><?= (int)$row['jumlah_pengaduan'] ?></td>
                        <td class="td-date"><?= format_tanggal($row['created_at']) ?></td>
                        <td>
                            <?php if ((int)$row['jumlah_pengaduan'] === 0): ?>
                                <a href="/admin/proses_kategori.php?aksi=hapus&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                            <?php else: ?>
                                <span class="text-muted text-sm">Tidak bisa dihapus</span>
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
