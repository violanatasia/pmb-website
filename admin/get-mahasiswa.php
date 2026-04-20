<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT id, nama_lengkap, nik, tempat_lahir, tanggal_lahir, 
            jenis_kelamin, prodi, no_hp, asal_sekolah, nama_ortu, 
            no_hp_ortu, alamat 
            FROM daftar_ulang 
            WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'success' => true,
            'id' => $row['id'],
            'nama_lengkap' => $row['nama_lengkap'],
            'nik' => $row['nik'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'prodi' => $row['prodi'],
            'no_hp' => $row['no_hp'],
            'asal_sekolah' => $row['asal_sekolah'],
            'nama_ortu' => $row['nama_ortu'],
            'no_hp_ortu' => $row['no_hp_ortu'],
            'alamat' => $row['alamat']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
}
?>