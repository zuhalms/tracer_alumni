<?php
session_start();
include 'config/config.php';

$token = $_GET['token'] ?? '';
$error_msg = '';
$success_msg = '';

if (!$token) {
    die("Token tidak ditemukan.");
}

// Cek token valid dan belum expired
$sql = "SELECT * FROM tb_password_resets WHERE token='$token' AND is_used=0 AND expires_at > NOW() LIMIT 1";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    die("Token tidak valid atau sudah kadaluarsa.");
}

$row = mysqli_fetch_assoc($res);
$id_alumni = $row['id_alumni'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_pass = $_POST['password'] ?? '';
    $new_pass_confirm = $_POST['password_confirm'] ?? '';

    if (empty($new_pass) || strlen($new_pass) < 6) {
        $error_msg = "Password harus diisi dan minimal 6 karakter.";
    } elseif ($new_pass != $new_pass_confirm) {
        $error_msg = "Password konfirmasi tidak sama.";
    } else {
        // Update password (gunakan MD5 / hashing lain)
        $hashed = md5($new_pass);  // Lebih baik gunakan password_hash()
        $sql_update = "UPDATE tb_alumni SET password='$hashed' WHERE id_alumni='$id_alumni'";
        if (mysqli_query($conn, $sql_update)) {
            // Tandai token sudah dipakai
            $sql_used = "UPDATE tb_password_resets SET is_used=1 WHERE id='$row[id]'";
            mysqli_query($conn, $sql_used);
            $success_msg = "Password berhasil diubah. <a href='login.php'>Login sekarang</a>";
        } else {
            $error_msg = "Gagal mengubah password. Coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
<h3>Reset Password</h3>

<?php if ($error_msg) echo "<p style='color:red;'>$error_msg</p>"; ?>
<?php if ($success_msg) { echo "<p style='color:green;'>$success_msg</p>"; } else { ?>

<form method="POST" action="">
    <input type="password" name="password" placeholder="Password baru" required><br>
    <input type="password" name="password_confirm" placeholder="Konfirmasi password baru" required><br>
    <button type="submit">Ubah Password</button>
</form>

<?php } ?>
</body>
</html>
