<?php
function onlyAdmin() {
    if (!isset($_SESSION['admin'])) {
        header("Location: ../pages/access_denied.php");
        exit();
    }
}

function onlyAdminAndKaryawan() {
    if (!isset($_SESSION['admin']) && !isset($_SESSION['karyawan'])) {
        // Kalau bukan admin atau karyawan, blokir
        header('Location: ../pages/access_denied.php');
        exit();
    }
}

function onlyLoggedIn() {
    if (!isset($_SESSION['admin']) && !isset($_SESSION['karyawan'])) {
        header("Location: ../pages/access_denied.php");
        exit();
    }
}
?>
