<?php
session_start();
include '../config/db.php';
include '../config/auth.php';

onlyAdminAndKaryawan();

if (!isset($_SESSION['admin']) && !isset($_SESSION['karyawan'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah'];
    $tanggal = $_POST['tanggal_masuk'];
    $keterangan = $_POST['keterangan'];

    // Cari ID terbesar yang ada
    $query = mysqli_query($koneksi, "SELECT MAX(id_barang) as max_id FROM barang_inventaris");
    $data = mysqli_fetch_assoc($query);
    $new_id = ($data['max_id'] ?? 0) + 1; // Gunakan 0 jika tabel kosong

    mysqli_query($koneksi, "INSERT INTO barang_inventaris 
        (id_barang, nama_barang, kategori, jumlah, tanggal_masuk, keterangan) 
        VALUES ('$new_id', '$nama', '$kategori', '$jumlah', '$tanggal', '$keterangan')");

    $_SESSION['success'] = "Barang berhasil ditambahkan dengan ID $new_id!";
    
    if (isset($_SESSION['admin'])) {
        header("Location: data_barang.php");
    } elseif (isset($_SESSION['karyawan'])) {
        header("Location: dashboard_karyawan.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tambah Barang</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="tambah-barang-page">
        <div class="tambah-barang-card">
            <h3>Tambah Barang</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control" placeholder="Nama Barang" required>
                </div>
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="ATK">ATK</option>
                        <option value="Perabotan">Perabotan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Jumlah" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_masuk">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan" required></textarea>
                </div>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                <?php
                // Tentukan URL kembali berdasarkan role
                $kembaliURL = isset($_SESSION['admin']) ? 'data_barang.php' : 'dashboard_karyawan.php';
                ?>
                <a href="<?= $kembaliURL ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>

</body>

</html>