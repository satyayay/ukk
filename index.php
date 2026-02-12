<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | E-Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f5f8fa; }
        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0px 0px 30px 0px rgba(82,63,105,0.05);
            background: #fff;
            padding: 2rem;
        }
        .form-control {
            background-color: #f5f8fa;
            border: 1px solid #f5f8fa;
            color: #5e6278;
            padding: 0.8rem 1rem;
            font-weight: 500;
        }
        .form-control:focus {
            background-color: #eef3f7;
            border-color: #eef3f7;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #009ef7;
            border-color: #009ef7;
            padding: 0.8rem;
            font-weight: 600;
        }
        .btn-primary:hover { background-color: #0095e8; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

<div class="col-md-4">
    <div class="text-center mb-4">
        <h2 class="fw-bolder text-dark">Selamat Datang</h2>
        <div class="text-muted">Silakan login untuk melanjutkan</div>
    </div>
    
    <div class="login-card">
        <?php 
        if(isset($_GET['pesan']) && $_GET['pesan']=="gagal"){
            echo "<div class='alert alert-danger border-0 small py-2 px-3 mb-4'>Username/Password salah!</div>";
        }
        ?>

        <form action="cek_login.php" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">NIS / USERNAME</label>
                <input type="text" name="username" class="form-control" autocomplete="off" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">PASSWORD</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">LOGIN SEBAGAI</label>
                <select name="level" class="form-select border-0 bg-light py-2 fw-bold text-muted">
                    <option value="siswa">Siswa</option>
                    <option value="admin">Petugas / Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Masuk Aplikasi</button>
        </form>
    </div>
    
    <div class="text-center mt-4">
        <small class="text-muted">&copy; 2026 UKK RPL - Paket 3</small>
    </div>
</div>

</body>
</html>