<?php
session_start();
include '../config/db.php';
include '../config/auth.php';

onlyAdminAndKaryawan();

if (!isset($_SESSION['admin']) && !isset($_SESSION['karyawan'])) {
    header("Location: ../index.php");
}

// Ambil keyword dari form pencarian
$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($koneksi, $_GET['keyword']) : ''; // <-- ditambahkan

// Konfigurasi pagination
$limit = 4; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Sorting and filtering
$sort = isset($_GET['sort']) && in_array($_GET['sort'], ['asc', 'desc']) ? $_GET['sort'] : '';
$filter_kategori = isset($_GET['filter_kategori']) ? mysqli_real_escape_string($koneksi, $_GET['filter_kategori']) : '';

$where = [];
if ($keyword != '') {
    $where[] = "(nama_barang LIKE '%$keyword%' OR kategori LIKE '%$keyword%' OR id_barang LIKE '%$keyword%' OR keterangan LIKE '%$keyword%')";
}
if ($filter_kategori != '') {
    $where[] = "kategori = '$filter_kategori'";
}
$where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Query utama dengan pengurutan yang benar
$query = "SELECT * FROM barang_inventaris $where_sql";

// Ambil semua parameter yang ada
$params = [
    'keyword' => $keyword,
    'filter_kategori' => $filter_kategori,
    'sort' => $sort
];

// Atur pengurutan berdasarkan parameter sort
if ($sort == 'asc') {
    $query .= " ORDER BY nama_barang ASC";
} elseif ($sort == 'desc') {
    $query .= " ORDER BY nama_barang DESC";
} else {
    $query .= " ORDER BY id_barang ASC"; // Default urutkan berdasarkan ID
}

// Eksekusi query dengan limit untuk pagination
$barang = mysqli_query($koneksi, $query . " LIMIT $start, $limit");

// Hitung total data untuk pagination
$total_query = "SELECT * FROM barang_inventaris $where_sql";
$total_data = mysqli_num_rows(mysqli_query($koneksi, $total_query));
$total_page = ceil($total_data / $limit);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Inventaris Barang - Data Barang</title>
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
                            <a class="nav-link" href="<?= isset($_SESSION['admin']) ? 'dashboard_admin.php' : 'dashboard_karyawan.php' ?>">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="data_barang.php">
                                <i class="bi bi-box-seam me-2"></i> Barang
                            </a>
                        </li>
                        <?php if (isset($_SESSION['admin'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="data_karyawan.php">
                                    <i class="bi bi-people me-2"></i> Karyawan
                                </a>
                            </li>
                        <?php endif; ?>
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
                    <h2>Data Barang</h2>
                    <?php if (isset($_SESSION['admin'])): ?>
                        <a href="tambah_barang.php" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Tambah Barang
                        </a>
                    <?php endif; ?>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Form pencarian -->
                        <form method="GET" class="row g-2 mb-3">
                            <div class="col-md-4">
                                <select name="sort" class="form-select">
                                    <option value="">Urutkan Nama Barang</option>
                                    <option value="asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'asc') ? 'selected' : '' ?>>A-Z</option>
                                    <option value="desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'desc') ? 'selected' : '' ?>>Z-A</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="filter_kategori" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    <option value="Elektronik" <?= (isset($_GET['filter_kategori']) && $_GET['filter_kategori'] == 'Elektronik') ? 'selected' : '' ?>>Elektronik</option>
                                    <option value="ATK" <?= (isset($_GET['filter_kategori']) && $_GET['filter_kategori'] == 'ATK') ? 'selected' : '' ?>>ATK</option>
                                    <option value="Perabotan" <?= (isset($_GET['filter_kategori']) && $_GET['filter_kategori'] == 'Perabotan') ? 'selected' : '' ?>>Perabotan</option>
                                    <option value="Lainnya" <?= (isset($_GET['filter_kategori']) && $_GET['filter_kategori'] == 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-outline-secondary w-100" type="submit">
                                    <i class="bi bi-funnel"></i> Terapkan Filter
                                </button>
                            </div>
                            <!-- Pertahankan pencarian jika ada -->
                            <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
                        </form>

                        <form method="GET" class="input-group mb-3 search-box"> <!-- <-- ditambahkan -->
                            <input type="text" class="form-control" name="keyword" placeholder="Cari barang..." value="<?= htmlspecialchars($keyword) ?>"> <!-- <-- ditambahkan -->
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <?= $_SESSION['success']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($barang) > 0): ?> <!-- <-- ditambahkan -->
                                        <?php while ($b = mysqli_fetch_assoc($barang)): ?>
                                            <tr>
                                                <td><?= $b['id_barang'] ?></td>
                                                <td><?= $b['nama_barang'] ?></td>
                                                <td><?= $b['kategori'] ?></td>
                                                <td><?= $b['jumlah'] ?></td>
                                                <td><?= $b['tanggal_masuk'] ?></td>
                                                <td><?= $b['keterangan'] ?></td>
                                                <td>
                                                    <a href="edit_barang.php?id=<?= $b['id_barang'] ?>" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <a href="hapus_barang.php?id=<?= $b['id_barang'] ?>" onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?> <!-- <-- ditambahkan -->
                                        <tr>
                                            <td colspan="7" class="text-center">Data tidak ditemukan.</td>
                                        </tr>
                                    <?php endif; ?> <!-- <-- ditambahkan -->
                                </tbody>
                            </table>
                        </div>

                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($params, ['page' => $page - 1])) ?>">Previous</a>
                                </li>

                                <?php for ($i = 1; $i <= $total_page; $i++): ?>
                                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                        <a class="page-link" href="?<?= http_build_query(array_merge($params, ['page' => $i])) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?= $page >= $total_page ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($params, ['page' => $page + 1])) ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>