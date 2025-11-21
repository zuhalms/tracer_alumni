<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login_admin.php'); exit();
}
include '../config/config.php';

// Ambil tahun unik dari database untuk opsi filter
$tahunQuery = mysqli_query($conn, "SELECT DISTINCT tahun_lulus FROM tb_alumni ORDER BY tahun_lulus DESC");
$tahunOptions = [];
while ($row = mysqli_fetch_assoc($tahunQuery)) {
    $tahunOptions[] = $row['tahun_lulus'];
}

$tahunLulus = isset($_GET['tahun_lulus']) ? intval($_GET['tahun_lulus']) : '';
$where = $tahunLulus ? "WHERE a.tahun_lulus = '$tahunLulus'" : '';

$query = "
    SELECT 
        a.nim, a.nama_lengkap, a.fakultas, a.program_studi, a.tahun_masuk, a.tahun_lulus, a.email, a.no_hp, a.alamat,
        p.status_pekerjaan, p.nama_perusahaan, p.jabatan, p.gaji_pertama, p.tahun_mulai_kerja, p.relevansi_pekerjaan,
        k.kepuasan_kurikulum, k.kepuasan_dosen, k.kepuasan_fasilitas, k.relevansi_ilmu_kerja, k.kompetensi_bidang
    FROM tb_alumni a
    LEFT JOIN tb_pekerjaan p ON p.id_alumni = a.id_alumni
    LEFT JOIN tb_kuesioner k ON k.id_alumni = a.id_alumni
    $where
    ORDER BY a.nama_lengkap ASC
";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../assets/logo-uin.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Alumni - Export & Preview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #e8f5e9 35%, #f6fafd 100%);
            font-family: 'Montserrat', Arial, sans-serif;
            min-height: 100vh;
        }
        .main-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(34,139,34,0.07);
            padding: 32px 28px;
            max-width: 1200px;
            margin: 42px auto;
        }
        .header-logo-title {
            display: flex;
            gap: 18px;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }
        .header-logo-title img {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            object-fit: contain;
        }
        .table-responsive {
            font-size: 0.96rem;
        }
        .icon-succ {
            color: #197948;
            font-size: 1.2rem;
            vertical-align: middle;
        }
        .filter-bar {
            background: #e8f5e9;
            border-radius: 10px;
            padding: 16px 18px;
            box-shadow: 0 2px 8px rgba(34,139,34,0.08);
            margin-bottom: 22px;
        }
        .btn-success {
            background-color: #197948;
            border-color: #197948;
        }
        .btn-success:hover {
            background-color: #155c29;
            border-color: #155c29;
        }
        .download-btn {
            box-shadow: 0 4px 12px rgba(25,121,72,.12);
            font-weight: 600;
        }
        .table th, .table td {
            vertical-align: middle !important;
        }
        @media (max-width: 991px) {
            .main-box {
                padding: 18px 4px;
                max-width: 100%;
            }
            .table-responsive {
                font-size: 0.92rem;
            }
            .header-logo-title img { width: 32px; height:32px;}
            .header-logo-title h2 {font-size:1.05rem;}
            .filter-bar {padding:10px;}
        }
        @media (max-width: 600px) {
            .main-box {padding: 5px 0;}
            .table-responsive {font-size:0.8rem;}
        }
    </style>
</head>
<body>
    <div class="main-box">
        <div class="header-logo-title">
            <img src="../assets/logo-uin.png" alt="Logo Kampus"/>
            <h2 class="fw-bold text-success">Tracer Alumni Export</h2>
        </div>
        <div class="mb-3">
            <a href="dashboard_admin.php" class="btn btn-outline-success">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Dashboard
            </a>
        </div>
        <div class="filter-bar mb-3">
            <form method="get" class="row row-cols-lg-auto g-2 align-items-center">
                <div class="col-auto">
                    <label for="tahun_lulus" class="fw-semibold mb-1">Tahun Lulus:</label>
                    <select name="tahun_lulus" id="tahun_lulus" class="form-select">
                        <option value="">Semua Tahun</option>
                        <?php foreach ($tahunOptions as $tahun): ?>
                            <option value="<?= $tahun ?>" <?= $tahun == $tahunLulus ? 'selected' : '' ?>><?= $tahun ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-search icon-succ"></i> Lihat Data
                    </button>
                </div>
                <?php if (!empty($result) && mysqli_num_rows($result) > 0): ?>
                <div class="col-auto">
                    <a href="export_alumni.php?tahun_lulus=<?= $tahunLulus ?>" class="btn btn-success download-btn">
                        <i class="bi bi-download me-1"></i> Download CSV
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>
        <div class="table-responsive" style="max-height:62vh; overflow:auto;">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Fakultas</th>
                        <th>Prodi</th>
                        <th>Tahun Masuk</th>
                        <th>Tahun Lulus</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Status Kerja</th>
                        <th>Perusahaan</th>
                        <th>Jabatan</th>
                        <th>Gaji Pertama</th>
                        <th>Tahun Mulai Kerja</th>
                        <th>Relevansi Kerja</th>
                        <th>Kepuasan Kurikulum</th>
                        <th>Kepuasan Dosen</th>
                        <th>Kepuasan Fasilitas</th>
                        <th>Relevansi Ilmu-Kerja</th>
                        <th>Kompetensi Bidang</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nim']) ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($row['fakultas']) ?></td>
                            <td><?= htmlspecialchars($row['program_studi']) ?></td>
                            <td><?= htmlspecialchars($row['tahun_masuk']) ?></td>
                            <td><?= htmlspecialchars($row['tahun_lulus']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['no_hp']) ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['status_pekerjaan']) ?></td>
                            <td><?= htmlspecialchars($row['nama_perusahaan']) ?></td>
                            <td><?= htmlspecialchars($row['jabatan']) ?></td>
                            <td><?= htmlspecialchars($row['gaji_pertama']) ?></td>
                            <td><?= htmlspecialchars($row['tahun_mulai_kerja']) ?></td>
                            <td><?= htmlspecialchars($row['relevansi_pekerjaan']) ?></td>
                            <td><?= htmlspecialchars($row['kepuasan_kurikulum']) ?></td>
                            <td><?= htmlspecialchars($row['kepuasan_dosen']) ?></td>
                            <td><?= htmlspecialchars($row['kepuasan_fasilitas']) ?></td>
                            <td><?= htmlspecialchars($row['relevansi_ilmu_kerja']) ?></td>
                            <td><?= htmlspecialchars($row['kompetensi_bidang']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="20" class="text-center text-muted">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            Data tidak ditemukan
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
