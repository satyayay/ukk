<?php
session_start();
include '../config/koneksi.php';

$id = $_POST['id_pelaporan'];
$id_kategori = $_POST['id_kategori'];
$lokasi = $_POST['lokasi'];
$ket = $_POST['ket'];
$foto_lama = $_POST['foto_lama'];

// LOGIKA GANTI FOTO
$foto = $_FILES['foto']['name'];
$tmp = $_FILES['foto']['tmp_name'];

if($foto != "") {
    // Jika user upload foto baru
    $acak = rand(100, 999);
    $nama_baru = $acak . "-" . $foto;
    
    // Upload foto baru
    move_uploaded_file($tmp, '../assets/foto_bukti/' . $nama_baru);
    
    // Hapus foto lama biar server gak penuh
    if($foto_lama != "" && file_exists("../assets/foto_bukti/".$foto_lama)){
        unlink("../assets/foto_bukti/".$foto_lama);
    }
    
    // Query update dengan foto
    $query_update = "UPDATE input_aspirasi SET 
                    id_kategori='$id_kategori',
                    lokasi='$lokasi',
                    ket='$ket',
                    foto='$nama_baru'
                    WHERE id_pelaporan='$id'";

} else {
    // Jika user TIDAK upload foto baru (pakai foto lama)
    $query_update = "UPDATE input_aspirasi SET 
                    id_kategori='$id_kategori',
                    lokasi='$lokasi',
                    ket='$ket'
                    WHERE id_pelaporan='$id'";
}

$run = mysqli_query($conn, $query_update);

if($run) {
    echo "<script>
            alert('Laporan Berhasil Diperbarui!');
            window.location='riwayat.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal Update Laporan!');
            window.location='edit_pengaduan.php?id=$id';
          </script>";
}
?>