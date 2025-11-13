<?php
session_start();
include_once(dirname(__DIR__) . '/config/config.php');

// --- Tambahan: Filter Tahun Lulus
$tahunLulus = isset($_GET['tahun_lulus']) ? intval($_GET['tahun_lulus']) : '';
$where = $tahunLulus ? "WHERE a.tahun_lulus = '$tahunLulus'" : '';
// (optional) Cek login khusus admin di sini

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
    <title>Dashboard Admin - Tracer Alumni</title>
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
            height:40px; width:40px; object-fit:contain; border-radius:6px; border:none; background:transparent;
        }
        .sidebar {
            min-height: 100vh;
            background: #fff;
            border-right: 1.6px solid #e4efea;
            box-shadow: 0 1px 10px #3ead6130;
            position: fixed;
            left: 0; top: 64px; bottom: 0; z-index:1040; width: 230px;
            display: flex; flex-direction: column; justify-content: space-between; padding:0;
        }
        .main-content {
            margin-left: 230px;
            padding: 88px 38px 30px 38px;
        }
        .avatar-admin {
            width: 92px;
            height: 92px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #e9f7ef;
            margin-bottom: 8px;
        }
        .profile-box {
            text-align:center;
            padding: 32px 0 14px 0;
        }
        .admin-name {
            font-weight:700; color:#197948; font-size:1.14rem; margin-bottom: 2px;
        }
        .admin-role { font-size:1rem; color:#7fa882; }
        .sidebar-link {
            display: flex; align-items: center;
            color: #222; background: #f7fcfa;
            border: none; padding: 14px 28px; margin-bottom: 4px;
            border-radius: 8px 0 0 8px;
            text-decoration: none; font-weight:500; transition:.18s;
        }
        .sidebar-link.active, .sidebar-link:hover {
            background: #dcf8e5; color: #258B42;
        }
        .sidebar-link i { font-size:1.15rem; margin-right:11px; }
        .card {
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(34,139,34,0.07);
        }
        .progress-stats-row .card {
            min-height: 120px;
        }
        table thead th {
            background: #e8f5e9;
            color: #258B42;
            vertical-align: middle;
        }
        .table-avatar {
            width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border:2px solid #e9f7ef;
            margin-right: 7px;
        }
        @media (max-width: 900px) {
            .sidebar { width: 100vw; position: relative; top:0; border-radius: 0; min-height: auto; box-shadow: none; }
            .main-content { margin-left: 0; padding: 20px 6px 18px 6px; }
            .navbar-brand img { height:32px; width:32px; }
        }
        .table-responsive { font-size:.97rem; }
                
        .logout-link {
            position: fixed;
            bottom: 28px; left: 0; width: 265px;
            text-align: center;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid px-3">
        <a class="navbar-brand fw-bold" href="data_alumni.php">
            <img src="../assets/logo-uin.png" alt="Logo Kampus"/> Tracer Alumni (Admin)
        </a>
    <div class="logout-link">
        <a href="logout_admin.php" class="btn btn-outline-danger btn-sm my-2 px-4"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
    </div>
    </div>
</nav>

<div class="sidebar">
    <div>
        <div class="profile-box">
            <img src="../assets/admin.png" class="avatar-admin" alt="Admin">
            <div class="admin-name">Administrator</div>
            <div class="admin-role">Tracer Alumni</div>
        </div>
        <!-- Sidebar menu, fix: no blue link for Dashboard -->
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
    <div class="mb-3"></div>
</div>

<div class="main-content">
    <h4 class="fw-bold text-success mb-4"><i class="bi bi-speedometer2 me-2"></i>Data Alumni</h4>
        <!-- FILTER TAHUN LULUS -->
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
    <!-- Table alumni -->
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
                                    // Path relatif dari admin/dashboard_admin.php ke folder upload di root
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>