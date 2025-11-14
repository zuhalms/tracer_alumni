<?php
session_start();
include_once(dirname(__DIR__) . '/config/config.php');

// --- Tambahan: Filter Tahun Lulus
$tahunLulus = isset($_GET['tahun_lulus']) ? intval($_GET['tahun_lulus']) : '';
$where = $tahunLulus ? "WHERE a.tahun_lulus = '$tahunLulus'" : '';

// Query alumni dan data tracer
$query = "
SELECT 
    a.id_alumni, a.nim, a.nama_lengkap, a.fakultas, a.program_studi, a.tahun_lulus, a.foto,
    p.status_pekerjaan, p.nama_perusahaan, p.jabatan, p.tahun_mulai_kerja, p.gaji_pertama, p.relevansi_pekerjaan,
    k.kepuasan_kurikulum, k.kepuasan_dosen, k.kepuasan_fasilitas, k.relevansi_ilmu_kerja, k.kompetensi_bidang
FROM tb_alumni a
LEFT JOIN tb_pekerjaan p ON p.id_alumni = a.id_alumni
LEFT JOIN tb_kuesioner k ON k.id_alumni = a.id_alumni
$where
ORDER BY a.nama_lengkap ASC
";
$result = mysqli_query($conn, $query);

// Hitung total progres
$total_alumni = mysqli_num_rows(mysqli_query($conn, "SELECT id_alumni FROM tb_alumni $where"));
$isi_pekerjaan = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT id_alumni FROM tb_pekerjaan".($tahunLulus?" WHERE id_alumni IN (SELECT id_alumni FROM tb_alumni WHERE tahun_lulus='$tahunLulus')":"")));
$isi_kuesioner = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT id_alumni FROM tb_kuesioner".($tahunLulus?" WHERE id_alumni IN (SELECT id_alumni FROM tb_alumni WHERE tahun_lulus='$tahunLulus')":"")));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../assets/logo-uin.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Alumni - Tracer Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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
            min-height: 100vh;
            margin: 0;
            font-family: 'Montserrat', Arial, sans-serif;
            overflow-x: hidden;
        }
        
        /* Navbar */
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
        
        /* Hamburger Button */
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            color: #197948;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px 10px;
        }
        
        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: #fff;
            border-right: 1.6px solid #e4efea;
            box-shadow: 0 1px 10px #3ead6130;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1040;
            width: 265px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-top: 64px;
            padding-bottom: 100px;
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
        }
        
        .sidebar-content {
            flex: 1;
        }
        
        .profile-box {
            text-align: center;
            padding: 32px 20px 14px 20px;
        }
        .avatar-admin {
            width: 92px;
            height: 92px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #e9f7ef;
            margin-bottom: 8px;
        }
        .admin-name {
            font-weight: 700;
            color: #197948;
            font-size: 1.14rem;
            margin-bottom: 2px;
        }
        .admin-role {
            font-size: 1rem;
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
            transition: 0.18s;
        }
        .sidebar-link.active,
        .sidebar-link:hover {
            background: #dcf8e5;
            color: #258B42;
        }
        .sidebar-link i {
            font-size: 1.15rem;
            margin-right: 11px;
        }
        
        /* Logout Link */
        .logout-link {
            position: fixed;
            bottom: 28px;
            left: 0;
            width: 265px;
            text-align: center;
            z-index: 1041;
            background: #fff;
            padding: 10px 0;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 265px;
            margin-top: 64px;
            padding: 30px 38px;
            min-height: calc(100vh - 64px);
            transition: margin-left 0.3s ease-in-out;
        }
        
        /* Cards */
        .card {
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(34, 139, 34, 0.07);
        }
        .progress-stats-row .card {
            min-height: 120px;
        }
        
        /* Table */
        table thead th {
            background: #e8f5e9;
            color: #258B42;
            vertical-align: middle;
        }
        .table-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e9f7ef;
            margin-right: 7px;
        }
        .table-responsive {
            font-size: 0.97rem;
        }
        
        /* Overlay */
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
        
        /* Responsive */
        @media (max-width: 991px) {
            .hamburger-btn {
                display: block;
            }
            
            .sidebar {
                transform: translateX(-100%);
                width: 100vw;
                padding-bottom: 20px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .logout-link {
                position: relative;
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
            .avatar-admin {
                width: 70px;
                height: 70px;
            }
            .admin-name {
                font-size: 1.1rem;
            }
            .admin-role {
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
        <a class="navbar-brand fw-bold" href="data_alumni.php">
            <img src="../assets/logo-uin.png" alt="Logo Kampus" /> Tracer Alumni (Admin)
        </a>
    </div>
</nav>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-content">
        <div class="profile-box">
            <img src="../assets/admin.png" class="avatar-admin" alt="Admin">
            <div class="admin-name">Administrator</div>
            <div class="admin-role">Tracer Alumni</div>
        </div>
        <div>
            <a href="dashboard_admin.php" class="sidebar-link">
                <i class="bi bi-house-door-fill"></i> Dashboard
            </a>
            <a href="data_alumni.php" class="sidebar-link active">
                <i class="bi bi-speedometer2"></i> Data Alumni
            </a>
            <a href="admin_export.php" class="sidebar-link">
                <i class="bi bi-file-earmark-excel"></i> Export Data
            </a>
        </div>
    </div>
    <div class="logout-link">
        <a href="logout_admin.php" class="btn btn-outline-danger btn-sm my-2 px-4">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <h4 class="fw-bold text-success mb-4">
        <i class="bi bi-speedometer2 me-2"></i>Data Alumni
    </h4>
    
    <!-- Filter Tahun Lulus -->
    <form method="get" class="mb-3">
        <label for="tahun_lulus" class="me-2 fw-semibold">Tahun Lulus:</label>
        <select name="tahun_lulus" id="tahun_lulus" class="form-select d-inline-block" style="width: 180px;">
            <option value="">Semua Tahun</option>
            <?php
            $qTahun = mysqli_query($conn, "SELECT DISTINCT tahun_lulus FROM tb_alumni ORDER BY tahun_lulus DESC");
            while($rowTahun = mysqli_fetch_assoc($qTahun)) {
                $selected = ($tahunLulus == $rowTahun['tahun_lulus'] ? 'selected' : '');
                echo "<option value='{$rowTahun['tahun_lulus']}' $selected>{$rowTahun['tahun_lulus']}</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-success btn-sm ms-2">Filter</button>
        <?php if($tahunLulus): ?>
            <a href="data_alumni.php" class="btn btn-secondary btn-sm ms-1">Reset</a>
        <?php endif; ?>
    </form>
    
    <!-- Statistics Cards -->
    <div class="row g-3 mb-3 progress-stats-row">
        <div class="col-md-4">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-badge-fill text-success me-2" style="font-size: 1.2rem"></i>
                    <div>
                        <div class="fw-bold mb-0"><?= $total_alumni ?></div>
                        <div class="small text-muted">Alumni Terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-briefcase-fill text-success me-2" style="font-size: 1.2rem"></i>
                    <div>
                        <div class="fw-bold mb-0"><?= $isi_pekerjaan ?></div>
                        <div class="small text-muted">Isi Data Pekerjaan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-list-task text-success me-2" style="font-size: 1.2rem"></i>
                    <div>
                        <div class="fw-bold mb-0"><?= $isi_kuesioner ?></div>
                        <div class="small text-muted">Isi Kuesioner</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Table Alumni -->
    <div class="card p-3">
        <div class="card-body">
            <h5 class="card-title mb-3 fw-bold">Data Alumni</h5>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Alumni</th>
                            <th>NIM</th>
                            <th>Fakultas</th>
                            <th>Prodi</th>
                            <th>Tahun Lulus</th>
                            <th>Status Pekerjaan</th>
                            <th>Perusahaan</th>
                            <th>Jabatan</th>
                            <th>Gaji Pertama</th>
                            <th>Relevansi</th>
                            <th>Kuesioner</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php
                                    $foto = (!empty($row['foto']) && file_exists("../" . $row['foto'])) 
                                        ? "../" . $row['foto'] 
                                        : "../assets/profile_placeholder.png";
                                    ?>
                                    <img src="<?= htmlspecialchars($foto) ?>" class="table-avatar" alt="Foto">
                                    <span><?= htmlspecialchars($row['nama_lengkap']) ?></span>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($row['nim']) ?></td>
                            <td><?= htmlspecialchars($row['fakultas']) ?></td>
                            <td><?= htmlspecialchars($row['program_studi']) ?></td>
                            <td><?= htmlspecialchars($row['tahun_lulus']) ?></td>
                            <td>
                                <?php if($row['status_pekerjaan']): ?>
                                    <span class="badge bg-success-subtle text-success"><?= htmlspecialchars($row['status_pekerjaan']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary">Belum Diisi</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['nama_perusahaan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['jabatan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['gaji_pertama'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['relevansi_pekerjaan'] ?? '-') ?></td>
                            <td>
                                <?php if($row['kepuasan_kurikulum']): ?>
                                    <span class="badge bg-success-subtle text-success">Sudah</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary">Belum</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Toggle sidebar
    hamburgerBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
    });
    
    // Close sidebar when overlay clicked
    sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    });
    
    // Close sidebar when menu item clicked (mobile)
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
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
