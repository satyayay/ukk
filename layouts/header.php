<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Pak! - Pengaduan Sekolah</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f0f2f5; 
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">📢 E-Pengaduan</a>
    
    <div class="d-flex">
        <?php if(isset($_SESSION['status'])): ?>
            <a href="../logout.php" class="btn btn-light btn-sm fw-bold text-primary">Logout</a>
        <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container">