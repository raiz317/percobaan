<?php
session_start();
include '../config/db.php';
include '../config/auth.php';

onlyAdmin();  // ⬅️ Proteksi untuk admin saja

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
}

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM karyawan WHERE id_karyawan=$id");

// Setelah query hapus berhasil:
mysqli_query($koneksi, "SET @num := 0");
mysqli_query($koneksi, "UPDATE karyawan SET id_karyawan = (@num := @num + 1) ORDER BY id_karyawan");
mysqli_query($koneksi, "ALTER TABLE karyawan AUTO_INCREMENT = 1");

header("Location: data_karyawan.php");
