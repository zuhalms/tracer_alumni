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
    <meta charset="UTF-8">
    <title>Data Alumni - Export & Preview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body class="p-3">
    <form method="get" class="mb-3">
        <label for="tahun_lulus"><b>Pilih Tahun Lulus:</b></label>
        <select name="tahun_lulus" id="tahun_lulus" class="form-select" style="max-width:200px; display:inline-block;">
            <option value="">-- Semua Tahun --</option>
            <?php foreach ($tahunOptions as $tahun): ?>
                <option value="<?= $tahun ?>" <?= $tahun == $tahunLulus ? 'selected' : '' ?>><?= $tahun ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Lihat Data</button>
        <?php if (!empty($result) && mysqli_num_rows($result) > 0): ?>
            <a href="export_alumni.php?tahun_lulus=<?= $tahunLulus ?>" class="btn btn-success">Download CSV</a>
        <?php endif; ?>
    </form>
    <div style="max-height: 60vh; overflow: auto;">
        <table class="table table-striped table-bordered table-sm align-middle">
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
                <tr><td colspan="20" class="text-center text-muted">Data tidak ditemukan</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
