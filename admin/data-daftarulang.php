<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Ambil username admin
$admin_username = $_SESSION['username'] ?? 'Admin';

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Turunin dikit biar muat banyak kolom
$offset = ($page - 1) * $limit;

$where = "";
if($search){
  $safe = mysqli_real_escape_string($conn, $search);
  $where = "WHERE u.nama LIKE '%$safe%' 
            OR u.nomor_tes LIKE '%$safe%' 
            OR d.nama_lengkap LIKE '%$safe%'
            OR d.nik LIKE '%$safe%'
            OR d.prodi LIKE '%$safe%'
            OR d.tempat_lahir LIKE '%$safe%'
            OR d.asal_sekolah LIKE '%$safe%'";
}

/* TOTAL DAFTAR ULANG */
$total = mysqli_fetch_assoc(mysqli_query($conn,
  "SELECT COUNT(*) as total FROM daftar_ulang"
))['total'];
$total_pages = ceil($total / $limit);

/* DATA dengan pagination */
$query = mysqli_query($conn, "
  SELECT 
    d.id,
    u.nama as nama_akun,
    u.nomor_tes,
    u.email,
    d.nama_lengkap,
    d.nik,
    d.no_hp,
    d.tempat_lahir,
    d.tanggal_lahir,
    d.jenis_kelamin,
    d.asal_sekolah,
    d.nama_ortu,
    d.no_hp_ortu,
    d.alamat,
    d.prodi,
    d.ijazah,
    d.kk,
    d.foto,
    d.created_at
  FROM daftar_ulang d
  JOIN users u ON d.user_id = u.id
  $where
  ORDER BY d.created_at DESC
  LIMIT $offset, $limit
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Daftar Ulang - Oriental University</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background: #f0f2f5;
}

/* ================= CONTAINER ================= */
.container {
  display: flex;
  min-height: 100vh;
}

/* ================= SIDEBAR ================= */
.sidebar {
  width: 250px;
  background: #7b0f0f;
  color: white;
  padding: 25px 20px;
  position: fixed;
  height: 100vh;
  box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 22px;
  padding-bottom: 15px;
  border-bottom: 1px solid rgba(255,255,255,0.2);
  font-weight: 600;
  letter-spacing: 1px;
}

.sidebar a {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 15px;
  color: white;
  text-decoration: none;
  border-radius: 10px;
  margin-bottom: 5px;
  transition: all 0.3s;
  font-size: 14px;
}

.sidebar a i {
  width: 20px;
  font-size: 16px;
}

.sidebar a:hover {
  background: #f5b400;
  color: #7b0f0f;
  transform: translateX(3px);
}

.sidebar a.active {
  background: #f5b400;
  color: #7b0f0f;
  font-weight: 500;
}

.sidebar a.logout {
  margin-top: 40px;
  border-top: 1px solid rgba(255,255,255,0.2);
  padding-top: 20px;
}

/* ================= CONTENT ================= */
.content {
  flex: 1;
  margin-left: 250px;
  padding: 25px 30px;
}

/* Header */
.header {
  background: white;
  padding: 20px 25px;
  border-radius: 16px;
  margin-bottom: 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 15px rgba(0,0,0,0.03);
  border: 1px solid #eee;
}

.header h1 {
  color: #7b0f0f;
  font-size: 24px;
  font-weight: 600;
  position: relative;
}

.header h1::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 50px;
  height: 3px;
  background: #f5b400;
  border-radius: 10px;
}

.header .user-info {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #f8f9fa;
  padding: 8px 18px;
  border-radius: 30px;
  border: 1px solid #eee;
}

.header .user-info i {
  color: #7b0f0f;
  font-size: 18px;
}

.header .user-info span {
  font-weight: 500;
  color: #333;
}

/* Stats Card */
.stats-card {
  background: white;
  padding: 25px;
  border-radius: 16px;
  margin-bottom: 25px;
  border: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}

.stats-info {
  display: flex;
  align-items: center;
  gap: 15px;
}

.stats-icon {
  width: 60px;
  height: 60px;
  background: #fff0f0;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #7b0f0f;
  font-size: 28px;
}

.stats-text h3 {
  font-size: 32px;
  color: #7b0f0f;
  font-weight: 600;
  margin-bottom: 5px;
}

.stats-text p {
  color: #666;
  font-size: 14px;
}

.stats-badge {
  background: #d4edda;
  color: #155724;
  padding: 12px 25px;
  border-radius: 40px;
  font-size: 16px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Search Section */
.search-section {
  background: white;
  padding: 20px 25px;
  border-radius: 16px;
  margin-bottom: 25px;
  border: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 15px;
}

.search-title {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #333;
  font-weight: 500;
}

.search-title i {
  color: #7b0f0f;
}

.search-box {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #f8f9fa;
  padding: 5px 5px 5px 15px;
  border-radius: 40px;
  border: 1px solid #eee;
}

.search-box input {
  border: none;
  background: transparent;
  padding: 10px 0;
  width: 250px;
  outline: none;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
}

.search-box input::placeholder {
  color: #aaa;
}

.search-box button {
  background: #7b0f0f;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 40px;
  cursor: pointer;
  font-weight: 500;
  transition: 0.2s;
}

.search-box button:hover {
  background: #5e0b0b;
}

/* Table Card */
.table-card {
  background: white;
  padding: 25px;
  border-radius: 16px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.03);
  border: 1px solid #eee;
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 15px;
}

.table-header h3 {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 18px;
  font-weight: 600;
  color: #333;
}

.table-header h3 i {
  color: #7b0f0f;
}

.table-stats {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #666;
  font-size: 14px;
  background: #f8f9fa;
  padding: 8px 16px;
  border-radius: 30px;
}

.table-stats i {
  color: #7b0f0f;
}

.table-responsive {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 1800px; /* Biar semua kolom muat */
}

table th {
  text-align: left;
  padding: 15px 12px;
  background: #f8f9fa;
  color: #555;
  font-size: 13px;
  font-weight: 600;
  white-space: nowrap;
}

table td {
  padding: 15px 12px;
  border-bottom: 1px solid #eee;
  font-size: 14px;
  color: #444;
  vertical-align: middle;
}

table tr:hover td {
  background: #fafafa;
}

/* Badge Prodi */
.prodi-badge {
  display: inline-block;
  padding: 5px 12px;
  border-radius: 30px;
  font-size: 12px;
  font-weight: 600;
  background: #d4edda;
  color: #155724;
  white-space: nowrap;
}

/* Jenis Kelamin Badge */
.jk-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 500;
}

.jk-laki {
  background: #cce5ff;
  color: #004085;
}

.jk-perempuan {
  background: #f8d7da;
  color: #721c24;
}

/* Nomor Tes */
.nomor-tes {
  font-family: monospace;
  font-weight: 500;
  color: #7b0f0f;
  white-space: nowrap;
}

/* Link File */
.file-link {
  color: #7b0f0f;
  text-decoration: none;
  font-size: 12px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: #f8f9fa;
  padding: 4px 8px;
  border-radius: 6px;
  transition: 0.2s;
}

.file-link:hover {
  background: #7b0f0f;
  color: white;
}

/* Foto Thumbnail */
.foto-thumb {
  width: 40px;
  height: 50px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #ddd;
  cursor: pointer;
  transition: 0.2s;
}

.foto-thumb:hover {
  transform: scale(2);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  z-index: 10;
  position: relative;
}

/* Alamat */
.alamat-cell {
  max-width: 200px;
  white-space: normal;
  line-height: 1.5;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  margin-top: 25px;
}

.page-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: white;
  border: 1px solid #eee;
  color: #333;
  text-decoration: none;
  transition: 0.2s;
}

.page-link:hover {
  background: #7b0f0f;
  color: white;
  border-color: #7b0f0f;
}

.page-link.active {
  background: #7b0f0f;
  color: white;
  border-color: #7b0f0f;
}

.page-link.disabled {
  opacity: 0.5;
  pointer-events: none;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #999;
}

.empty-state i {
  font-size: 60px;
  margin-bottom: 20px;
  opacity: 0.3;
}

.empty-state h3 {
  font-size: 18px;
  margin-bottom: 5px;
  color: #666;
}

.empty-state p {
  font-size: 14px;
}

/* Responsive */
@media (max-width: 992px) {
  .search-box input {
    width: 200px;
  }
}

@media (max-width: 768px) {
  .sidebar {
    width: 70px;
    padding: 20px 10px;
  }
  
  .sidebar h2,
  .sidebar a span {
    display: none;
  }
  
  .sidebar a {
    justify-content: center;
    padding: 15px;
  }
  
  .sidebar a i {
    width: auto;
    font-size: 20px;
  }
  
  .content {
    margin-left: 70px;
    padding: 20px 15px;
  }
  
  .header {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }
  
  .stats-card {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .search-section {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .search-box {
    width: 100%;
  }
  
  .search-box input {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .table-header {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
</head>
<body>

<div class="container">

  <!-- INCLUDE SIDEBAR -->
  <?php include "sidebar.php"; ?>

  <!-- CONTENT -->
  <div class="content">
    
    <!-- HEADER -->
    <div class="header">
      <h1>Data Daftar Ulang</h1>
      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= htmlspecialchars($admin_username) ?></span>
      </div>
    </div>

    <!-- STATS CARD -->
    <div class="stats-card">
      <div class="stats-info">
        <div class="stats-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="stats-text">
          <h3><?= number_format($total) ?></h3>
          <p>Total Mahasiswa Terdaftar</p>
        </div>
      </div>
      <div class="stats-badge">
        <i class="fas fa-check-circle"></i>
        <span>Sudah Daftar Ulang</span>
      </div>
    </div>

    <!-- SEARCH SECTION -->
    <div class="search-section">
      <div class="search-title">
        <i class="fas fa-search"></i>
        <span>Cari Data</span>
      </div>
      
      <form method="GET" class="search-box">
        <input type="text" name="search" placeholder="Cari nama / NIK / prodi / asal sekolah..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit">
          <i class="fas fa-search"></i> Cari
        </button>
      </form>
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">
      <div class="table-header">
        <h3>
          <i class="fas fa-list"></i>
          Detail Mahasiswa Daftar Ulang
        </h3>
        
        <?php if(mysqli_num_rows($query) > 0): ?>
        <div class="table-stats">
          <i class="fas fa-database"></i>
          <span>Menampilkan <?= mysqli_num_rows($query) ?> dari <?= number_format($total) ?> data</span>
        </div>
        <?php endif; ?>
      </div>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Foto</th>
              <th>Nama Lengkap</th>
              <th>Nama Akun</th>
              <th>Nomor Tes</th>
              <th>NIK</th>
              <th>Email</th>
              <th>Tempat Lahir</th>
              <th>Tanggal Lahir</th>
              <th>JK</th>
              <th>Asal Sekolah</th>
              <th>Prodi</th>
              <th>No HP</th>
              <th>Nama Ortu</th>
              <th>No HP Ortu</th>
              <th>Alamat</th>
              <th>Ijazah</th>
              <th>KK</th>
              <th>Tgl Daftar</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = $offset + 1; 
            if(mysqli_num_rows($query) > 0):
              while($row = mysqli_fetch_assoc($query)): 
                $tgl_lahir = $row['tanggal_lahir'] ? date('d/m/Y', strtotime($row['tanggal_lahir'])) : '-';
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td>
                <?php if($row['foto'] && file_exists("../uploads/foto/".$row['foto'])): ?>
                  <img src="../uploads/foto/<?= $row['foto'] ?>" 
                       alt="foto" class="foto-thumb">
                <?php else: ?>
                  <i class="fas fa-user-circle" style="font-size: 30px; color: #ccc;"></i>
                <?php endif; ?>
              </td>
              <td><b><?= htmlspecialchars($row['nama_lengkap'] ?: $row['nama_akun']) ?></b></td>
              <td><?= htmlspecialchars($row['nama_akun']) ?></td>
              <td class="nomor-tes"><?= $row['nomor_tes'] ?></td>
              <td><?= htmlspecialchars($row['nik'] ?: '-') ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['tempat_lahir'] ?: '-') ?></td>
              <td><?= $tgl_lahir ?></td>
              <td>
                <?php if($row['jenis_kelamin']): ?>
                  <span class="jk-badge <?= $row['jenis_kelamin'] == 'Laki-laki' ? 'jk-laki' : 'jk-perempuan' ?>">
                    <?= $row['jenis_kelamin'] == 'Laki-laki' ? 'L' : 'P' ?>
                  </span>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row['asal_sekolah'] ?: '-') ?></td>
              <td>
                <span class="prodi-badge">
                  <i class="fas fa-graduation-cap"></i>
                  <?= htmlspecialchars($row['prodi'] ?: '-') ?>
                </span>
              </td>
              <td><?= htmlspecialchars($row['no_hp'] ?: '-') ?></td>
              <td><?= htmlspecialchars($row['nama_ortu'] ?: '-') ?></td>
              <td><?= htmlspecialchars($row['no_hp_ortu'] ?: '-') ?></td>
              <td class="alamat-cell"><?= nl2br(htmlspecialchars($row['alamat'] ?: '-')) ?></td>
              <td>
                <?php if($row['ijazah']): ?>
                  <a href="../uploads/<?= $row['ijazah'] ?>" target="_blank" class="file-link">
                    <i class="fas fa-file-pdf"></i> Lihat
                  </a>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td>
                <?php if($row['kk']): ?>
                  <a href="../uploads/<?= $row['kk'] ?>" target="_blank" class="file-link">
                    <i class="fas fa-file-pdf"></i> Lihat
                  </a>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
            </tr>
            <?php 
              endwhile;
            else:
            ?>
            <tr>
              <td colspan="19" class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Data tidak ditemukan</h3>
                <p>Coba gunakan kata kunci lain atau reset pencarian</p>
                <?php if($search): ?>
                <a href="data-daftarulang.php" style="color: #7b0f0f; text-decoration: none; margin-top: 10px; display: inline-block;">
                  <i class="fas fa-times"></i> Reset Pencarian
                </a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- PAGINATION -->
      <?php if($total_pages > 1 && mysqli_num_rows($query) > 0): ?>
      <div class="pagination">
        <a href="?page=<?= max(1, $page-1) ?>&search=<?= urlencode($search) ?>" 
           class="page-link <?= $page==1?'disabled':'' ?>">
          <i class="fas fa-chevron-left"></i>
        </a>
        
        <?php 
        $start = max(1, $page - 2);
        $end = min($total_pages, $page + 2);
        for($i = $start; $i <= $end; $i++): 
        ?>
          <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" 
             class="page-link <?= $i==$page?'active':'' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>
        
        <a href="?page=<?= min($total_pages, $page+1) ?>&search=<?= urlencode($search) ?>" 
           class="page-link <?= $page==$total_pages?'disabled':'' ?>">
          <i class="fas fa-chevron-right"></i>
        </a>
      </div>
      <?php endif; ?>
    </div>

  </div>

</div>

</body>
</html>