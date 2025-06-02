<?php
session_start();
include '../config/db.php';
include '../config/auth.php';

onlyAdmin();  // ⬅️ Proteksi untuk admin saja

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "INSERT INTO karyawan 
        (nama, jabatan, email, no_hp, alamat, status) 
        VALUES ('$nama', '$jabatan', '$email', '$no_hp', '$alamat', '$status')");

    // Re-index id_karyawan agar tetap berurutan
    mysqli_query($koneksi, "SET @num := 0");
    mysqli_query($koneksi, "UPDATE karyawan SET id_karyawan = (@num := @num + 1) ORDER BY id_karyawan");
    mysqli_query($koneksi, "ALTER TABLE karyawan AUTO_INCREMENT = 1");

    // Set pesan sukses ke session
    $_SESSION['success'] = "Karyawan berhasil ditambahkan!";

    header("Location: data_karyawan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tambah Karyawan</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="tambah-barang-page">
        <div class="tambah-barang-card">
            <h3>Tambah Karyawan</h3>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" required>
                </div>
                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Jabatan" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="no_hp">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Nomor HP" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="non aktif">Non Aktif</option>
                    </select>
                </div>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                <a href="data_karyawan.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>

</body>

</html>