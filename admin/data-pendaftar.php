<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Ambil username admin
$admin_username = $_SESSION['username'] ?? 'Admin';

// Pagination
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$total_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM users"))['total'];
$total_pages = ceil($total_data / $limit);

// Query dengan limit
$query = mysqli_query($conn, "
  SELECT id, nama, email, nomor_tes, status_tes, nilai_tes, status_kelulusan, created_at
  FROM users
  ORDER BY created_at DESC
  LIMIT $offset, $limit
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Pendaftar - Oriental University</title>
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

/* Header (tetap di masing-masing file karena bisa beda) */
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

.table-responsive {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

table th {
  text-align: left;
  padding: 15px 12px;
  background: #f8f9fa;
  color: #555;
  font-size: 13px;
  font-weight: 600;
}

table td {
  padding: 12px;
  border-bottom: 1px solid #eee;
  font-size: 14px;
  color: #444;
}

table tr:hover td {
  background: #fafafa;
}

.status-badge {
  display: inline-block;
  padding: 5px 12px;
  border-radius: 30px;
  font-size: 12px;
  font-weight: 500;
  text-align: center;
  min-width: 90px;
}

.status-belum {
  background: #fff3cd;
  color: #856404;
}

.status-sudah {
  background: #d4edda;
  color: #155724;
}

.status-lulus {
  background: #d4edda;
  color: #155724;
}

.status-tidak {
  background: #f8d7da;
  color: #721c24;
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

/* Responsive */
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
}

@media (max-width: 480px) {
  table {
    font-size: 13px;
  }
  
  table th, table td {
    padding: 10px 8px;
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
    
    <!-- HEADER (tetap di sini, bisa beda tiap halaman) -->
    <div class="header">
      <h1>Data Pendaftar</h1>
      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= htmlspecialchars($admin_username) ?></span>
      </div>
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">
      <div class="table-header">
        <h3>
          <i class="fas fa-list"></i>
          Daftar Pendaftar
        </h3>
        <div style="color:#666; font-size:14px;">
          Total: <?= number_format($total_data) ?> pendaftar
        </div>
      </div>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Nomor Tes</th>
              <th>Status Tes</th>
              <th>Nilai</th>
              <th>Kelulusan</th>
              <th>Tanggal Daftar</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = $offset + 1; 
            while($row = mysqli_fetch_assoc($query)): 
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= $row['nomor_tes'] ?? '-' ?></td>
              <td>
                <span class="status-badge <?= $row['status_tes']=='sudah'?'status-sudah':'status-belum' ?>">
                  <?= $row['status_tes']=='sudah' ? 'Sudah Tes' : 'Belum Tes' ?>
                </span>
              </td>
              <td><?= $row['nilai_tes'] ?? '-' ?></td>
              <td>
                <?php if($row['status_kelulusan'] == 'lulus'): ?>
                  <span class="status-badge status-lulus">Lulus</span>
                <?php elseif($row['status_kelulusan'] == 'tidak'): ?>
                  <span class="status-badge status-tidak">Tidak Lulus</span>
                <?php else: ?>
                  <span class="status-badge status-belum">-</span>
                <?php endif; ?>
              </td>
              <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>

            <?php if(mysqli_num_rows($query) == 0): ?>
            <tr>
              <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 15px; display: block;"></i>
                Belum ada data pendaftar
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- PAGINATION -->
      <?php if($total_pages > 1): ?>
      <div class="pagination">
        <a href="?page=<?= max(1, $page-1) ?>" class="page-link <?= $page==1?'disabled':'' ?>">
          <i class="fas fa-chevron-left"></i>
        </a>
        
        <?php for($i = 1; $i <= $total_pages; $i++): ?>
          <a href="?page=<?= $i ?>" class="page-link <?= $i==$page?'active':'' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>
        
        <a href="?page=<?= min($total_pages, $page+1) ?>" class="page-link <?= $page==$total_pages?'disabled':'' ?>">
          <i class="fas fa-chevron-right"></i>
        </a>
      </div>
      <?php endif; ?>
    </div>
  </div>

</div>

</body>
</html>