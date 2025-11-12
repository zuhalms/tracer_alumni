<?php
$title = "Register Alumni - Tracer Alumni Kampus";
include 'includes/header.php';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
        font-family: 'Montserrat', Arial, sans-serif;
        color: #fff;
        display: flex;
        flex-direction: column;
        margin: 0;
        padding: 0;
    }
    .register-bg {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .register-card {
        background: rgba(255,255,255,0.94);
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(38, 70, 44, 0.15);
        padding: 40px 32px;
        max-width: 460px;
        width: 100%;
        margin: 20px;
        color: #257a41;
    }
    .register-title {
        font-weight: 800;
        font-size: 2rem;
        margin-bottom: 26px;
        color: #229954;
        text-align: center;
    }
    .form-label {
        font-weight: 600;
        color: #1e593e;
    }
    .form-control {
        border-radius: 8px;
        border: 1.5px solid #72c59d;
    }
    .btn-green {
        background: #2e7d32;
        color: #fff;
        font-weight: 700;
        padding: 12px 0;
        border-radius: 2rem;
        font-size: 1.15rem;
        box-shadow: 0 2px 12px rgba(38, 70, 44, 0.18);
        width: 100%;
        margin-top: 18px;
    }
    .btn-green:hover {
        background: #229954;
        color: #eaffea;
    }
    .alert {
        margin-bottom: 18px;
    }
</style>

<div class="register-bg">
    <div class="register-card shadow">
        <div class="register-title">Registrasi Alumni</div>

        <?php
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger">';
            switch ($_GET['error']) {
                case 'password_mismatch':
                    echo 'Password dan konfirmasi tidak cocok.';
                    break;
                case 'nim_exist':
                    echo 'NIM sudah terdaftar.';
                    break;
                case 'email_exist':
                    echo 'Email sudah terdaftar.';
                    break;
                case 'failed':
                    echo 'Registrasi gagal. Coba lagi.';
                    break;
            }
            echo '</div>';
        }
        ?>

        <form action="proses_register.php" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" id="nim" name="nim" class="form-control" required placeholder="Masukkan NIM" />
            </div>
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" required placeholder="Nama Anda" />
            </div>
            <div class="mb-3">
                <label for="fakultas" class="form-label">Fakultas</label>
                <input type="text" id="fakultas" name="fakultas" class="form-control" required placeholder="Fakultas" />
            </div>
            <div class="mb-3">
                <label for="program_studi" class="form-label">Program Studi</label>
                <input type="text" id="program_studi" name="program_studi" class="form-control" required placeholder="Program Studi" />
            </div>
            <div class="mb-3">
                <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                <input type="number" id="tahun_masuk" name="tahun_masuk" class="form-control" required placeholder="Tahun Masuk" />
            </div>
            <div class="mb-3">
                <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                <input type="number" id="tahun_lulus" name="tahun_lulus" class="form-control" required placeholder="Tahun Lulus" />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="Email aktif" />
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label">No. HP</label>
                <input type="text" id="no_hp" name="no_hp" class="form-control" required placeholder="Nomor HP" />
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" rows="2" class="form-control" required placeholder="Alamat lengkap"></textarea>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Buat password" />
                    <button type="button" class="btn btn-outline-secondary" tabindex="-1" onclick="togglePassword('password', this)">
                        <span class="bi bi-eye"></span>
                    </button>
                </div>
            </div>
            <div class="mb-3">
                <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password" class="form-control" required placeholder="Ulangi password" />
                    <button type="button" class="btn btn-outline-secondary" tabindex="-1" onclick="togglePassword('konfirmasi_password', this)">
                        <span class="bi bi-eye"></span>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-green">Daftar</button>
        </form>

        <div class="mt-3 small text-center text-success">
            Sudah punya akun? <a href="login.php" class="text-success">Login di sini</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
