<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= isset($title) ? htmlspecialchars($title) : 'Tracer Alumni Kampus' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
            min-height: 100vh;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .main-navbar {
            background: transparent;
            box-shadow: none;
            padding-top: 18px;
            padding-bottom: 0;
        }
        .brand-logo {
            display: flex;
            align-items: center;
        }
        .brand-logo img {
            width: 44px;
            margin-right: 10px;
        }
        .brand-title {
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: .5px;
            color: #fff;
            line-height: 1.1;
        }
        .navbar-nav .nav-link {
            color: #d4ffe9 !important;
            font-weight: 500;
            margin-right: 15px;
            opacity: 0.89;
        }
        .navbar-nav .nav-link.active, .navbar-nav .nav-link:hover {
            color: #fff !important;
            text-decoration: underline;
            opacity: 1;
        }
        @media (max-width: 800px) {
            .brand-title { font-size: 1rem; }
            .brand-logo img { width: 32px;}
        }
        .container-main {
            padding-top: 68px;
            padding-bottom: 40px;
        }
    </style>
</head>
<body>
<nav class="navbar main-navbar navbar-expand-lg">
    <div class="container">
        <div class="brand-logo">
            <img src="assets/logo-uin.png" alt="Logo UIN" />
            <div>
                <span class="brand-title">TRACER ALUMNI Jurusan Teknik Informatika<br>
                    <span style="font-size:.85rem;font-weight:400;">Universitas Islam Negeri Alauddin Makassar</span></span>
            </div>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavMain">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link<?= ($_SERVER['SCRIPT_NAME']=='/index.php'?' active':'') ?>" href="index.php">Beranda</a></li>
                <?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard_alumni.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="profil.php"><i class="bi bi-person-circle"></i> Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="kuesioner.php"><i class="bi bi-journal-text"></i> Kuesioner</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php"><i class="bi bi-pencil-square"></i> Registrasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/login_admin.php"><i class="bi bi-shield-lock"></i> Admin</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container container-main">
