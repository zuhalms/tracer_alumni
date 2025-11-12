<?php
session_start();
include 'config/koneksi.php';

// Cek login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php?error=not_logged_in");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_alumni = $_SESSION['id_alumni'];
    $kepuasan_kurikulum = (int)$_POST['kepuasan_kurikulum'];
    $kepuasan_dosen = (int)$_POST['kepuasan_dosen'];
    $kepuasan_fasilitas = (int)$_POST['kepuasan_fasilitas'];
    $relevansi_ilmu_kerja = (int)$_POST['relevansi_ilmu_kerja'];
    $kompetensi_bidang = (int)$_POST['kompetensi_bidang'];
    $saran_perbaikan = mysqli_real_escape_string($conn, $_POST['saran_perbaikan']);

    $query = "INSERT INTO tb_kuesioner 
        (id_alumni, kepuasan_kurikulum, kepuasan_dosen, kepuasan_fasilitas, relevansi_ilmu_kerja, kompetensi_bidang, saran_perbaikan) 
        VALUES 
        ('$id_alumni', $kepuasan_kurikulum, $kepuasan_dosen, $kepuasan_fasilitas, $relevansi_ilmu_kerja, $kompetensi_bidang, '$saran_perbaikan')";

    if (mysqli_query($conn, $query)) {
        header("Location: kuesioner.php?success=1");
        exit();
    } else {
        header("Location: kuesioner.php?error=1");
        exit();
    }
} else {
    header("Location: kuesioner.php");
    exit();
}
?>
