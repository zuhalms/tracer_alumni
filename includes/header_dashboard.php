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
    <title><?= isset($title) ? htmlspecialchars($title) : 'Tracer Alumni Kampus Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background: #f6fafd;
            min-height: 100vh;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .container-main {
            padding-top: 76px;
            padding-bottom: 40px;
        }
        .navbar {
            background: #e8f5e9 !important;
            box-shadow: 0 2px 8px rgba(120,180,120,0.10) !important;
        }
        .navbar-brand, .nav-link {
            color: #197948 !important;
            font-weight: 600;
        }
        .nav-link.active, .nav-link:hover {
            color: #145b38 !important;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard_alumni.php">Tracer Alumni Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDashboard" aria-controls="navbarNavDashboard" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbarNavDashboard" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="dashboard_alumni.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container container-main">
