<?php
session_start();
include '../config/db.php';
include '../config/auth.php';

onlyAdmin();  // ⬅️ Proteksi untuk admin saja

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM karyawan WHERE id_karyawan=$id"));

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "UPDATE karyawan SET 
        nama='$nama', jabatan='$jabatan', email='$email', 
        no_hp='$no_hp', alamat='$alamat', status='$status' 
        WHERE id_karyawan=$id");

    // Set pesan sukses ke session
    $_SESSION['success'] = "Karyawan berhasil diupdate!";

    header("Location: data_karyawan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Karyawan</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            min-height: 100vh;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            overflow: auto;
            background: #f4f7fa;
        }

        .tambah-barang-page {
            min-height: 100vh;
            background: #f4f7fa;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 10px 60px 10px;
            box-sizing: border-box;
            margin-left: 220px;
            /* <-- Tambahkan ini agar tidak ketutup sidebar */
        }

        .tambah-barang-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(60, 72, 88, 0.12);
            padding: 36px 32px 28px 32px;
            width: 100%;
            max-width: 480px;
        }

        .tambah-barang-card h3 {
            margin-bottom: 28px;
            font-weight: 700;
            color: #2d3a4b;
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .btn {
            min-width: 110px;
        }

        @media (max-width: 991.98px) {
            .tambah-barang-page {
                margin-left: 0 !important;
                padding: 18px 2px 30px 2px;
            }
        }

        @media (max-width: 576px) {
            .tambah-barang-card {
                padding: 22px 8px 18px 8px;
            }
        }
    </style>
</head>

<body>
    <div class="tambah-barang-page">
        <div class="tambah-barang-card">
            <h3>Edit Karyawan</h3>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama"
                        value="<?= htmlspecialchars($data['nama']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Jabatan"
                        value="<?= htmlspecialchars($data['jabatan']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email"
                        value="<?= htmlspecialchars($data['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="no_hp">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Nomor HP"
                        value="<?= htmlspecialchars($data['no_hp']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat"
                        value="<?= htmlspecialchars($data['alamat']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" <?= $data['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="non aktif" <?= $data['status'] == 'non aktif' ? 'selected' : '' ?>>Non Aktif
                        </option>
                    </select>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <a href="data_karyawan.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</body>

</html>