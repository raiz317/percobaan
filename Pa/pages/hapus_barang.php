<?php
session_start();
include '../config/db.php';
include '../config/auth.php';

onlyAdminAndKaryawan();

if (!isset($_SESSION['admin']) && !isset($_SESSION['karyawan'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Mulai transaksi
    mysqli_begin_transaction($koneksi);
    
    try {
        // 1. Hapus barang
        mysqli_query($koneksi, "DELETE FROM barang_inventaris WHERE id_barang = $id");
        
        // 2. Perbarui ID barang yang lebih besar
        mysqli_query($koneksi, "UPDATE barang_inventaris SET id_barang = id_barang - 1 WHERE id_barang > $id");
        
        // Commit transaksi
        mysqli_commit($koneksi);
        
        $_SESSION['success'] = "Barang berhasil dihapus dan ID diperbarui!";
    } catch (Exception $e) {
        // Rollback jika ada error
        mysqli_rollback($koneksi);
        $_SESSION['error'] = "Gagal menghapus barang: " . $e->getMessage();
    }
}

// Tentukan halaman redirect berdasarkan role
$redirect_page = isset($_SESSION['admin']) ? 'data_barang.php' : 'dashboard_karyawan.php';

// Redirect dengan mempertahankan parameter filter
$params = [];
if (isset($_GET['keyword'])) {
    $params['keyword'] = $_GET['keyword'];
}
if (isset($_GET['filter_kategori'])) {
    $params['filter_kategori'] = $_GET['filter_kategori'];
}
if (isset($_GET['sort'])) {
    $params['sort'] = $_GET['sort'];
}
if (isset($_GET['page'])) {
    $params['page'] = (int)$_GET['page'];
}

$query_string = !empty($params) ? '?' . http_build_query($params) : '';
header("Location: $redirect_page" . $query_string);
exit();
?>