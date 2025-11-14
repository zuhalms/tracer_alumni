<?php
session_start();

// Jika sudah login, redirect ke dashboard alumni
if (isset($_SESSION['id_alumni'])) {
    header("Location: dashboard_alumni.php");
    exit();
}
$title = "Login Alumni - Tracer Alumni Kampus";
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Alumni - Tracer Alumni Kampus</title>
    <link rel="icon" type="image/png" href="../assets/logo-uin.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
            font-family: 'Montserrat', Arial, sans-serif;
            color: #fff;
            display: flex;
            flex-direction: column;
        }
        .login-bg {
            min-height: 93vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255,255,255,0.94);
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(38, 70, 44, 0.15);
            padding: 40px 32px;
            max-width: 420px;
            width: 100%;
            margin: 38px auto;
            color: #257a41;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 12px;
        }
        .login-logo img {
            width: 65px;
            margin-bottom: 8px;
        }
        .login-title {
            font-weight: 800;
            font-size: 2.0rem;
            margin-bottom: 10px;
            color: #229954;
            letter-spacing: 0.5px;
        }
        .login-desc {
            color: #229954;
            font-weight: 500;
            margin-bottom: 27px;
            text-align: center;
            font-size: 1.1rem;
        }
        .form-label {
            font-weight: 600;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-green {
            background: #2e7d32;
            color: #fff;
            font-weight: 700;
            padding: 12px 0;
            border-radius: 2rem;
            font-size: 1.15rem;
            box-shadow: 0 2px 12px rgba(38, 70, 44, 0.18);
        }
        .btn-green:hover {
            background: #229954;
            color: #eaffea;
        }
        .login-card .small {
            color: #339966;
        }
        /* Responsif */
        @media (max-width: 600px) {
            .login-card {
                padding: 23px 7vw;
                margin: 22px auto;
            }
            .login-title {
                font-size: 1.4rem;
            }
        }
    </style>
    <script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        if (input.type === "password") {
            input.type = "text";
            btn.querySelector('span').classList.remove('bi-eye');
            btn.querySelector('span').classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            btn.querySelector('span').classList.remove('bi-eye-slash');
            btn.querySelector('span').classList.add('bi-eye');
        }
    }
    </script>
</head>
<body>
<div class="login-bg">
    <div class="login-card shadow">
        <div class="login-logo">
            <img src="assets/logo-uin.png" alt="Logo Kampus" />
        </div>
        <div class="login-title text-center">Login Alumni</div>
        <div class="login-desc">Sistem Tracer Alumni </div>

        <?php
        // Pesan sukses registrasi
        if (isset($_GET['success']) && $_GET['success'] == 'register') {
            echo '<div class="alert alert-success">Registrasi berhasil! Silakan login.</div>';
        }
        // Pesan error login
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger">';
            if ($_GET['error'] == 'wrong_credentials') {
                echo 'NIM/Email atau Password salah!';
            } elseif ($_GET['error'] == 'empty_fields') {
                echo 'Mohon isi semua field dengan lengkap!';
            } else {
                echo 'Terjadi kesalahan, silakan coba lagi!';
            }
            echo '</div>';
        }
        // Pesan logout sukses
        if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
            echo '<div class="alert alert-info">Anda berhasil logout.</div>';
        }
        ?>

        <form action="proses_login.php" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="nim_email" class="form-label">NIM atau Email</label>
                <input type="text" id="nim_email" name="nim_email" class="form-control" required autofocus placeholder="Masukkan NIM atau Email" />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan password" />
                    <button type="button" class="btn btn-outline-secondary" tabindex="-1" onclick="togglePassword('password', this)" aria-label="Tampilkan/Sembunyikan Password">
                        <span class="bi bi-eye"></span>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-green w-100 my-3">Login</button>
        </form>

        <div class="mt-2 text-center small">
            Belum punya akun? <a href="register.php" class="text-success">Daftar di sini</a>
            <br>
            <a href="index.php" class="text-success">← Kembali ke Beranda</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>