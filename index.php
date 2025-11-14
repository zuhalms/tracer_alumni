<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-uin.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tracer Alumni | Smart & Green Campus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
            font-family: 'Montserrat', Arial, sans-serif;
            color: #fff;
            position: relative;
            overflow-x: hidden;
        }
        .main-navbar {
            background: transparent;
            box-shadow: none;
            padding-top: 20px;
            padding-bottom: 0;
            z-index: 2;
            position: relative;
        }
        .brand-logo {
            display: flex;
            align-items: center;
        }
        .brand-logo img {
            width: 48px;
            margin-right: 12px;
        }
        .brand-title {
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: .5px;
        }
        .main-nav .nav-link {
            color: #e4ffe6 !important;
            font-weight: 500;
            margin-right: 18px;
            opacity: 0.88;
        }
        .main-nav .nav-link.active, .main-nav .nav-link:hover {
            color: #fff !important;
            text-decoration: underline;
            opacity: 1;
        }

        .hero-section {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            text-align: center;
            z-index: 1;
            padding-bottom: 40px;
        }

        .hero-bg-watermark {
            position: absolute;
            left: 50%;
            top: 47%;
            transform: translate(-50%, -50%);
            width: 400px;
            max-width: 90vw;
            opacity: 0.25;
            z-index: 0;
            user-select: none;
            pointer-events: none;
        }

        @media (max-width: 720px) {
            .hero-bg-watermark {
                width: 180px;
            }
        }

        .sub-headline {
            font-size: 1.1rem;
            font-weight: 600;
            color: #ddfff9;
            letter-spacing: 0.6px;
            z-index: 2;
            position: relative;
            margin-bottom: 0.25rem;
        }
        .hero-title {
            font-size: 2.8rem;
            font-weight: 900;
            letter-spacing: 1px;
            text-shadow: 0 2px 14px rgba(21, 80, 38, 0.16);
            z-index: 2;
            position: relative;
            margin-bottom: 0.6rem;
        }
        .subtitle {
            margin: 0 auto 1.7rem auto;
            font-size: 1.15rem;
            max-width: 570px;
            color: #e6ffe8;
            font-weight: 400;
            z-index: 2;
            position: relative;
            text-shadow: 0 1px 6px rgba(44,51,49,0.13);
        }
        .hero-section .btn-main {
            margin-top: 1.35rem;
            padding: 0.85rem 2.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            background: #fff;
            color: #229954 !important;
            border: none;
            border-radius: 2rem;
            box-shadow: 0 6px 36px rgba(40,150,80,0.13);
            transition: all 0.15s;
            z-index: 2;
            position: relative;
        }
        .hero-section .btn-main:hover {
            background: #e8ffe4;
            color: #1e8449 !important;
        }
        .campus-foot {
            margin-top: 40px;
            opacity: 0.12;
            font-size: 2rem;
            letter-spacing: 2px;
            font-weight: 800;
            z-index: 2;
            position: relative;
        }

        @media (max-width: 800px) {
            .hero-title { font-size: 1.45rem;}
            .campus-foot { font-size: 1rem; }
            .subtitle { font-size: 1rem; }
        }
        .card-section {
            margin-top: 3rem;
            margin-bottom: 2.2rem;
        }
        .card-feature {
            background: rgba(255,255,255,0.07);
            border: none;
            border-radius: 14px;
            color: #fff;
            box-shadow: 0 2px 14px rgba(37, 90, 51, 0.13);
            transition: transform 0.18s, box-shadow 0.18s;
        }
        .card-feature:hover {
            transform: translateY(-7px) scale(1.03);
            box-shadow: 0 10px 32px rgba(37,90,51,0.23);
        }
        .card-feature h4 { font-weight: 700; }
        .card-feature p { color: #def7e4; }
        footer {
            background: transparent;
            color: #e7ffe7;
            font-size: 1rem;
            text-align: center;
            padding: 25px 0 10px 0;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <nav class="navbar main-navbar navbar-expand-lg">
        <div class="container">
            <div class="brand-logo">
                <img src="assets/logo-uin.png" alt="Logo UIN" />
                <div>
                    <span class="brand-title">TRACER ALUMNI  Teknik Informatika</span><br>
                    <small>Universitas Islam Negeri Alauddin Makassar</small>
                </div>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end main-nav" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login Alumni</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Registrasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/login_admin.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="hero-section">
        <!-- Logo watermark besar, benar2 di tengah, pudar -->
        <img src="assets/logo-uin.png" class="hero-bg-watermark" alt="Watermark UIN"/>
        
        <h1 class="hero-title">Tracer Alumni</h1>
        <div class="subtitle">
            Platform pelacakan dan evaluasi alumni untuk mendukung efisiensi, keberlanjutan, serta perbaikan kualitas pendidikan di lingkungan Jurusan Teknik Informatika.
        </div>
        <a href="login.php" class="btn btn-main shadow">Masuk ke Sistem</a>
        <div class="campus-foot"></div>
    </main>

    <section class="container card-section">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card card-feature h-100 p-4 text-center">
                    <h4>📝 Registrasi Mudah</h4>
                    <p>Daftar sebagai alumni hanya dengan beberapa klik, data langsung tersimpan, tanpa perlu verifikasi manual.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-feature h-100 p-4 text-center">
                    <h4>📊 Isi Kuesioner</h4>
                    <p>Kuesioner tracer alumni berbasis data, mendukung kebutuhan jurusan untuk pelaporan dan pembenahan kualitas.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card card-feature h-100 p-4 text-center">
                    <h4>📈 Laporan Modern</h4>
                    <p>Admin dapat melakukan monitoring dan evaluasi alumni untuk mendukung program Smart & Green Campus.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        &copy; <?= date('Y') ?> Tracer Alumni Jurusan Teknik Informatika – Universitas Islam Negeri Alauddin Makassar. 
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>