<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login_admin.php'); exit();
}
include '../config/config.php';

// Ambil filter tahun dari URL
$tahunLulus = isset($_GET['tahun_lulus']) ? intval($_GET['tahun_lulus']) : '';
$where = $tahunLulus ? "WHERE a.tahun_lulus = '$tahunLulus'" : '';

// Query data alumni (kolom sesuaikan kebutuhan)
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

if (!$result) {
    die("Terjadi kesalahan query: " . mysqli_error($conn));
}

$filename = "data_alumni" . ($tahunLulus ? "_$tahunLulus" : "") . "_" . date('Ymd_His') . ".xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Output tabel
echo '<table border="1">';
echo '<thead>
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
<tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>'.htmlspecialchars($row['nim']).'</td>';
    echo '<td>'.htmlspecialchars($row['nama_lengkap']).'</td>';
    echo '<td>'.htmlspecialchars($row['fakultas']).'</td>';
    echo '<td>'.htmlspecialchars($row['program_studi']).'</td>';
    echo '<td>'.htmlspecialchars($row['tahun_masuk']).'</td>';
    echo '<td>'.htmlspecialchars($row['tahun_lulus']).'</td>';
    echo '<td>'.htmlspecialchars($row['email']).'</td>';
    echo '<td>'.htmlspecialchars($row['no_hp']).'</td>';
    echo '<td>'.htmlspecialchars($row['alamat']).'</td>';
    echo '<td>'.htmlspecialchars($row['status_pekerjaan']).'</td>';
    echo '<td>'.htmlspecialchars($row['nama_perusahaan']).'</td>';
    echo '<td>'.htmlspecialchars($row['jabatan']).'</td>';
    echo '<td>'.htmlspecialchars($row['gaji_pertama']).'</td>';
    echo '<td>'.htmlspecialchars($row['tahun_mulai_kerja']).'</td>';
    echo '<td>'.htmlspecialchars($row['relevansi_pekerjaan']).'</td>';
    echo '<td>'.htmlspecialchars($row['kepuasan_kurikulum']).'</td>';
    echo '<td>'.htmlspecialchars($row['kepuasan_dosen']).'</td>';
    echo '<td>'.htmlspecialchars($row['kepuasan_fasilitas']).'</td>';
    echo '<td>'.htmlspecialchars($row['relevansi_ilmu_kerja']).'</td>';
    echo '<td>'.htmlspecialchars($row['kompetensi_bidang']).'</td>';
    echo '</tr>';
}
echo '</tbody></table>';

exit();
?>