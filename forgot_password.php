<?php
session_start();
include 'config/config.php';

$error_msg = '';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    // Cek apakah email terdaftar di db
    $sql = "SELECT id_alumni FROM tb_alumni WHERE email='$email' LIMIT 1";
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $id_alumni = $row['id_alumni'];

        // Generate token unik
        $token = bin2hex(random_bytes(40));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Simpan token di database
        $sqlInsert = "INSERT INTO tb_password_resets (id_alumni, token, expires_at) VALUES ('$id_alumni', '$token', '$expires')";
        mysqli_query($conn, $sqlInsert);

        // Kirim email ke user (simple mail(), bisa diganti PHPMailer)
        $resetLink = "https://domainanda.com/reset_password.php?token=$token";

        $subject = "Reset Password Tracer Alumni";
        $message = "Klik link berikut untuk reset password Anda: $resetLink\nLink ini hanya berlaku selama 1 jam.";
        $headers = "From: no-reply@domainanda.com";

        mail($email, $subject, $message, $headers);

        $success_msg = "Email reset password sudah dikirim. Cek inbox Anda.";
    } else {
        $error_msg = "Email tidak terdaftar.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-uin.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lupa Password - Tracer Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
            font-family: 'Montserrat', Arial, sans-serif;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px 15px;
        }
        .forgot-card {
            background: rgba(255,255,255,0.95);
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(38, 70, 44, 0.15);
            padding: 40px 32px;
            max-width: 420px;
            width: 100%;
            color: #257a41;
            text-align: center;
        }
        h3 {
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 12px;
            color: #229954;
            letter-spacing: 0.5px;
        }
        .form-label {
            font-weight: 600;
            color: #257a41;
            text-align: left;
            display: block;
            margin-bottom: 8px;
        }
        .form-control {
            border-radius: 8px;
            border: 1.5px solid #b2d8b2;
            padding: 12px 15px;
            font-size: 1rem;
        }
        .btn-green {
            background: #2e7d32;
            color: #fff;
            font-weight: 700;
            padding: 12px 0;
            border-radius: 2rem;
            font-size: 1.15rem;
            box-shadow: 0 2px 12px rgba(38, 70, 44, 0.18);
            border: none;
            width: 100%;
            margin-top: 20px;
            cursor: pointer;
        }
        .btn-green:hover {
            background: #229954;
            color: #eaffea;
        }
        .alert {
            text-align: left;
            font-weight: 500;
            margin-top: 15px;
        }
        @media (max-width: 600px) {
            .forgot-card {
                padding: 28px 20px;
            }
            h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-card shadow">
        <h3>Lupa Password</h3>

        <?php if ($error_msg): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <?php if ($success_msg): ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= htmlspecialchars($success_msg) ?>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <label for="email" class="form-label">Masukkan Email Terdaftar</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email Anda" required autofocus />
                <button type="submit" class="btn btn-green">Kirim Link Reset</button>
            </form>
        <?php endif; ?>
        <div class="mt-4 text-center small text-success">
            <a href="login.php" class="text-success text-decoration-none fw-semibold">Kembali ke Login</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
