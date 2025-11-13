<?php
session_start();
include 'config/config.php';

// Cek login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php?error=not_logged_in");
    exit();
}

$id_alumni = $_SESSION['id_alumni'];

// Ambil data alumni untuk sidebar
$data_alumni = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_alumni WHERE id_alumni=$id_alumni"));

// Cek apakah sudah pernah mengisi data pekerjaan
$cek_pekerjaan = mysqli_query($conn, "SELECT * FROM tb_pekerjaan WHERE id_alumni = '$id_alumni'");
$sudah_isi = mysqli_num_rows($cek_pekerjaan) > 0;
$data_pekerjaan = $sudah_isi ? mysqli_fetch_assoc($cek_pekerjaan) : null;

// Proses penyimpanan data pekerjaan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status_pekerjaan = mysqli_real_escape_string($conn, $_POST['status_pekerjaan']);
    $nama_perusahaan = mysqli_real_escape_string($conn, $_POST['nama_perusahaan']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $bidang_pekerjaan = mysqli_real_escape_string($conn, $_POST['bidang_pekerjaan']);
    $tahun_mulai_kerja = $_POST['tahun_mulai_kerja'] ? (int)$_POST['tahun_mulai_kerja'] : NULL;
    $gaji_pertama = mysqli_real_escape_string($conn, $_POST['gaji_pertama']);
    $relevansi_pekerjaan = mysqli_real_escape_string($conn, $_POST['relevansi_pekerjaan']);
    $lama_mendapat_kerja = mysqli_real_escape_string($conn, $_POST['lama_mendapat_kerja']);

    if ($sudah_isi) {
        // Update data pekerjaan yang sudah ada
        $query = "UPDATE tb_pekerjaan SET 
            status_pekerjaan = '$status_pekerjaan',
            nama_perusahaan = '$nama_perusahaan',
            jabatan = '$jabatan',
            bidang_pekerjaan = '$bidang_pekerjaan',
            tahun_mulai_kerja = " . ($tahun_mulai_kerja ? $tahun_mulai_kerja : 'NULL') . ",
            gaji_pertama = '$gaji_pertama',
            relevansi_pekerjaan = '$relevansi_pekerjaan',
            lama_mendapat_kerja = '$lama_mendapat_kerja'
            WHERE id_alumni = '$id_alumni'";
        $success_msg = "Data pekerjaan berhasil diperbarui.";
    } else {
        // Insert data pekerjaan baru
        $query = "INSERT INTO tb_pekerjaan 
            (id_alumni, status_pekerjaan, nama_perusahaan, jabatan, bidang_pekerjaan, tahun_mulai_kerja, gaji_pertama, relevansi_pekerjaan, lama_mendapat_kerja) 
            VALUES 
            ('$id_alumni', '$status_pekerjaan', '$nama_perusahaan', '$jabatan', '$bidang_pekerjaan', " . ($tahun_mulai_kerja ? $tahun_mulai_kerja : 'NULL') . ", '$gaji_pertama', '$relevansi_pekerjaan', '$lama_mendapat_kerja')";
        $success_msg = "Data pekerjaan berhasil disimpan.";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: pekerjaan.php?success=1");
        exit();
    } else {
        $error_msg = "Gagal menyimpan data, silakan coba lagi. Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../assets/logo-uin.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Pekerjaan - Tracer Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        html, body { height: 100%; }
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
            border: none;
        }
        .form-label {
            font-weight: 600;
            color: #197948;
            margin-bottom: 8px;
        }
        .form-select, .form-control {
            border: 2px solid #e9f7ef;
            border-radius: 8px;
            padding: 10px 12px;
            transition: all .15s ease;
        }
        .form-select:focus, .form-control:focus {
            border-color: #197948;
            box-shadow: 0 0 0 0.2rem rgba(25,121,72,0.15);
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }
        .status-pending {
            background: #fff3cd;
            color: #664d03;
        }
        .conditional-fields {
            display: none;
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
                    <a class="nav-link" href="dashboard_alumni.php">
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
            if (!empty($data_alumni['foto']) && file_exists($data_alumni['foto'])) {
                $foto_path = $data_alumni['foto'];
            }
            ?>
            <img src="<?= htmlspecialchars($foto_path) ?>" class="profile-img" alt="Foto Alumni">
            <div class="profile-name"><?= htmlspecialchars($data_alumni['nama_lengkap']) ?></div>
            <div class="profile-desc">Alumni<br><?= htmlspecialchars($data_alumni['program_studi']) ?></div>
        </div>
        <a href="dashboard_alumni.php" class="sidebar-link">
            <i class="bi bi-house-door-fill"></i> Dashboard
        </a>
        <a href="profil.php" class="sidebar-link">
            <i class="bi bi-person-badge-fill"></i> Informasi Pribadi
        </a>
        <a href="pekerjaan.php" class="sidebar-link active">
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-success mb-0">
            <i class="bi bi-briefcase-fill me-2"></i>Data Pekerjaan Alumni
        </h4>
        <?php if ($sudah_isi): ?>
            <span class="status-badge status-completed">
                <i class="bi bi-check-circle-fill"></i>Sudah Diisi
            </span>
        <?php else: ?>
            <span class="status-badge status-pending">
                <i class="bi bi-clock-fill"></i>Belum Diisi
            </span>
        <?php endif; ?>
    </div>

    <?php
    if (isset($_GET['success']) && $_GET['success'] == '1') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>Data pekerjaan berhasil disimpan.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
    }
    if (isset($error_msg)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>'.$error_msg.'
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
    }
    ?>

    <div class="card p-4">
        <div class="card-body">
            <p class="text-muted mb-4">
                <i class="bi bi-info-circle me-2"></i>
                Silakan isi data pekerjaan Anda saat ini untuk membantu kampus dalam tracer study alumni.
            </p>

            <form method="POST" action="pekerjaan.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Pekerjaan <span class="text-danger">*</span></label>
                        <select name="status_pekerjaan" class="form-select" required id="status_pekerjaan">
                            <option value="">-- Pilih Status --</option>
                            <option value="Bekerja" <?= ($data_pekerjaan && $data_pekerjaan['status_pekerjaan'] == 'Bekerja') ? 'selected' : '' ?>>Bekerja</option>
                            <option value="Wirausaha" <?= ($data_pekerjaan && $data_pekerjaan['status_pekerjaan'] == 'Wirausaha') ? 'selected' : '' ?>>Wirausaha</option>
                            <option value="Melanjutkan Studi" <?= ($data_pekerjaan && $data_pekerjaan['status_pekerjaan'] == 'Melanjutkan Studi') ? 'selected' : '' ?>>Melanjutkan Studi</option>
                            <option value="Belum Bekerja" <?= ($data_pekerjaan && $data_pekerjaan['status_pekerjaan'] == 'Belum Bekerja') ? 'selected' : '' ?>>Belum Bekerja</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lama Mendapat Pekerjaan Pertama</label>
                        <select name="lama_mendapat_kerja" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="< 3 bulan" <?= ($data_pekerjaan && $data_pekerjaan['lama_mendapat_kerja'] == '< 3 bulan') ? 'selected' : '' ?>>Kurang dari 3 bulan</option>
                            <option value="3-6 bulan" <?= ($data_pekerjaan && $data_pekerjaan['lama_mendapat_kerja'] == '3-6 bulan') ? 'selected' : '' ?>>3-6 bulan</option>
                            <option value="6-12 bulan" <?= ($data_pekerjaan && $data_pekerjaan['lama_mendapat_kerja'] == '6-12 bulan') ? 'selected' : '' ?>>6-12 bulan</option>
                            <option value="> 12 bulan" <?= ($data_pekerjaan && $data_pekerjaan['lama_mendapat_kerja'] == '> 12 bulan') ? 'selected' : '' ?>>Lebih dari 12 bulan</option>
                        </select>
                    </div>
                </div>

                <div id="work-fields" class="conditional-fields">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Perusahaan/Instansi</label>
                            <input type="text" class="form-control" name="nama_perusahaan" value="<?= $data_pekerjaan ? htmlspecialchars($data_pekerjaan['nama_perusahaan']) : '' ?>" placeholder="Contoh: PT. ABC Indonesia">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" class="form-control" name="jabatan" value="<?= $data_pekerjaan ? htmlspecialchars($data_pekerjaan['jabatan']) : '' ?>" placeholder="Contoh: Software Developer">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bidang Pekerjaan</label>
                            <input type="text" class="form-control" name="bidang_pekerjaan" value="<?= $data_pekerjaan ? htmlspecialchars($data_pekerjaan['bidang_pekerjaan']) : '' ?>" placeholder="Contoh: Teknologi Informasi">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Mulai Kerja</label>
                            <input type="number" class="form-control" name="tahun_mulai_kerja" value="<?= $data_pekerjaan ? htmlspecialchars($data_pekerjaan['tahun_mulai_kerja']) : '' ?>" min="2000" max="2099" placeholder="2023">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gaji Pertama</label>
                            <select name="gaji_pertama" class="form-select">
                                <option value="">-- Pilih Range Gaji --</option>
                                <option value="< 3 juta" <?= ($data_pekerjaan && $data_pekerjaan['gaji_pertama'] == '< 3 juta') ? 'selected' : '' ?>>Kurang dari 3 juta</option>
                                <option value="3-5 juta" <?= ($data_pekerjaan && $data_pekerjaan['gaji_pertama'] == '3-5 juta') ? 'selected' : '' ?>>3-5 juta</option>
                                <option value="5-7 juta" <?= ($data_pekerjaan && $data_pekerjaan['gaji_pertama'] == '5-7 juta') ? 'selected' : '' ?>>5-7 juta</option>
                                <option value="7-10 juta" <?= ($data_pekerjaan && $data_pekerjaan['gaji_pertama'] == '7-10 juta') ? 'selected' : '' ?>>7-10 juta</option>
                                <option value="> 10 juta" <?= ($data_pekerjaan && $data_pekerjaan['gaji_pertama'] == '> 10 juta') ? 'selected' : '' ?>>Lebih dari 10 juta</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Relevansi Pekerjaan dengan Jurusan</label>
                            <select name="relevansi_pekerjaan" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="Sangat Relevan" <?= ($data_pekerjaan && $data_pekerjaan['relevansi_pekerjaan'] == 'Sangat Relevan') ? 'selected' : '' ?>>Sangat Relevan</option>
                                <option value="Relevan" <?= ($data_pekerjaan && $data_pekerjaan['relevansi_pekerjaan'] == 'Relevan') ? 'selected' : '' ?>>Relevan</option>
                                <option value="Cukup Relevan" <?= ($data_pekerjaan && $data_pekerjaan['relevansi_pekerjaan'] == 'Cukup Relevan') ? 'selected' : '' ?>>Cukup Relevan</option>
                                <option value="Tidak Relevan" <?= ($data_pekerjaan && $data_pekerjaan['relevansi_pekerjaan'] == 'Tidak Relevan') ? 'selected' : '' ?>>Tidak Relevan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-2"></i><?= $sudah_isi ? 'Update Data' : 'Simpan Data' ?>
                    </button>
                    <a href="dashboard_alumni.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('status_pekerjaan').addEventListener('change', function() {
    const workFields = document.getElementById('work-fields');
    const value = this.value;
    
    if (value === 'Bekerja' || value === 'Wirausaha') {
        workFields.style.display = 'block';
        workFields.classList.add('conditional-fields');
    } else {
        workFields.style.display = 'none';
        workFields.classList.remove('conditional-fields');
    }
});

// Trigger on page load jika sudah ada data
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status_pekerjaan');
    if (statusSelect.value) {
        statusSelect.dispatchEvent(new Event('change'));
    }
});
</script>
</body>
</html>