<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = $_POST['tanggal_lahir'] ?: null;
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $prodi = mysqli_real_escape_string($conn, $_POST['prodi']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $asal_sekolah = mysqli_real_escape_string($conn, $_POST['asal_sekolah']);
    $nama_ortu = mysqli_real_escape_string($conn, $_POST['nama_ortu']);
    $no_hp_ortu = mysqli_real_escape_string($conn, $_POST['no_hp_ortu']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    // Validasi prodi
    $allowed_prodi = ['Computer Science', 'Information Systems', 'International Relations', 'Business Administration', 'Economics'];
    if (!in_array($prodi, $allowed_prodi)) {
        $_SESSION['error'] = "Program Studi tidak valid!";
        header("Location: data-mahasiswa.php");
        exit;
    }
    
    $sql = "UPDATE daftar_ulang SET 
            nama_lengkap = ?,
            nik = ?,
            tempat_lahir = ?,
            tanggal_lahir = ?,
            jenis_kelamin = ?,
            prodi = ?,
            no_hp = ?,
            asal_sekolah = ?,
            nama_ortu = ?,
            no_hp_ortu = ?,
            alamat = ?
            WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssssi", 
        $nama_lengkap, $nik, $tempat_lahir, $tanggal_lahir, 
        $jenis_kelamin, $prodi, $no_hp, $asal_sekolah, 
        $nama_ortu, $no_hp_ortu, $alamat, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Set session success message
        $_SESSION['success'] = "Data mahasiswa berhasil diupdate";
        header("Location: data-mahasiswa.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal mengupdate data: " . mysqli_error($conn);
        header("Location: data-mahasiswa.php");
        exit;
    }
    
    mysqli_stmt_close($stmt);
} else {
    header("Location: data-mahasiswa.php");
    exit;
}
?>