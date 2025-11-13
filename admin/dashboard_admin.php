<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../assets/logo-uin.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin - Tracer Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f6fafd;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .navbar {
            background: #e8f5e9 !important;
            box-shadow: 0 2px 8px rgba(120,180,120,0.10) !important;
            min-height: 64px;
            padding: 8px 0;
            z-index: 1051;
        }
        .navbar-brand {
            display: flex; align-items: center; gap: 14px;
            color: #197948 !important;
            font-weight: 700;
            font-size: 1.25rem;
        }
        .navbar-brand img {
            height:40px; width:40px; object-fit:contain; border-radius:6px; border:none;
        }
        .sidebar-admin {
            min-height: 100vh;
            background: #fff;
            width: 265px;
            box-shadow: 0 2px 24px 0 #c3ecc67e;
            padding: 0;
            position: fixed;
            top: 0; left: 0; z-index: 1100;
        }
        .sidebar-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 0 12px 0;
        }
        .sidebar-profile img {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            object-fit: cover;
            background: #e4faf0;
            border: 4px solid #e6f4ea;
            margin-bottom: 6px;
        }
        .sidebar-profile .admin-name {
            color: #219150;
            font-size: 1.28rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        .sidebar-profile .admin-role {
            font-size: 1.07rem;
            color: #419873;
            font-weight: 600;
            margin-bottom: 1px;
        }
        .sidebar-profile .admin-label {
            color: #c6cec5;
            font-size: .94rem;
            font-weight: 500;
        }
        .sidebar-menu {
            margin-top: 15px;
        }
        .sidebar-menu .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 22px;
            font-size: 1.07rem;
            background: none;
            color: #295d3a;
            border: none;
            border-radius: 14px 0 0 14px;
            margin-bottom: 4px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.12s, color 0.12s;
        }
        .sidebar-menu .menu-item.active,
        .sidebar-menu .menu-item:hover {
            background: #e8f5e9;
            color: #22aa57;
        }
        .sidebar-menu .menu-item .bi {
            font-size: 1.12rem;
            min-width: 22px;
        }
        @media (max-width:900px) {
            .sidebar-admin { width: 100vw; position: static; min-height: auto; }
            .main-content { margin-left: 0; }
        }
        .main-content {
            margin-left: 265px;
            padding: 20px 38px 30px 38px;
            min-height: 100vh;
            background: #f6fafd;
            /* Add stacking context */
            position: relative;
            z-index: 1;
        }
        .header-admin {
            font-weight: 700;
            color: #219150;
            margin-bottom: 24px;
            font-size: 1.44rem;
            letter-spacing: .3px;
        }
        .welcome-box {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 14px rgba(50,108,75,.07);
            padding: 28px 38px 30px 38px;
            max-width: 1500px;
            margin: 0 0 40px 0;
            border: 1.2px solid #e8efeb;
            margin-left: 0;
            float: none;
        }
        .welcome-head {
            font-weight: 800;
            font-size: 2.1rem;
            letter-spacing: 1px;
            color: #212a2e;
            margin-bottom: 6px;
            text-align: left;
        }
        .welcome-desc {
            font-size: 1.09rem;
            color: #354b3b;
            margin-bottom: 0;
            text-align: left;
        }
        .logout-link {
            position: fixed;
            bottom: 28px; left: 0; width: 265px;
            text-align: center;
        }
        /* Prevent sidebar from overlapping header */
        @media (min-width: 900px) {
          .navbar {
            left: 0;
            width: 100vw;
            position: fixed;
            z-index: 1052;
          }
          body {
            padding-top: 64px;
          }
          .sidebar-admin {
            top: 64px;
            height: calc(100vh - 64px);
          }
        }
        @media (max-width: 991px) {
            .main-content { padding: 28px 3vw 18px 3vw; }
            .welcome-box { margin-left: auto; margin-right: auto;}
            .sidebar-admin, .navbar {
                position: static !important;
                width: 100vw;
            }
            .logout-link { position: static; width: 100%; }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid px-3">
        <a class="navbar-brand fw-bold" href="dashboard_admin.php">
            <img src="../assets/logo-uin.png" alt="Logo Admin"/> Tracer Alumni (Admin)
        </a>
        <div class="logout-link d-lg-none d-block" style="position:static;margin:0;">
            <a href="logout_admin.php" class="btn btn-outline-danger btn-sm my-2 px-4"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
        </div>
    </div>
</nav>

<div class="sidebar-admin">
    <div class="sidebar-profile">
        <img src="../assets/admin.png" alt="Foto Admin">
        <div class="admin-name">Admin</div>
        <div class="admin-role">Administrator</div>
        <div class="admin-label">Tracer Alumni</div>
    </div>
    <div class="sidebar-menu">
        <a href="dashboard_admin.php" class="menu-item active"><i class="bi bi-house-door-fill"></i> Dashboard</a>
        <a href="data_alumni.php" class="menu-item"><i class="bi bi-speedometer2"></i> Data Alumni</a>
        <a href="admin_export.php" class="menu-item"><i class="bi bi-file-earmark-excel"></i> Export Data</a>
    </div>
    <div class="logout-link d-none d-lg-block">
        <a href="logout_admin.php" class="btn btn-outline-danger btn-sm my-2 px-4"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="header-admin"><i class="bi bi-house-door-fill me-2"></i>Dashboard</div>
    <div class="welcome-box mb-4">
        <div class="welcome-head">Selamat Datang, Admin!</div>
        <div class="welcome-desc">
            Selamat datang di sistem tracer alumni.<br>
            Anda dapat mengelola data alumni, memantau progres kuesioner, serta mengekspor data untuk kepentingan evaluasi dan pelaporan kampus di UIN Alauddin Makassar.<br>
            Silakan gunakan menu di sidebar untuk navigasi fitur sistem ini.
        </div>
    </div>
</div>
</body>
</html>