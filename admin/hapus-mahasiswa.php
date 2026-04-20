<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ambil data file untuk dihapus
    $query = mysqli_query($conn, "SELECT ijazah, kk, foto FROM daftar_ulang WHERE id = $id");
    $data = mysqli_fetch_assoc($query);
    
    // Hapus file-file terkait jika ada
    if ($data) {
        if ($data['ijazah'] && file_exists("../uploads/" . $data['ijazah'])) {
            unlink("../uploads/" . $data['ijazah']);
        }
        if ($data['kk'] && file_exists("../uploads/" . $data['kk'])) {
            unlink("../uploads/" . $data['kk']);
        }
        if ($data['foto'] && file_exists("../uploads/foto/" . $data['foto'])) {
            unlink("../uploads/foto/" . $data['foto']);
        }
    }
    
    // Hapus data dari database
    $sql = "DELETE FROM daftar_ulang WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Data mahasiswa berhasil dihapus";
    } else {
        $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
    }
    
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['error'] = "ID tidak ditemukan";
}

header("Location: data-mahasiswa.php");
exit;
?>