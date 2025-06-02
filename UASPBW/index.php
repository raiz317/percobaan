<?php
session_start();
include 'config/db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek Admin
    $admin = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username'");
    if (mysqli_num_rows($admin) > 0) {
        $a = mysqli_fetch_assoc($admin);
        if (password_verify($password, $a['password'])) {
            $_SESSION['admin'] = $a;
            header("Location: pages/dashboard_admin.php");
            exit();
        }
    }

    // Cek Karyawan
    $karyawan = mysqli_query($koneksi, "SELECT * FROM karyawan WHERE email='$username'");
    if (mysqli_num_rows($karyawan) > 0) {
        $k = mysqli_fetch_assoc($karyawan);
        // Password default karyawan = "123456"
        if ($password == '123456') {
            $_SESSION['karyawan'] = $k;
            header("Location: pages/dashboard_karyawan.php");
            exit();
        }
    }

    $error = "Username atau password salah!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Inventaris</title>
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-page {
            background: linear-gradient(135deg, #3a7bd5, #3a6073);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .login-page:before {
            content: "";
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            top: -300px;
            right: -300px;
            border-radius: 50%;
        }

        .login-page:after {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            bottom: -200px;
            left: -200px;
            border-radius: 50%;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.8s ease;
        }

        .login-illustration {
            text-align: center;
            margin-bottom: 25px;
        }

        .login-illustration i {
            font-size: 65px;
            background: linear-gradient(135deg, #4361ee, #3a7bd5);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }

        .form-group {
            position: relative;
            margin-bottom: 25px;
        }

        .form-control {
            height: 55px;
            padding-left: 45px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
            background-color: #fff;
        }

        .form-icon {
            position: absolute;
            left: 15px;
            top: 18px;
            color: #718096;
            font-size: 18px;
        }

        .btn-login {
            height: 55px;
            background: linear-gradient(135deg, #4361ee, #3a7bd5);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
            background: linear-gradient(135deg, #3a7bd5, #4361ee);
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 15px;
        }

        .floating-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 6s infinite ease-in-out;
        }

        .shape-1 {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 150px;
            height: 150px;
            top: 60%;
            left: 15%;
            animation-delay: 1s;
        }

        .shape-3 {
            width: 70px;
            height: 70px;
            top: 30%;
            right: 15%;
            animation-delay: 2s;
        }

        .shape-4 {
            width: 120px;
            height: 120px;
            bottom: 20%;
            right: 10%;
            animation-delay: 3s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert {
            border-radius: 12px;
            padding: 15px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }

            .login-container {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="login-page">
        <!-- Floating shapes for background animation -->
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        <div class="floating-shape shape-4"></div>

        <div class="login-container">
            <div class="login-card">
                <div class="login-illustration">
                    <i class="fas fa-boxes-stacked"></i>
                    <h2 class="fw-bold text-primary mb-1">Selamat Datang</h2>
                    <p class="text-muted">Sistem Manajemen Inventaris Barang</p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div><?php echo $error; ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST" class="mt-4">
                    <div class="form-group">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username atau Email" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-lock form-icon"></i>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100 btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                    </button>
                </form>
            </div>

            <div class="login-footer">
                &copy; <?php echo date('Y'); ?> Sistem Inventaris Barang
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>