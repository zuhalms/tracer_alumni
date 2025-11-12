<?php
session_start();
include 'config/config.php';

// Cek login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php?error=not_logged_in");
    exit();
}

$id_alumni = $_SESSION['id_alumni'];

// Ambil data alumni dari database
$query = "SELECT * FROM tb_alumni WHERE id_alumni = '$id_alumni'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $fakultas = mysqli_real_escape_string($conn, $_POST['fakultas']);
    $program_studi = mysqli_real_escape_string($conn, $_POST['program_studi']);
    $tahun_masuk = mysqli_real_escape_string($conn, $_POST['tahun_masuk']);
    $tahun_lulus = mysqli_real_escape_string($conn, $_POST['tahun_lulus']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    $foto_path = $data['foto']; // Default ke foto lama
    
    // Proses upload foto jika ada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (in_array($_FILES['foto']['type'], $allowed_types) && $_FILES['foto']['size'] <= $max_size) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $new_filename = 'foto_' . $id_alumni . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                // Hapus foto lama jika ada
                if ($data['foto'] && file_exists($data['foto'])) {
                    unlink($data['foto']);
                }
                $foto_path = $upload_path;
            } else {
                $error_msg = "Gagal mengupload foto.";
            }
        } else {
            $error_msg = "Format foto tidak valid atau ukuran terlalu besar (max 2MB).";
        }
    }

    if (!isset($error_msg)) {
        // Update data ke database
        $update = "UPDATE tb_alumni SET 
            nama_lengkap='$nama_lengkap',
            fakultas='$fakultas',
            program_studi='$program_studi',
            tahun_masuk='$tahun_masuk',
            tahun_lulus='$tahun_lulus',
            email='$email',
            no_hp='$no_hp',
            alamat='$alamat',
            foto='$foto_path'
            WHERE id_alumni='$id_alumni'";

        if (mysqli_query($conn, $update)) {
            $_SESSION['nama_lengkap'] = $nama_lengkap; // Update session nama
            header("Location: profil.php?success=update");
            exit();
        } else {
            $error_msg = "Gagal mengupdate profil. Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil Alumni - Tracer Alumni</title>
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
        .foto-preview {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            border: 3px solid #e9f7ef;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-upload {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            color: #6c757d;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-upload:hover {
            border-color: #198754;
            background: #d1e7dd;
            color: #198754;
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
            .foto-preview {
                width: 150px;
                height: 150px;
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
            <img src="<?= htmlspecialchars($data['foto'] ?? 'assets/profile_placeholder.png') ?>" class="profile-img" alt="Foto Alumni">
            <div class="profile-name"><?= htmlspecialchars($data['nama_lengkap']) ?></div>
            <div class="profile-desc">Alumni<br><?= htmlspecialchars($data['program_studi']) ?></div>
        </div>
        <a href="dashboard_alumni.php" class="sidebar-link">
            <i class="bi bi-house-door-fill"></i> Dashboard
        </a>
        <a href="profil.php" class="sidebar-link active">
            <i class="bi bi-person-badge-fill"></i> Informasi Pribadi
        </a>
        <a href="kuesioner.php" class="sidebar-link">
            <i class="bi bi-list-task"></i> Isi Kuesioner
        </a>
    </div>
    <div class="mb-3">
        <a href="logout.php" class="sidebar-link text-danger">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
        <a href="pekerjaan.php" class="sidebar-link">
    <i class="bi bi-briefcase-fill"></i> Data Pekerjaan
</a>
    </div>
</div>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold text-success mb-4">
                <i class="bi bi-person-badge-fill me-2"></i>Profil Alumni
            </h4>
            
            <?php
            if (isset($_GET['success']) && $_GET['success'] == 'update') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>Profil berhasil diperbarui.
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

            <div class="row">
                <!-- Foto Profil -->
                <div class="col-lg-4 mb-4">
                    <div class="card p-4 text-center">
                        <h6 class="card-title mb-3">Foto Profil</h6>
                        <div class="mb-3">
                            <img src="<?= htmlspecialchars($data['foto'] ?? 'assets/profile_placeholder.png') ?>" 
                                 class="foto-preview" alt="Foto Profil" id="preview-foto">
                        </div>
                        <form method="POST" enctype="multipart/form-data" id="form-foto">
                            <input type="hidden" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>">
                            <input type="hidden" name="fakultas" value="<?= htmlspecialchars($data['fakultas']) ?>">
                            <input type="hidden" name="program_studi" value="<?= htmlspecialchars($data['program_studi']) ?>">
                            <input type="hidden" name="tahun_masuk" value="<?= htmlspecialchars($data['tahun_masuk']) ?>">
                            <input type="hidden" name="tahun_lulus" value="<?= htmlspecialchars($data['tahun_lulus']) ?>">
                            <input type="hidden" name="email" value="<?= htmlspecialchars($data['email']) ?>">
                            <input type="hidden" name="no_hp" value="<?= htmlspecialchars($data['no_hp']) ?>">
                            <input type="hidden" name="alamat" value="<?= htmlspecialchars($data['alamat']) ?>">
                            
                            <label for="foto" class="btn-upload d-block">
                                <i class="bi bi-camera-fill me-2"></i>Pilih Foto
                            </label>
                            <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">
                            <small class="text-muted mt-2 d-block">Format: JPG, PNG. Max: 2MB</small>
                        </form>
                    </div>
                </div>

                <!-- Form Data -->
                <div class="col-lg-8">
                    <div class="card p-4">
                        <h6 class="card-title mb-3">Informasi Pribadi</h6>
                        <form method="POST" action="profil.php">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">NIM</label>
                                <input type="text" class="form-control" name="nim" value="<?=htmlspecialchars($data['nim'])?>" disabled />
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_lengkap" value="<?=htmlspecialchars($data['nama_lengkap'])?>" required />
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Fakultas</label>
                                <select class="form-select" name="fakultas" required>
                                    <option value="">-- Pilih Fakultas --</option>
                                    <?php
                                    $fakultas_arr = [
                                        'Sains dan Teknologi',
                                        'Ekonomi dan Bisnis Islam',
                                        'Ushuluddin, Filsafat dan Politik',
                                        'Fakultas Kedokteran dan Ilmu Kesehatan',
                                        'Syariah dan Hukum',
                                        'Tarbiyah dan Keguruan',
                                        'Dakwah dan Komunikasi',
                                        'Adab dan Humaniora'
                                    ];
                                    foreach ($fakultas_arr as $fak) {
                                        $selected = ($data['fakultas'] == $fak) ? 'selected' : '';
                                        echo "<option value=\"$fak\" $selected>$fak</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Program Studi</label>
                                    <input type="text" class="form-control" name="program_studi" value="<?=htmlspecialchars($data['program_studi'])?>" required />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-semibold">Tahun Masuk</label>
                                    <input type="number" class="form-control" name="tahun_masuk" value="<?=htmlspecialchars($data['tahun_masuk'])?>" min="2000" max="2099" />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-semibold">Tahun Lulus</label>
                                    <input type="number" class="form-control" name="tahun_lulus" value="<?=htmlspecialchars($data['tahun_lulus'])?>" min="2000" max="2099" required />
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" name="email" value="<?=htmlspecialchars($data['email'])?>" required />
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">No. HP/WhatsApp</label>
                                <input type="text" class="form-control" name="no_hp" value="<?=htmlspecialchars($data['no_hp'])?>" />
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Alamat Domisili</label>
                                <textarea class="form-control" name="alamat" rows="3"><?=htmlspecialchars($data['alamat'])?></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-lg me-2"></i>Update Profil
                                </button>
                                <a href="dashboard_alumni.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Preview foto
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-foto').src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        // Auto submit form foto
        document.getElementById('form-foto').submit();
    }
});
</script>
</body>
</html>