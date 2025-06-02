<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
}
$admin = $_SESSION['admin'];

include '../config/auth.php';
onlyAdmin();  // Proteksi untuk admin saja
include '../config/db.php';
$jumlahBarang = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM barang_inventaris"))['total'];
$jumlahKaryawan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM karyawan"))['total'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Inventaris Barang</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard_admin.php">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="data_barang.php">
                                <i class="bi bi-box-seam me-2"></i> Barang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="data_karyawan.php">
                                <i class="bi bi-people me-2"></i> Karyawan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile_admin.php">
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
                            <span class="me-3">Halo, <?= $admin['nama_admin']; ?></span>
                            <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
                        </div>
                    </div>
                </nav>

                <h2 class="mb-4">Dashboard</h2>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0" style="border-radius: 18px;">
                            <div class="card-body d-flex align-items-center" style="min-height: 140px;">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-4"
                                    style="width:70px; height:70px;">
                                    <i class="bi bi-box-seam display-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold text-secondary mb-1">Total Barang</h6>
                                    <div class="fs-2 fw-semibold mb-2"><?= $jumlahBarang ?></div>
                                    <div class="text-end">
                                        <a href="data_barang.php" class="btn btn-outline-primary btn-sm px-3">
                                            <i class="bi bi-arrow-right-circle"></i> Lihat Data Barang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0" style="border-radius: 18px;">
                            <div class="card-body d-flex align-items-center" style="min-height: 140px;">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-4"
                                    style="width:70px; height:70px;">
                                    <i class="bi bi-people display-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold text-secondary mb-1">Total Karyawan</h6>
                                    <div class="fs-2 fw-semibold mb-2"><?= $jumlahKaryawan ?></div>
                                    <div class="text-end">
                                        <a href="data_karyawan.php" class="btn btn-outline-success btn-sm px-3">
                                            <i class="bi bi-arrow-right-circle"></i> Lihat Data Karyawan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bootstrap JS Bundle with Popper -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>