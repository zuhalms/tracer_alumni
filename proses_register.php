<?php
include 'config/config.php';

// Ambil data dari form
$nim = mysqli_real_escape_string($conn, $_POST['nim']);
$nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
$fakultas = mysqli_real_escape_string($conn, $_POST['fakultas']);
$program_studi = mysqli_real_escape_string($conn, $_POST['program_studi']);
$tahun_masuk = mysqli_real_escape_string($conn, $_POST['tahun_masuk']);
$tahun_lulus = mysqli_real_escape_string($conn, $_POST['tahun_lulus']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$password = $_POST['password'];
$konfirmasi_password = $_POST['konfirmasi_password'];

// Validasi password
if($password != $konfirmasi_password) {
    header("Location: register.php?error=password_mismatch");
    exit();
}

// Hash password
$password_hash = md5($password);

// Cek apakah NIM sudah terdaftar
$cek_nim = mysqli_query($conn, "SELECT * FROM tb_alumni WHERE nim='$nim'");
if(mysqli_num_rows($cek_nim) > 0) {
    header("Location: register.php?error=nim_exist");
    exit();
}

// Cek apakah Email sudah terdaftar
$cek_email = mysqli_query($conn, "SELECT * FROM tb_alumni WHERE email='$email'");
if(mysqli_num_rows($cek_email) > 0) {
    header("Location: register.php?error=email_exist");
    exit();
}

// Insert data ke database
$query = "INSERT INTO tb_alumni (nim, nama_lengkap, fakultas, program_studi, tahun_masuk, tahun_lulus, email, no_hp, alamat, password) 
          VALUES ('$nim', '$nama_lengkap', '$fakultas', '$program_studi', '$tahun_masuk', '$tahun_lulus', '$email', '$no_hp', '$alamat', '$password_hash')";

if(mysqli_query($conn, $query)) {
    // Registrasi berhasil, redirect ke login dengan pesan sukses
    header("Location: login.php?success=register");
    exit();
} else {
    // Registrasi gagal
    header("Location: register.php?error=failed");
    exit();
}
?>
