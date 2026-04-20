<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];
    
    if ($action == 'verify') {
        mysqli_query($conn, "UPDATE daftar_ulang SET status_verifikasi = 'verified' WHERE id = $id");
        $_SESSION['success'] = "Data berhasil diverifikasi!";
    } elseif ($action == 'reject') {
        $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
        mysqli_query($conn, "UPDATE daftar_ulang SET status_verifikasi = 'rejected', keterangan_verifikasi = '$keterangan' WHERE id = $id");
        $_SESSION['success'] = "Data berhasil ditolak!";
    }
    
    header("Location: data-daftarulang.php");
    exit;
}
?>