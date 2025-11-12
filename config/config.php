<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "tracer_alumni_kampus";

// Membuat koneksi ke database
$conn = mysqli_connect($server, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
