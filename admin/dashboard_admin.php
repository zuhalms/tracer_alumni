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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
        }
        body {
            background: #f6fafd;
            font-family: 'Montserrat', Arial, sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }
        
        /* Navbar Styles */
        .navbar {
            background: #e8f5e9 !important;
            box-shadow: 0 2px 8px rgba(120,180,120,0.10) !important;
            min-height: 64px;
            padding: 8px 0;
            z-index: 1051;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            color: #197948 !important;
            font-weight: 700;
            font-size: 1.25rem;
        }
        .navbar-brand img {
            height: 40px;
            width: 40px;
            object-fit: contain;
            border-radius: 6px;
            border: none;
            background: transparent;
        }
        
        /* Hamburger Menu Button (Mobile Only) */
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            color: #197948;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px 10px;
        }
        
        /* Sidebar Styles */
        .sidebar-admin {
            min-height: 100vh;
            background: #fff;
            width: 265px;
            border-right: 1.6px solid #e4efea;
            box-shadow: 0 1px 10px #3ead6130;
            padding-top: 64px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1040;
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-bottom: 100px; /* Beri ruang untuk tombol logout */
        }
        
        .sidebar-content {
            flex: 1;
        }
        
        .sidebar-profile {
            text-align: center;
            padding: 32px 20px 14px 20px;
        }
        .sidebar-profile img {
            width: 92px;
            height: 92px;
            border-radius: 50%;
            object-fit: cover;
            background: #e4faf0;
            border: 4px solid #e9f7ef;
            margin-bottom: 8px;
        }
        .sidebar-profile .admin-name {
            color: #197948;
            font-size: 1.14rem;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .sidebar-profile .admin-role {
            font-size: 1rem;
            color: #7fa882;
        }
        
        .sidebar-menu {
            padding-top: 10px;
        }
        .sidebar-menu .menu-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 14px 28px;
            font-size: 1.07rem;
            background: #f7fcfa;
            color: #222;
            border: none;
            border-radius: 8px 0 0 8px;
            margin-bottom: 4px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.18s, color 0.18s;
        }
        .sidebar-menu .menu-item.active,
        .sidebar-menu .menu-item:hover {
            background: #dcf8e5;
            color: #258B42;
        }
        .sidebar-menu .menu-item .bi {
            font-size: 1.15rem;
            min-width: 22px;
        }
        
        /* Logout button fixed at bottom of sidebar */
        .logout-link {
            position: fixed;
            bottom: 28px;
            left: 0;
            width: 265px;
            text-align: center;
            z-index: 1041;
            background: #fff; /* Tambahkan background agar tidak transparan */
            padding: 10px 0;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 265px;
            margin-top: 64px;
            padding: 30px 38px;
            min-height: calc(100vh - 64px);
            background: #f6fafd;
            transition: margin-left 0.3s ease-in-out;
        }
        
        .header-admin {
            font-weight: 700;
            color: #219150;
            margin-bottom: 24px;
            font-size: 1.44rem;
            text-align: left;
        }
        
        .welcome-box {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 14px rgba(50, 108, 75, .07);
            padding: 28px 38px 30px 38px;
            max-width: 1200px;
            margin: 0;
        }
        .welcome-head {
            font-weight: 800;
            font-size: 2.1rem;
            color: #212a2e;
            margin-bottom: 12px;
            text-align: left;
        }
        .welcome-desc {
            font-size: 1.09rem;
            color: #354b3b;
            text-align: left;
            line-height: 1.7;
        }
        
        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1039;
            transition: opacity 0.3s ease-in-out;
        }
        
        /* Responsive Styles */
        @media (max-width: 991px) {
            .hamburger-btn {
                display: block;
            }
            
            .sidebar-admin {
                transform: translateX(-100%);
                width: 100vw;
                position: fixed;
                top: 0;
                border-radius: 0;
                box-shadow: none;
                padding-bottom: 20px; /* Reset padding untuk mobile */
            }
            
            .sidebar-admin.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .logout-link {
                position: relative; /* Ubah dari fixed ke relative */
                bottom: auto;
                width: 100%;
                padding: 20px;
                margin-top: 20px;
                background: transparent;
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px 20px;
            }
            
            .welcome-box {
                padding: 24px 24px;
            }
            
            .welcome-head {
                font-size: 1.7rem;
            }
            
            .welcome-desc {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 600px) {
            .navbar-brand {
                font-size: 1rem;
            }
            
            .navbar-brand img {
                height: 32px;
                width: 32px;
            }
            
            .main-content {
                padding: 16px 12px;
            }
            
            .welcome-box {
                padding: 20px 16px;
            }
            
            .welcome-head {
                font-size: 1.5rem;
            }
            
            .welcome-desc {
                font-size: 0.95rem;
            }
            
            .header-admin {
                font-size: 1.25rem;
            }
            
            .sidebar-profile img {
                width: 70px;
                height: 70px;
            }
            
            .sidebar-profile .admin-name {
                font-size: 1.1rem;
            }
            
            .sidebar-profile .admin-role {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid px-3">
        <button class="hamburger-btn" id="hamburgerBtn">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand fw-bold" href="dashboard_admin.php">
            <img src="../assets/logo-uin.png" alt="Logo Admin"> Tracer Alumni (Admin)
        </a>
    </div>
</nav>

<!-- Sidebar Overlay (for mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar-admin" id="sidebar">
    <div class="sidebar-content">
        <div class="sidebar-profile">
            <img src="../assets/admin.png" alt="Foto Admin">
            <div class="admin-name">Administrator</div>
            <div class="admin-role">Tracer Alumni</div>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard_admin.php" class="menu-item active">
                <i class="bi bi-house-door-fill"></i> Dashboard
            </a>
            <a href="data_alumni.php" class="menu-item">
                <i class="bi bi-speedometer2"></i> Data Alumni
            </a>
            <a href="admin_export.php" class="menu-item">
                <i class="bi bi-file-earmark-excel"></i> Export Data
            </a>
        </div>
    </div>
    <!-- Tombol logout tetap terlihat -->
    <div class="logout-link">
        <a href="logout_admin.php" class="btn btn-outline-danger btn-sm my-2 px-4">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header-admin">
        <i class="bi bi-house-door-fill me-2"></i> Dashboard
    </div>
    <div class="welcome-box">
        <div class="welcome-head">Selamat Datang, Admin!</div>
        <div class="welcome-desc">
            Selamat datang di sistem tracer alumni.<br>
            Anda dapat mengelola data alumni, memantau progres kuesioner, serta mengekspor data untuk kepentingan evaluasi dan pelaporan kampus di UIN Alauddin Makassar.<br>
            Silakan gunakan menu di sidebar untuk navigasi fitur sistem ini.
        </div>
    </div>
</div>

<!-- JavaScript for Mobile Sidebar Toggle -->
<script>
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Toggle sidebar on hamburger click
    hamburgerBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
    });
    
    // Close sidebar when overlay is clicked
    sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    });
    
    // Close sidebar when menu item is clicked (for better UX on mobile)
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 991) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
