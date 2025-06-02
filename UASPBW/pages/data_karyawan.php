<?php
session_start();
include '../config/db.php';
include '../config/auth.php';

onlyAdminAndKaryawan();

if (!isset($_SESSION['admin']) && !isset($_SESSION['karyawan'])) {
    header("Location: ../index.php");
}

// Ambil keyword dari URL (GET)
$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($koneksi, $_GET['keyword']) : '';

// Pagination
$per_page = 5; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Pastikan tidak kurang dari 1
$offset = ($page - 1) * $per_page;

// Hitung total data
if ($keyword != '') {
    $total_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM karyawan WHERE 
        id_karyawan LIKE '%$keyword%' OR 
        nama LIKE '%$keyword%' OR 
        jabatan LIKE '%$keyword%' OR 
        email LIKE '%$keyword%' OR 
        no_hp LIKE '%$keyword%' OR 
        alamat LIKE '%$keyword%' OR 
        status LIKE '%$keyword%'");
} else {
    $total_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM karyawan");
}
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $per_page);

// Query berdasarkan pencarian dengan pagination
if ($keyword != '') {
    $karyawan = mysqli_query($koneksi, "SELECT * FROM karyawan WHERE 
        id_karyawan LIKE '%$keyword%' OR 
        nama LIKE '%$keyword%' OR 
        jabatan LIKE '%$keyword%' OR 
        email LIKE '%$keyword%' OR 
        no_hp LIKE '%$keyword%' OR 
        alamat LIKE '%$keyword%' OR 
        status LIKE '%$keyword%'
        LIMIT $offset, $per_page");
} else {
    $karyawan = mysqli_query($koneksi, "SELECT * FROM karyawan LIMIT $offset, $per_page");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Inventaris Barang - Data Karyawan</title>
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
                            <a class="nav-link" href="dashboard_admin.php">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="data_barang.php">
                                <i class="bi bi-box-seam me-2"></i> Barang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="data_karyawan.php">
                                <i class="bi bi-people me-2"></i> Karyawan
                            </a>
                        </li>
                        <?php if (isset($_SESSION['admin'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="profile_admin.php">
                                    <i class="bi bi-person me-2"></i> Admin
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 main-content">
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand text-white">Website Inventaris Barang</span>
                        <div class="d-flex align-items-center">
                            <span class="me-3">Halo, <?= isset($_SESSION['admin']) ? $_SESSION['admin']['nama_admin'] : $_SESSION['karyawan']['nama'] ?></span>
                            <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
                        </div>
                    </div>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Data Karyawan</h2>
                    <?php if (isset($_SESSION['admin'])): ?>
                        <a href="tambah_karyawan.php" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Tambah Karyawan
                        </a>
                    <?php endif; ?>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Form Pencarian -->
                        <form method="GET" class="input-group mb-3 search-box">
                            <input type="text" class="form-control" name="keyword" placeholder="Cari karyawan..." value="<?= htmlspecialchars($keyword) ?>">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>

                        <div class="card p-3">
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show w-100 mb-3" role="alert">
                                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID Karyawan</th>
                                            <th>Nama Karyawan</th>
                                            <th>Jabatan</th>
                                            <th>Email</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($karyawan) > 0): ?>
                                            <?php while ($k = mysqli_fetch_assoc($karyawan)): ?>
                                                <tr>
                                                    <td><?= $k['id_karyawan'] ?></td>
                                                    <td><?= $k['nama'] ?></td>
                                                    <td><?= $k['jabatan'] ?></td>
                                                    <td><?= $k['email'] ?></td>
                                                    <td><?= $k['no_hp'] ?></td>
                                                    <td><?= $k['alamat'] ?></td>
                                                    <td><?= $k['status'] ?></td>
                                                    <td>
                                                        <a href="edit_karyawan.php?id=<?= $k['id_karyawan'] ?>" class="btn btn-sm btn-warning mb-1">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </a>
                                                        <a href="hapus_karyawan.php?id=<?= $k['id_karyawan'] ?>" onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?page=<?= $page - 1 ?><?= $keyword ? '&keyword='.urlencode($keyword) : '' ?>" tabindex="-1">Previous</a>
                                    </li>
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?><?= $keyword ? '&keyword='.urlencode($keyword) : '' ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?page=<?= $page + 1 ?><?= $keyword ? '&keyword='.urlencode($keyword) : '' ?>">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>