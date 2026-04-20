<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$admin_username = $_SESSION['username'] ?? 'Admin';

/* AMBIL SEMUA DATA HASIL TES */
$query = mysqli_query($conn, "
  SELECT nama, nomor_tes, nilai_tes, status_kelulusan
  FROM users
  WHERE status_tes='sudah'
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
<title>Hasil Tes - Oriental University</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background: #f5f5f5;
}

/* CONTAINER */
.container {
  display: flex;
  min-height: 100vh;
}

/* SIDEBAR */
.sidebar {
  width: 260px;
  background: #7b0f0f;
  color: white;
  position: fixed;
  height: 100vh;
  padding: 25px 0;
  box-shadow: 2px 0 10px rgba(0,0,0,0.1);
  overflow-y: auto;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 20px;
  padding: 0 20px 15px;
  border-bottom: 1px solid rgba(255,255,255,0.2);
}

.sidebar a {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 25px;
  color: white;
  text-decoration: none;
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
}

.sidebar a.active {
  background: #f5b400;
  color: #7b0f0f;
}

.sidebar a.logout {
  margin-top: 50px;
  border-top: 1px solid rgba(255,255,255,0.2);
  padding-top: 20px;
}

/* CONTENT */
.content {
  flex: 1;
  margin-left: 260px;
  padding: 30px;
  min-height: 100vh;
}

/* TOP BAR */
.top-bar {
  background: white;
  padding: 20px 25px;
  border-radius: 12px;
  margin-bottom: 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.top-bar h1 {
  color: #7b0f0f;
  font-size: 24px;
  font-weight: 600;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #f8f9fa;
  padding: 8px 18px;
  border-radius: 25px;
}

.user-info i {
  color: #7b0f0f;
  font-size: 18px;
}

/* SUMMARY CARDS */
.summary {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 30px;
}

.summary-card {
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  display: flex;
  align-items: center;
  gap: 15px;
  transition: 0.3s;
  border-left: 4px solid #7b0f0f;
}

.summary-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 20px rgba(123,15,15,0.1);
}

.summary-icon {
  width: 55px;
  height: 55px;
  background: #f8f9fa;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: #7b0f0f;
}

.summary-info h3 {
  font-size: 28px;
  color: #7b0f0f;
  font-weight: 700;
}

.summary-info p {
  font-size: 13px;
  color: #666;
}

/* TABLE CARD */
.table-card {
  background: white;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #f0f0f0;
}

.table-header h3 {
  font-size: 18px;
  color: #333;
}

.table-header h3 i {
  color: #7b0f0f;
  margin-right: 8px;
}

.table-header span {
  color: #666;
  font-size: 14px;
}

/* TABLE */
.table-responsive {
  overflow-x: auto;
  max-height: calc(100vh - 400px);
  overflow-y: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  text-align: left;
  padding: 15px 12px;
  background: #f8f9fa;
  color: #555;
  font-size: 13px;
  font-weight: 600;
  position: sticky;
  top: 0;
  z-index: 10;
}

.data-table td {
  padding: 12px;
  border-bottom: 1px solid #eee;
  font-size: 13px;
  color: #444;
}

.data-table tr:hover td {
  background: #fafafa;
}

/* BADGES */
.badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 500;
}

.badge-lulus {
  background: #d4edda;
  color: #155724;
}

.badge-tidak {
  background: #f8d7da;
  color: #721c24;
}

.nilai {
  font-weight: 600;
  font-size: 14px;
}

.nilai-hijau {
  color: #28a745;
}

.nilai-merah {
  color: #dc3545;
}

.nomor-tes {
  font-family: monospace;
  font-weight: 500;
  color: #7b0f0f;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .sidebar {
    width: 70px;
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
    padding: 20px;
  }
  
  .top-bar {
    flex-direction: column;
    gap: 15px;
    text-align: center;
  }
  
  .summary {
    grid-template-columns: 1fr;
  }
  
  .table-header {
    flex-direction: column;
    gap: 10px;
    text-align: center;
  }
}
</style>
</head>
<body>

<div class="container">
  <?php include "sidebar.php"; ?>
  
  <div class="content">
    <!-- TOP BAR -->
    <div class="top-bar">
      <h1>Hasil Tes</h1>
      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= htmlspecialchars($admin_username) ?></span>
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
          <i class="fas fa-chart-bar"></i> Daftar Nilai Peserta
        </h3>
        <span>Total: <?= number_format(mysqli_num_rows($query)) ?> peserta</span>
      </div>
      
      <div class="table-responsive">
        <table class="data-table">
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
              <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
              <td class="nomor-tes"><?= $row['nomor_tes'] ?></td>
              <td>
                <span class="nilai <?= $row['nilai_tes'] >= 70 ? 'nilai-hijau' : 'nilai-merah' ?>">
                  <?= $row['nilai_tes'] ?>
                </span>
              </td>
              <td>
                <span class="badge <?= $row['status_kelulusan'] == 'lulus' ? 'badge-lulus' : 'badge-tidak' ?>">
                  <?= $row['status_kelulusan'] == 'lulus' ? 'LULUS' : 'TIDAK LULUS' ?>
                </span>
              </td>
            </tr>
            <?php 
              endwhile;
            else:
            ?>
            <tr>
              <td colspan="5" style="text-align: center; padding: 50px; color: #999;">
                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                Belum ada data hasil tes
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