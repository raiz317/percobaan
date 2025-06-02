<?php
session_start();
include '../config/db.php';
include '../config/auth.php';

onlyAdminAndKaryawan(); // 🛡 proteksi admin + karyawan

// Cek jika tidak ada admin dan tidak ada karyawan
if (!isset($_SESSION['admin']) && !isset($_SESSION['karyawan'])) {
    header("Location: ../index.php");
    exit();
}

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID barang tidak valid");
}

$id = (int)$_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM barang_inventaris WHERE id_barang=$id");
if (!$query || mysqli_num_rows($query) === 0) {
    die("Data barang tidak ditemukan");
}
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    // Escape input untuk mencegah SQL Injection
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $jumlah = (int)$_POST['jumlah'];
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal_masuk']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    $updateQuery = "UPDATE barang_inventaris SET 
        nama_barang='$nama', kategori='$kategori', jumlah=$jumlah, 
        tanggal_masuk='$tanggal', keterangan='$keterangan' 
        WHERE id_barang=$id";
    
    if (mysqli_query($koneksi, $updateQuery)) {
        // Set pesan sukses ke session
        $_SESSION['success'] = "Barang berhasil diupdate!";
        // Redirect ke halaman sesuai role
        if (isset($_SESSION['admin'])) {
            header("Location: data_barang.php");
        } else {
            header("Location: dashboard_karyawan.php");
        }
        exit();
    } else {
        die("Error updating record: " . mysqli_error($koneksi));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Barang</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="tambah-barang-page">
        <div class="tambah-barang-card">
            <h3>Edit Barang</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control" placeholder="Nama Barang" value="<?= htmlspecialchars($data['nama_barang']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Elektronik" <?= $data['kategori'] == 'Elektronik' ? 'selected' : '' ?>>Elektronik</option>
                        <option value="ATK" <?= $data['kategori'] == 'ATK' ? 'selected' : '' ?>>ATK</option>
                        <option value="Perabotan" <?= $data['kategori'] == 'Perabotan' ? 'selected' : '' ?>>Perabotan</option>
                        <option value="Lainnya" <?= $data['kategori'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Jumlah" value="<?= htmlspecialchars($data['jumlah']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_masuk">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="<?= htmlspecialchars($data['tanggal_masuk']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan" required><?= htmlspecialchars($data['keterangan']) ?></textarea>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <?php
                $kembaliURL = isset($_SESSION['admin']) ? 'data_barang.php' : 'dashboard_karyawan.php';
                ?>
                <a href="<?= $kembaliURL ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</body>

</html>