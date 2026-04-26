<?php
/**
 * semua_pengaduan.php (Admin)
 * Menampilkan semua pengaduan di sistem dengan kemampuan assign petugas
 */
$page_title = 'Semua Pengaduan';
require_once __DIR__ . '/../layouts/header.php';
cek_peran('admin');

// Filter status
$filter_status = $_GET['status'] ?? '';

$sql = "SELECT p.*, k.nama_kategori, u.nama as nama_pelapor, pt.nama as nama_petugas
    FROM pengaduan p
    JOIN kategori k ON p.kategori_id = k.id
    JOIN pengguna u ON p.user_id = u.id
    LEFT JOIN pengguna pt ON p.petugas_id = pt.id";

$params = [];

if (in_array($filter_status, ['menunggu', 'diproses', 'selesai'])) {
    $sql .= " WHERE p.status = :status";
    $params['status'] = $filter_status;
}

$sql .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pengaduan_list = $stmt->fetchAll();

// Daftar petugas untuk dropdown assign
$petugas_list = $pdo->query("SELECT id, nama FROM pengguna WHERE peran = 'petugas' ORDER BY nama")->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h3>Semua Pengaduan</h3>
        <div class="filter-group">
            <a href="?status=" class="btn btn-sm <?= $filter_status === '' ? 'btn-primary' : 'btn-outline' ?>">Semua</a>
            <a href="?status=menunggu" class="btn btn-sm <?= $filter_status === 'menunggu' ? 'btn-primary' : 'btn-outline' ?>">Menunggu</a>
            <a href="?status=diproses" class="btn btn-sm <?= $filter_status === 'diproses' ? 'btn-primary' : 'btn-outline' ?>">Diproses</a>
            <a href="?status=selesai" class="btn btn-sm <?= $filter_status === 'selesai' ? 'btn-primary' : 'btn-outline' ?>">Selesai</a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($pengaduan_list)): ?>
            <div class="empty-state">
                <p>Tidak ada pengaduan<?= $filter_status ? " dengan status \"{$filter_status}\"" : '' ?>.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Ditugaskan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pengaduan_list as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td class="td-title"><?= e($row['judul']) ?></td>
                            <td><?= e($row['nama_pelapor']) ?></td>
                            <td><?= e($row['nama_kategori']) ?></td>
                            <td><span class="status-badge <?= status_class($row['status']) ?>"><?= status_label($row['status']) ?></span></td>
                            <td>
                                <?php if ($row['nama_petugas']): ?>
                                    <?= e($row['nama_petugas']) ?>
                                <?php else: ?>
                                    <form action="/admin/proses_assign.php" method="POST" class="form-assign-inline">
                                        <input type="hidden" name="pengaduan_id" value="<?= $row['id'] ?>">
                                        <select name="petugas_id" required>
                                            <option value="">Pilih...</option>
                                            <?php foreach ($petugas_list as $pt): ?>
                                                <option value="<?= $pt['id'] ?>"><?= e($pt['nama']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-xs">Assign</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td class="td-date"><?= format_tanggal($row['created_at']) ?></td>
                            <td>
                                <a href="/admin/detail_pengaduan.php?id=<?= $row['id'] ?>" class="btn btn-outline btn-sm">Detail</a>
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
