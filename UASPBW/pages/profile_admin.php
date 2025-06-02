<?php
session_start();
include '../config/db.php';
include '../config/auth.php';

onlyAdmin();

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
}

$admin = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - Website Inventaris Barang</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .profile-card {
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border: none;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
        }
        
        .profile-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .profile-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }
        
        .detail-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .stats-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            height: 100%;
        }
        
        .stats-number {
            font-size: 24px;
            font-weight: bold;
            color: #2575fc;
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        .edit-btn {
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (unchanged) -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="dashboard_admin.php">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="data_barang.php">
                                <i class="bi bi-box-seam me-2"></i> Barang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="data_karyawan.php">
                                <i class="bi bi-people me-2"></i> Karyawan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active text-white bg-primary" href="profile_admin.php">
                                <i class="bi bi-person me-2"></i> Admin
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 main-content">
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand text-white">Website Inventaris Barang</span>
                        <div class="d-flex align-items-center">
                            <span class="me-3">Halo, <?= $_SESSION['admin']['nama_admin'] ?></span>
                            <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
                        </div>
                    </div>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Profil Admin</h2>
                    <span class="badge bg-primary fs-5" id="clock"></span>
                </div>

                <div class="row">
                    <div class="col-lg-4 mb-0">
                        <div class="card profile-card h-100">
                            <div class="profile-header text-center py-4">
                                <img src="../assets/img/BudiSupanji.jpg" 
                                     alt="Admin Photo"
                                     class="profile-img mb-3">
                                <h4 class="mb-1"><?= htmlspecialchars($admin['nama_admin']) ?></h4>
                                <p class="text-light mb-0">Administrator</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card profile-card h-100">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-4">Informasi Profil</h5>
                                <div class="detail-item">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Nama Lengkap</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <?= htmlspecialchars($admin['nama_admin']) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <?= htmlspecialchars($admin['email']) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Username</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <?= htmlspecialchars($admin['username']) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- <div class="detail-item">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Bergabung Pada</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <?= date('d F Y', strtotime('2023-01-15')) ?>
                                        </div>
                                    </div>
                                </div> -->
                                
                                <div class="detail-item">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Status</h6>
                                        </div>
                                        <div class="col-sm-9">
                                            <span class="badge bg-success">Aktif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString();
            document.getElementById('clock').textContent = time;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>