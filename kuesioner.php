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

// Cek apakah sudah pernah mengisi kuesioner
$cek_kuesioner = mysqli_query($conn, "SELECT * FROM tb_kuesioner WHERE id_alumni = '$id_alumni'");
$sudah_isi = mysqli_num_rows($cek_kuesioner) > 0;
$data_kuesioner = $sudah_isi ? mysqli_fetch_assoc($cek_kuesioner) : null;

// Proses penyimpanan kuesioner jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kepuasan_kurikulum = (int)$_POST['kepuasan_kurikulum'];
    $kepuasan_dosen = (int)$_POST['kepuasan_dosen'];
    $kepuasan_fasilitas = (int)$_POST['kepuasan_fasilitas'];
    $relevansi_ilmu_kerja = (int)$_POST['relevansi_ilmu_kerja'];
    $kompetensi_bidang = (int)$_POST['kompetensi_bidang'];
    $saran_perbaikan = mysqli_real_escape_string($conn, $_POST['saran_perbaikan']);

    if ($sudah_isi) {
        // Update data kuesioner yang sudah ada
        $query = "UPDATE tb_kuesioner SET 
            kepuasan_kurikulum = $kepuasan_kurikulum,
            kepuasan_dosen = $kepuasan_dosen,
            kepuasan_fasilitas = $kepuasan_fasilitas,
            relevansi_ilmu_kerja = $relevansi_ilmu_kerja,
            kompetensi_bidang = $kompetensi_bidang,
            saran_perbaikan = '$saran_perbaikan'
            WHERE id_alumni = '$id_alumni'";
        $success_msg = "Kuesioner berhasil diperbarui.";
    } else {
        // Insert data kuesioner baru
        $query = "INSERT INTO tb_kuesioner 
            (id_alumni, kepuasan_kurikulum, kepuasan_dosen, kepuasan_fasilitas, relevansi_ilmu_kerja, kompetensi_bidang, saran_perbaikan) 
            VALUES 
            ('$id_alumni', $kepuasan_kurikulum, $kepuasan_dosen, $kepuasan_fasilitas, $relevansi_ilmu_kerja, $kompetensi_bidang, '$saran_perbaikan')";
        $success_msg = "Terima kasih telah mengisi kuesioner.";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: kuesioner.php?success=1");
        exit();
    } else {
        $error_msg = "Gagal menyimpan data, silakan coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../assets/logo-uin.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kuesioner Tracer Study - Tracer Alumni</title>
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
        <a href="pekerjaan.php" class="sidebar-link">
              <i class="bi bi-briefcase-fill"></i> Data Pekerjaan
        </a>
        <a href="kuesioner.php" class="sidebar-link active">
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
            <i class="bi bi-list-task me-2"></i>Kuesioner Tracer Study
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
                <i class="bi bi-check-circle-fill me-2"></i>'.($sudah_isi ? 'Kuesioner berhasil diperbarui.' : 'Terima kasih telah mengisi kuesioner.').'
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
                Silakan isi kuesioner berikut untuk membantu kampus dalam evaluasi kualitas pendidikan. 
                <?= $sudah_isi ? 'Anda dapat memperbarui jawaban Anda kapan saja.' : '' ?>
            </p>

            <form method="POST" action="kuesioner.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kepuasan terhadap Kurikulum</label>
                        <select name="kepuasan_kurikulum" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="1" <?= ($data_kuesioner && $data_kuesioner['kepuasan_kurikulum'] == 1) ? 'selected' : '' ?>>Sangat Tidak Puas</option>
                            <option value="2" <?= ($data_kuesioner && $data_kuesioner['kepuasan_kurikulum'] == 2) ? 'selected' : '' ?>>Tidak Puas</option>
                            <option value="3" <?= ($data_kuesioner && $data_kuesioner['kepuasan_kurikulum'] == 3) ? 'selected' : '' ?>>Cukup</option>
                            <option value="4" <?= ($data_kuesioner && $data_kuesioner['kepuasan_kurikulum'] == 4) ? 'selected' : '' ?>>Puas</option>
                            <option value="5" <?= ($data_kuesioner && $data_kuesioner['kepuasan_kurikulum'] == 5) ? 'selected' : '' ?>>Sangat Puas</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kepuasan terhadap Dosen</label>
                        <select name="kepuasan_dosen" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="1" <?= ($data_kuesioner && $data_kuesioner['kepuasan_dosen'] == 1) ? 'selected' : '' ?>>Sangat Tidak Puas</option>
                            <option value="2" <?= ($data_kuesioner && $data_kuesioner['kepuasan_dosen'] == 2) ? 'selected' : '' ?>>Tidak Puas</option>
                            <option value="3" <?= ($data_kuesioner && $data_kuesioner['kepuasan_dosen'] == 3) ? 'selected' : '' ?>>Cukup</option>
                            <option value="4" <?= ($data_kuesioner && $data_kuesioner['kepuasan_dosen'] == 4) ? 'selected' : '' ?>>Puas</option>
                            <option value="5" <?= ($data_kuesioner && $data_kuesioner['kepuasan_dosen'] == 5) ? 'selected' : '' ?>>Sangat Puas</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kepuasan terhadap Fasilitas</label>
                        <select name="kepuasan_fasilitas" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="1" <?= ($data_kuesioner && $data_kuesioner['kepuasan_fasilitas'] == 1) ? 'selected' : '' ?>>Sangat Tidak Puas</option>
                            <option value="2" <?= ($data_kuesioner && $data_kuesioner['kepuasan_fasilitas'] == 2) ? 'selected' : '' ?>>Tidak Puas</option>
                            <option value="3" <?= ($data_kuesioner && $data_kuesioner['kepuasan_fasilitas'] == 3) ? 'selected' : '' ?>>Cukup</option>
                            <option value="4" <?= ($data_kuesioner && $data_kuesioner['kepuasan_fasilitas'] == 4) ? 'selected' : '' ?>>Puas</option>
                            <option value="5" <?= ($data_kuesioner && $data_kuesioner['kepuasan_fasilitas'] == 5) ? 'selected' : '' ?>>Sangat Puas</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Relevansi Ilmu dengan Pekerjaan</label>
                        <select name="relevansi_ilmu_kerja" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="1" <?= ($data_kuesioner && $data_kuesioner['relevansi_ilmu_kerja'] == 1) ? 'selected' : '' ?>>Sangat Tidak Relevan</option>
                            <option value="2" <?= ($data_kuesioner && $data_kuesioner['relevansi_ilmu_kerja'] == 2) ? 'selected' : '' ?>>Tidak Relevan</option>
                            <option value="3" <?= ($data_kuesioner && $data_kuesioner['relevansi_ilmu_kerja'] == 3) ? 'selected' : '' ?>>Cukup Relevan</option>
                            <option value="4" <?= ($data_kuesioner && $data_kuesioner['relevansi_ilmu_kerja'] == 4) ? 'selected' : '' ?>>Relevan</option>
                            <option value="5" <?= ($data_kuesioner && $data_kuesioner['relevansi_ilmu_kerja'] == 5) ? 'selected' : '' ?>>Sangat Relevan</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kompetensi Bidang</label>
                        <select name="kompetensi_bidang" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="1" <?= ($data_kuesioner && $data_kuesioner['kompetensi_bidang'] == 1) ? 'selected' : '' ?>>Sangat Tidak Kompeten</option>
                            <option value="2" <?= ($data_kuesioner && $data_kuesioner['kompetensi_bidang'] == 2) ? 'selected' : '' ?>>Tidak Kompeten</option>
                            <option value="3" <?= ($data_kuesioner && $data_kuesioner['kompetensi_bidang'] == 3) ? 'selected' : '' ?>>Cukup Kompeten</option>
                            <option value="4" <?= ($data_kuesioner && $data_kuesioner['kompetensi_bidang'] == 4) ? 'selected' : '' ?>>Kompeten</option>
                            <option value="5" <?= ($data_kuesioner && $data_kuesioner['kompetensi_bidang'] == 5) ? 'selected' : '' ?>>Sangat Kompeten</option>
                        </select>
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label">Saran dan Masukan</label>
                        <textarea name="saran_perbaikan" class="form-control" rows="4" placeholder="Masukkan saran dan masukan Anda untuk perbaikan kampus..."><?= $data_kuesioner ? htmlspecialchars($data_kuesioner['saran_perbaikan']) : '' ?></textarea>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-2"></i><?= $sudah_isi ? 'Update Kuesioner' : 'Simpan Kuesioner' ?>
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
</body>
</html>