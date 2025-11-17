<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login_admin.php'); exit();
}
include '../config/config.php';

// Ambil filter tahun dari URL
$tahunLulus = isset($_GET['tahun_lulus']) ? intval($_GET['tahun_lulus']) : '';
$where = $tahunLulus ? "WHERE a.tahun_lulus = '$tahunLulus'" : '';

// Query data alumni
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

$filename = "data_alumni" . ($tahunLulus ? "_$tahunLulus" : "") . "_" . date('Ymd_His') . ".csv";
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");

$output = fopen("php://output", "w");
// Header kolom
fputcsv($output, [
    'NIM', 'Nama Lengkap', 'Fakultas', 'Prodi', 'Tahun Masuk', 'Tahun Lulus', 'Email', 'No. HP', 'Alamat',
    'Status Kerja', 'Perusahaan', 'Jabatan', 'Gaji Pertama', 'Tahun Mulai Kerja', 'Relevansi Kerja',
    'Kepuasan Kurikulum', 'Kepuasan Dosen', 'Kepuasan Fasilitas', 'Relevansi Ilmu-Kerja', 'Kompetensi Bidang'
]);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['nim'],
        $row['nama_lengkap'],
        $row['fakultas'],
        $row['program_studi'],
        $row['tahun_masuk'],
        $row['tahun_lulus'],
        $row['email'],
        $row['no_hp'],
        $row['alamat'],
        $row['status_pekerjaan'],
        $row['nama_perusahaan'],
        $row['jabatan'],
        $row['gaji_pertama'],
        $row['tahun_mulai_kerja'],
        $row['relevansi_pekerjaan'],
        $row['kepuasan_kurikulum'],
        $row['kepuasan_dosen'],
        $row['kepuasan_fasilitas'],
        $row['relevansi_ilmu_kerja'],
        $row['kompetensi_bidang']
    ]);
}
fclose($output);
exit();
?>
