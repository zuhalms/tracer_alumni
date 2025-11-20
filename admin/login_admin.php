<?php
session_start();
include '../config/config.php';

// Jika sudah login, redirect ke dashboard admin
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: dashboard_admin.php');
    exit();
}

// Variable untuk error
$error = '';

// Handle error dari URL parameter (jika ada redirect dari halaman lain)
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'not_logged_in':
            $error = 'Silakan login terlebih dahulu untuk mengakses halaman admin.';
            break;
        case 'session_expired':
            $error = 'Sesi Anda telah berakhir. Silakan login kembali.';
            break;
        default:
            $error = '';
    }
}

// Proses login jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    // Validasi input tidak kosong
    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi!";
    } else {
        // Query ke database
        $query = "SELECT * FROM tb_admin WHERE username = '$username' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Verifikasi password (MD5)
            if (md5($password) === $row['password']) {
                // Set session
                $_SESSION['is_admin'] = true;
                $_SESSION['admin_username'] = $row['username'];
                $_SESSION['admin_id'] = $row['id_admin'] ?? null;
                
                // Redirect ke dashboard TANPA parameter error
                header('Location: dashboard_admin.php');
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../assets/logo-uin.png">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Login Admin - Tracer Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
    <style>
        body {
            background: #e8f5e9;
            font-family: 'Montserrat', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            max-width: 400px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(34,139,34,.10);
            padding: 38px 32px;
            margin: 20px;
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
        .form-control:focus {
            border-color: #197948;
            box-shadow: 0 0 0 0.2rem rgba(25, 121, 72, 0.25);
        }
        .btn-success {
            background-color: #197948;
            border-color: #197948;
        }
        .btn-success:hover {
            background-color: #155c29;
            border-color: #155c29;
        }
        @media (max-width: 576px) {
            .login-box {
                padding: 28px 24px;
                margin: 15px;
            }
            .login-logo {
                flex-direction: column;
                gap: 8px;
            }
            .login-logo span {
                font-size: 1.1rem !important;
            }
        }
    </style>
</head>
<body>
    <div class="login-box">
        <a href="../index.php" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
        <div class="login-logo">
            <img src="../assets/logo-uin.png" alt="Logo Kampus"/>
            <span class="fw-bold fs-4 text-success">Tracer Alumni Admin</span>
        </div>
        <h5 class="mb-4 text-center fw-bold">Login Administrator</h5>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login_admin.php">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input 
                    type="text" 
                    name="username" 
                    class="form-control" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="Masukkan username"
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                />
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="form-control" 
                        required 
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                    />
                    <button 
                        class="btn btn-outline-secondary" 
                        type="button" 
                        id="togglePassword"
                        tabindex="-1"
                    >
                        <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (passwordInput && toggleBtn && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // ganti icon mata / mata silang
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');
            });
        }
    });
    </script>
</body>
</html>
