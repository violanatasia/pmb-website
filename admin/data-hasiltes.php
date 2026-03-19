<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Ambil username admin
$admin_username = $_SESSION['username'] ?? 'Admin';

/* FILTER */
$filter = $_GET['filter'] ?? 'all';
$where  = "";

if ($filter == 'lulus') {
  $where = "AND status_kelulusan='lulus'";
} elseif ($filter == 'tidak') {
  $where = "AND status_kelulusan='tidak'";
}

/* DATA */
$query = mysqli_query($conn, "
  SELECT nama, nomor_tes, nilai_tes, status_kelulusan
  FROM users
  WHERE status_tes='sudah' $where
  ORDER BY nilai_tes DESC
");

/* RINGKASAN */
$total = mysqli_fetch_assoc(
  mysqli_query($conn,"SELECT COUNT(*) total FROM users WHERE status_tes='sudah'")
)['total'];

$lulus = mysqli_fetch_assoc(
  mysqli_query($conn,"SELECT COUNT(*) total FROM users WHERE status_kelulusan='lulus'")
)['total'];

$tidak = $total - $lulus;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hasil Tes PMB - Oriental University</title>
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

/* Filter Section */
.filter-section {
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

.filter-title {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #333;
  font-weight: 500;
}

.filter-title i {
  color: #7b0f0f;
}

.filter-buttons {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.filter-btn {
  padding: 8px 18px;
  border-radius: 30px;
  font-size: 13px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.filter-btn.all {
  background: #f0f0f0;
  color: #333;
}

.filter-btn.lulus {
  background: #d4edda;
  color: #155724;
}

.filter-btn.tidak {
  background: #f8d7da;
  color: #721c24;
}

.filter-btn.active {
  transform: scale(1.05);
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  font-weight: 600;
}

.filter-btn.all.active {
  background: #7b0f0f;
  color: white;
}

.filter-btn.lulus.active {
  background: #28a745;
  color: white;
}

.filter-btn.tidak.active {
  background: #dc3545;
  color: white;
}

/* Summary Cards */
.summary {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
  margin-bottom: 30px;
}

.summary-card {
  background: white;
  padding: 25px;
  border-radius: 16px;
  border: 1px solid #eee;
  box-shadow: 0 4px 15px rgba(0,0,0,0.03);
  transition: all 0.3s;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  gap: 20px;
}

.summary-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(123,15,15,0.1);
  border-color: #7b0f0f;
}

.summary-icon {
  width: 60px;
  height: 60px;
  background: #f8f9fa;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: #7b0f0f;
}

.summary-info h3 {
  font-size: 32px;
  color: #7b0f0f;
  font-weight: 600;
  line-height: 1.2;
  margin-bottom: 5px;
}

.summary-info p {
  color: #666;
  font-size: 14px;
  font-weight: 500;
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
  padding: 14px 12px;
  border-bottom: 1px solid #eee;
  font-size: 14px;
  color: #444;
}

table tr:hover td {
  background: #fafafa;
}

/* Nilai styling */
.nilai-cell {
  font-weight: 600;
  font-size: 16px;
}

.nilai-cell.green {
  color: #28a745;
}

.nilai-cell.red {
  color: #dc3545;
}

/* Status Badge */
.status-badge {
  display: inline-block;
  padding: 6px 14px;
  border-radius: 30px;
  font-size: 12px;
  font-weight: 600;
  text-align: center;
  min-width: 100px;
}

.status-lulus {
  background: #d4edda;
  color: #155724;
}

.status-tidak {
  background: #f8d7da;
  color: #721c24;
}

/* Nomor tes */
.nomor-tes {
  font-family: monospace;
  font-weight: 500;
  color: #7b0f0f;
}

/* Empty state */
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

.empty-state p {
  font-size: 16px;
}

/* Responsive */
@media (max-width: 992px) {
  .summary {
    grid-template-columns: repeat(2, 1fr);
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
  
  .filter-section {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .summary {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .table-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  table {
    font-size: 13px;
  }
  
  table th, table td {
    padding: 10px 8px;
  }
  
  .status-badge {
    min-width: 80px;
    padding: 4px 8px;
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
      <h1>Hasil Tes PMB</h1>
      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= htmlspecialchars($admin_username) ?></span>
      </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
      <div class="filter-title">
        <i class="fas fa-filter"></i>
        <span>Filter Data</span>
      </div>
      
      <div class="filter-buttons">
        <a href="?filter=all" class="filter-btn all <?= $filter=='all'?'active':'' ?>">
          <i class="fas fa-list"></i> Semua
        </a>
        <a href="?filter=lulus" class="filter-btn lulus <?= $filter=='lulus'?'active':'' ?>">
          <i class="fas fa-check-circle"></i> Lulus
        </a>
        <a href="?filter=tidak" class="filter-btn tidak <?= $filter=='tidak'?'active':'' ?>">
          <i class="fas fa-times-circle"></i> Tidak Lulus
        </a>
      </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="summary">
      <div class="summary-card">
        <div class="summary-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="summary-info">
          <h3><?= number_format($total) ?></h3>
          <p>Total Peserta Tes</p>
        </div>
      </div>

      <div class="summary-card">
        <div class="summary-icon">
          <i class="fas fa-check-circle" style="color: #28a745;"></i>
        </div>
        <div class="summary-info">
          <h3><?= number_format($lulus) ?></h3>
          <p>Lulus</p>
        </div>
      </div>

      <div class="summary-card">
        <div class="summary-icon">
          <i class="fas fa-times-circle" style="color: #dc3545;"></i>
        </div>
        <div class="summary-info">
          <h3><?= number_format($tidak) ?></h3>
          <p>Tidak Lulus</p>
        </div>
      </div>
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">
      <div class="table-header">
        <h3>
          <i class="fas fa-chart-bar"></i>
          Daftar Nilai Peserta
        </h3>
        
        <?php if(mysqli_num_rows($query) > 0): ?>
        <div class="table-stats">
          <i class="fas fa-database"></i>
          <span><?= mysqli_num_rows($query) ?> data ditampilkan</span>
        </div>
        <?php endif; ?>
      </div>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Nomor Tes</th>
              <th>Nilai</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1; 
            if(mysqli_num_rows($query) > 0):
              while($row = mysqli_fetch_assoc($query)): 
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td class="nomor-tes"><?= $row['nomor_tes'] ?></td>
              <td>
                <span class="nilai-cell <?= $row['nilai_tes'] >= 70 ? 'green' : 'red' ?>">
                  <?= $row['nilai_tes'] ?>
                </span>
              </td>
              <td>
                <span class="status-badge <?= $row['status_kelulusan'] == 'lulus' ? 'status-lulus' : 'status-tidak' ?>">
                  <?= $row['status_kelulusan'] == 'lulus' ? 'LULUS' : 'TIDAK LULUS' ?>
                </span>
              </td>
            </tr>
            <?php 
              endwhile;
            else:
            ?>
            <tr>
              <td colspan="5" class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data hasil tes</p>
                <?php if($filter != 'all'): ?>
                <p style="font-size: 13px; margin-top: 5px;">Coba ganti filter atau reset ke "Semua"</p>
                <?php endif; ?>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

</div>

</body>
</html>