<?php
session_start();
include 'config/config.php';

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php?error=not_logged_in");
    exit();
}
$id_alumni = $_SESSION['id_alumni'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_alumni WHERE id_alumni=$id_alumni"));

// Ambil data pekerjaan alumni
$pekerjaan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_pekerjaan WHERE id_alumni=$id_alumni"));

// Ambil data kuesioner alumni
$kuesioner = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_kuesioner WHERE id_alumni=$id_alumni"));

$title = "Dashboard Alumni";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        html, body { height:100%; }
        body {
            background: #f6fafd;
            min-height: 100vh;
            margin: 0;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .navbar {
            background: #e8f5e9 !important;
            box-shadow: 0 2px 8px rgba(120,180,120,0.10) !important;
            z-index: 1051;
            min-height: 64px;
            padding: 8px 0;
        }
        .navbar-brand {
            display: flex; 
            align-items: center; 
            gap: 14px;
            color: #197948 !important;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: .01em;
        }
        .navbar-brand img {
            height: 40px; 
            width: 40px; 
            object-fit: contain; 
            border-radius: 6px;
            border: none; 
            box-shadow: none; 
            background: transparent;
        }
        .navbar .nav-link {
            color: #197948 !important;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all .15s ease;
        }
        .navbar .nav-link:hover {
            background: rgba(25,121,72,0.08);
            color: #145a35 !important;
        }
        .navbar .nav-link.active {
            background: #dcf8e5;
            color: #145a35 !important;
        }
        .sidebar {
            min-height: 100vh;
            background: #fff;
            border-right: 1.6px solid #e4efea;
            padding: 0;
            box-shadow: 0 1px 10px #3ead6130;
            position: fixed;
            left: 0; 
            top: 64px;
            bottom: 0; 
            z-index: 1040;
            width: 230px;
            display: flex; 
            flex-direction: column; 
            justify-content: space-between;
        }
        .main-content {
            margin-left: 230px;
            padding: 88px 38px 30px 38px;
        }
        .profile-box {
            text-align: center;
            padding: 32px 0 14px 0;
        }
        .profile-img {
            width: 92px; 
            height: 92px; 
            object-fit: cover;
            border-radius: 50%; 
            border: 4px solid #e9f7ef;
        }
        .profile-name {
            font-size: 1.14rem; 
            font-weight: 700; 
            margin-top: 8px; 
            color: #197948;
        }
        .profile-desc {
            font-size: 1.01rem; 
            color: #7fa882;
        }
        .sidebar-link {
            display: flex; 
            align-items: center;
            color: #222; 
            background: #f7fcfa;
            border: none; 
            padding: 14px 28px; 
            margin-bottom: 4px;
            border-radius: 8px 0 0 8px;
            text-decoration: none; 
            font-weight: 500;
            transition: .18s;
        }
        .sidebar-link.active, .sidebar-link:hover {
            background: #dcf8e5;
            color: #258B42;
        }
        .sidebar-link i {
            font-size: 1.15rem; 
            margin-right: 11px;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(34,139,34,0.07);
        }
        .status-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            margin-right: 8px;
        }
        .status-complete {
            background: #d1e7dd;
            color: #0f5132;
        }
        .status-pending {
            background: #fff3cd;
            color: #664d03;
        }
        @media (max-width: 900px) {
            .sidebar {
                width: 100vw;
                position: relative;
                top: 0;
                border-radius: 0;
                min-height: auto;
                box-shadow: none;
            }
            .main-content {
                margin-left: 0;
                padding: 20px 6px 18px 6px;
            }
            .navbar-brand {
                font-size: 1.1rem;
            }
            .navbar-brand img {
                height: 32px;
                width: 32px;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid px-3">
        <a class="navbar-brand fw-bold" href="dashboard_alumni.php">
            <img src="assets/logo-uin.png" alt="Logo"/>
            Tracer Alumni
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDashboard" aria-controls="navbarNavDashboard" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbarNavDashboard" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard_alumni.php">
                        <i class="bi bi-house-door-fill"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="sidebar">
    <div>
        <div class="profile-box">
            <?php
            $foto_path = 'assets/profile_placeholder.png';
            if (!empty($data['foto']) && file_exists($data['foto'])) {
                $foto_path = $data['foto'];
            }
            ?>
            <img src="<?= htmlspecialchars($foto_path) ?>" class="profile-img" alt="Foto Alumni">
            <div class="profile-name"><?= htmlspecialchars($data['nama_lengkap']) ?></div>
            <div class="profile-desc">Alumni<br><?= htmlspecialchars($data['program_studi']) ?></div>
        </div>
        <a href="dashboard_alumni.php" class="sidebar-link active">
            <i class="bi bi-house-door-fill"></i> Dashboard
        </a>
        <a href="profil.php" class="sidebar-link">
            <i class="bi bi-person-badge-fill"></i> Informasi Pribadi
        </a>
        <a href="pekerjaan.php" class="sidebar-link">
            <i class="bi bi-briefcase-fill"></i> Data Pekerjaan
        </a>
        <a href="kuesioner.php" class="sidebar-link">
            <i class="bi bi-list-task"></i> Isi Kuesioner
        </a>
    </div>
    <div class="mb-3">
        <a href="logout.php" class="sidebar-link text-danger">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <h4 class="fw-bold text-success mb-4">Beranda</h4>
    
    <div class="card mb-4 p-4">
        <h2 class="fw-bold mb-2">Selamat Datang, <?= htmlspecialchars(explode(' ', $data['nama_lengkap'])[0] ?? 'Alumni') ?>!</h2>
        <div style="font-size:1.06rem;">
            <p>Selamat datang di sistem tracer alumni. Di sini Anda bisa memperbarui data pribadi, mengisi data pekerjaan, dan mengisi kuesioner tracer study secara online untuk membantu kampus dalam evaluasi kualitas pendidikan alumni.</p>
        </div>
    </div>

    <!-- Area info cards -->
    <div class="row g-3 mb-4">
        <!-- Card Status Pekerjaan -->
        <div class="col-md-6 col-lg-4">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-briefcase-fill text-success me-2" style="font-size: 1.2rem;"></i>
                    <h6 class="mb-0 fw-bold">Status Pekerjaan</h6>
                </div>
                <?php if ($pekerjaan): ?>
                    <div class="d-flex align-items-center mb-2">
                        <span class="status-icon status-complete">
                            <i class="bi bi-check"></i>
                        </span>
                        <strong><?= htmlspecialchars($pekerjaan['status_pekerjaan']) ?></strong>
                    </div>
                    <?php if ($pekerjaan['nama_perusahaan']): ?>
                        <p class="mb-1 text-muted small">
                            <i class="bi bi-building me-1"></i>
                            <?= htmlspecialchars($pekerjaan['nama_perusahaan']) ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($pekerjaan['jabatan']): ?>
                        <p class="mb-1 text-muted small">
                            <i class="bi bi-person-badge me-1"></i>
                            <?= htmlspecialchars($pekerjaan['jabatan']) ?>
                        </p>
                    <?php endif; ?>
                    <div class="mt-auto pt-2">
                        <a href="pekerjaan.php" class="btn btn-outline-success btn-sm w-100">
                            <i class="bi bi-pencil-square me-1"></i>Perbarui Data
                        </a>
                    </div>
                <?php else: ?>
                    <div class="d-flex align-items-center mb-2">
                        <span class="status-icon status-pending">
                            <i class="bi bi-clock"></i>
                        </span>
                        <span class="text-muted">Belum mengisi data pekerjaan</span>
                    </div>
                    <p class="mb-0 text-muted small">Lengkapi data pekerjaan Anda untuk tracer study.</p>
                    <div class="mt-auto pt-2">
                        <a href="pekerjaan.php" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-plus-circle me-1"></i>Isi Data Pekerjaan
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Card Status Kuesioner -->
        <div class="col-md-6 col-lg-4">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-list-task text-success me-2" style="font-size: 1.2rem;"></i>
                    <h6 class="mb-0 fw-bold">Status Kuesioner</h6>
                </div>
                <?php if ($kuesioner): ?>
                    <div class="d-flex align-items-center mb-2">
                        <span class="status-icon status-complete">
                            <i class="bi bi-check"></i>
                        </span>
                        <span>Sudah mengisi kuesioner</span>
                    </div>
                    <p class="mb-0 text-muted small">Terima kasih telah mengisi kuesioner tracer study.</p>
                    <div class="mt-auto pt-2">
                        <a href="kuesioner.php" class="btn btn-outline-success btn-sm w-100">
                            <i class="bi bi-pencil-square me-1"></i>Perbarui Kuesioner
                        </a>
                    </div>
                <?php else: ?>
                    <div class="d-flex align-items-center mb-2">
                        <span class="status-icon status-pending">
                            <i class="bi bi-clock"></i>
                        </span>
                        <span class="text-muted">Belum mengisi kuesioner</span>
                    </div>
                    <p class="mb-0 text-muted small">Mohon bantu kampus dengan mengisi kuesioner tracer study.</p>
                    <div class="mt-auto pt-2">
                        <a href="kuesioner.php" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-plus-circle me-1"></i>Isi Kuesioner
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Card Informasi Profil -->
        <div class="col-md-6 col-lg-4">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-person-badge-fill text-success me-2" style="font-size: 1.2rem;"></i>
                    <h6 class="mb-0 fw-bold">Informasi Profil</h6>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="status-icon status-complete">
                        <i class="bi bi-check"></i>
                    </span>
                    <span>Data profil tersimpan</span>
                </div>
                <p class="mb-0 text-muted small">Perbarui informasi pribadi Anda agar data tracer lebih akurat.</p>
                <div class="mt-auto pt-2">
                    <a href="profil.php" class="btn btn-outline-success btn-sm w-100">
                        <i class="bi bi-pencil-square me-1"></i>Perbarui Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Card -->
    <div class="card p-4">
        <h5 class="fw-bold mb-3">
            <i class="bi bi-graph-up me-2 text-success"></i>Progress Tracer Study
        </h5>
        <?php 
        $progress = 0;
        $total_steps = 3;
        
        // Hitung progress
        $progress++; // Profil selalu ada karena sudah login
        if ($pekerjaan) $progress++;
        if ($kuesioner) $progress++;
        
        $percentage = ($progress / $total_steps) * 100;
        ?>
        
        <div class="progress mb-3" style="height: 10px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        
        <div class="row text-center">
            <div class="col-4">
                <div class="d-flex flex-column align-items-center">
                    <i class="bi bi-person-badge-fill text-success mb-1" style="font-size: 1.5rem;"></i>
                    <small class="fw-bold text-success">Profil</small>
                    <small class="text-muted">Lengkap</small>
                </div>
            </div>
            <div class="col-4">
                <div class="d-flex flex-column align-items-center">
                    <i class="bi bi-briefcase-fill <?= $pekerjaan ? 'text-success' : 'text-muted' ?> mb-1" style="font-size: 1.5rem;"></i>
                    <small class="fw-bold <?= $pekerjaan ? 'text-success' : 'text-muted' ?>">Pekerjaan</small>
                    <small class="text-muted"><?= $pekerjaan ? 'Lengkap' : 'Belum diisi' ?></small>
                </div>
            </div>
            <div class="col-4">
                <div class="d-flex flex-column align-items-center">
                    <i class="bi bi-list-task <?= $kuesioner ? 'text-success' : 'text-muted' ?> mb-1" style="font-size: 1.5rem;"></i>
                    <small class="fw-bold <?= $kuesioner ? 'text-success' : 'text-muted' ?>">Kuesioner</small>
                    <small class="text-muted"><?= $kuesioner ? 'Lengkap' : 'Belum diisi' ?></small>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <strong>Progress: <?= $progress ?>/<?= $total_steps ?> (<?= round($percentage) ?>%)</strong>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>