<?php
session_start();
include '../config/config.php';

// Jika sudah login, redirect ke dashboard admin
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: dashboard_admin.php');
    exit();
}

// Proses login jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Gantilah query ini sesuai struktur tabel admin Anda!
    $query = "SELECT * FROM tb_admin WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // Password terenkripsi MD5
        if (md5($password) === $row['password']) {
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_username'] = $row['username'];
            header('Location: dashboard_admin.php');
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Login Admin - Tracer Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
    <style>
    body {
        background: #e8f5e9;
        font-family: 'Montserrat', Arial, sans-serif;
    }
    .login-box {
        max-width: 400px;
        margin: 6% auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(34,139,34,.10);
        padding: 38px 32px;
    }
    .login-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        justify-content: center;
        margin-bottom: 18px;
    }
    .login-logo img {
        width: 48px;
        height: 48px;
        object-fit: contain;
    }
    .back-link {
        display: block;
        margin-bottom: 18px;
        color: #197948;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.18s;
    }
    .back-link:hover {
        color: #155c29;
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <div class="login-box">
        <a href="../dashboard_alumni.php" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
        <div class="login-logo">
            <img src="../assets/logo-uin.png" alt="Logo Kampus"/>
            <span class="fw-bold fs-4 text-success">Tracer Alumni Admin</span>
        </div>
        <h5 class="mb-4 text-center fw-bold">Login Administrator</h5>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="login_admin.php">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus autocomplete="username"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required autocomplete="current-password"/>
            </div>
            <button type="submit" class="btn btn-success w-100">Login</button>
        </form>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>