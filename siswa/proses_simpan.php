<?php
session_start();
include '../config/koneksi.php';

// Tangkap data dari form
$nis = $_SESSION['username'];
$id_kategori = $_POST['id_kategori'];
$lokasi = $_POST['lokasi'];
$ket = $_POST['ket'];
$tgl_pengaduan = date('Y-m-d'); // Format MySQL (Tahun-Bulan-Tanggal)

// LOGIKA UPLOAD FOTO
$foto = $_FILES['foto']['name'];
$tmp = $_FILES['foto']['tmp_name'];
$nama_baru = "";

if($foto != "") {
    // Jika user upload foto
    $acak = rand(100, 999);
    $nama_baru = $acak . "-" . $foto; // Tambah angka acak biar nama file gak kembar
    
    // Pindahkan file ke folder tujuan
    move_uploaded_file($tmp, '../assets/foto_bukti/' . $nama_baru);
}

// Simpan ke Database
$query = mysqli_query($conn, "INSERT INTO input_aspirasi 
    (nis, id_kategori, lokasi, ket, tgl_pengaduan, foto) 
    VALUES 
    ('$nis', '$id_kategori', '$lokasi', '$ket', '$tgl_pengaduan', '$nama_baru')
");
     
if($query) {
    echo "<script>
            alert('Laporan Berhasil Terkirim!');
            window.location='index.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal Mengirim Laporan!');
            window.location='tulis_pengaduan.php';
          </script>";
}
?>