<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | E-Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card-login {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card card-login">
                <div class="card-header">
                    <h4>LOGIN APLIKASI</h4>
                    <small>Pengaduan Sekolah</small>
                </div>
                <div class="card-body p-4">

                    <?php 
                    if(isset($_GET['pesan'])){
                        if($_GET['pesan']=="gagal"){
                            echo "<div class='alert alert-danger'>Username atau Password salah!</div>";
                        }
                    }
                    ?>

                    <form action="cek_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username / NIS</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan NIS atau Username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                            <div class="form-text text-muted">*Untuk Siswa, masukkan NIS lagi sebagai password.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Login Sebagai</label>
                            <select name="level" class="form-select">
                                <option value="siswa">Siswa</option>
                                <option value="admin">Petugas / Admin</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">MASUK SEKARANG</button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3 text-muted">
                <small>&copy; 2026 UKK RPL Paket 3</small>
            </div>
        </div>
    </div>
</div>

</body>
</html>