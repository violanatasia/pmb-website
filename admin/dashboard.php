<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$admin_username = $_SESSION['username'] ?? 'Admin';

/* HITUNG DATA */
$total_user   = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM users"))['total'];
$ikut_tes     = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM users WHERE status_tes='sudah'"))['total'];
$lulus        = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM users WHERE status_kelulusan='lulus'"))['total'];
$tidak_lulus  = $ikut_tes - $lulus; // Hitung tidak lulus
$daftar_ulang = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM daftar_ulang"))['total'];

// Hitung persentase
$persen_tes = $total_user > 0 ? round(($ikut_tes / $total_user) * 100) : 0;
$persen_lulus = $total_user > 0 ? round(($lulus / $total_user) * 100) : 0;
$persen_tidak_lulus = $total_user > 0 ? round(($tidak_lulus / $total_user) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin - Oriental University</title>
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

/* ================= CONTAINER ================= */
.container {
  display: flex;
  min-height: 100vh;
}

/* ================= SIDEBAR ================= */
.sidebar {
  width: 260px;
  background: #7b0f0f;
  color: white;
  position: fixed;
  height: 100vh;
  padding: 25px 0;
  box-shadow: 2px 0 10px rgba(0,0,0,0.1);
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

/* ================= CONTENT ================= */
.content {
  flex: 1;
  margin-left: 260px;
  padding: 30px;
}

/* HEADER */
.top-bar {
  background: white;
  padding: 20px 25px;
  border-radius: 12px;
  margin-bottom: 30px;
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

/* STATS GRID */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  transition: 0.3s;
  border-left: 4px solid #7b0f0f;
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 20px rgba(123,15,15,0.1);
}

.stat-card .icon {
  font-size: 32px;
  color: #7b0f0f;
  opacity: 0.3;
  margin-bottom: 12px;
}

.stat-card .number {
  font-size: 32px;
  font-weight: 700;
  color: #333;
  margin-bottom: 5px;
}

.stat-card .label {
  font-size: 13px;
  color: #666;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
}

.stat-card .percentage {
  font-size: 12px;
  color: #7b0f0f;
  font-weight: 500;
}

/* STATISTIK TAMBAHAN SECTION */
.extra-stats {
  background: white;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.extra-stats h3 {
  margin-bottom: 20px;
  color: #333;
  font-size: 18px;
  padding-bottom: 10px;
  border-bottom: 2px solid #f0f0f0;
}

.extra-stats h3 i {
  color: #7b0f0f;
  margin-right: 8px;
}

.mini-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}

.mini-stat {
  padding: 15px;
  background: #f8f9fa;
  border-radius: 10px;
  text-align: center;
}

.mini-stat .label {
  color: #666;
  font-size: 12px;
  margin-bottom: 8px;
}

.mini-stat .value {
  font-weight: 700;
  color: #7b0f0f;
  font-size: 24px;
}

/* RESPONSIVE */
@media (max-width: 1024px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .mini-stats {
    grid-template-columns: repeat(2, 1fr);
  }
}

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
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .mini-stats {
    grid-template-columns: 1fr;
  }
  
  .top-bar {
    flex-direction: column;
    gap: 15px;
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
      <h1>Dashboard Admin</h1>
      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= htmlspecialchars($admin_username) ?></span>
      </div>
    </div>
    
    <!-- STATS GRID (4 CARD UTAMA) -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="icon"><i class="fas fa-users"></i></div>
        <div class="number"><?= number_format($total_user) ?></div>
        <div class="label">Total Pendaftar</div>
        <div class="percentage">100%</div>
      </div>
      
      <div class="stat-card">
        <div class="icon"><i class="fas fa-pencil-alt"></i></div>
        <div class="number"><?= number_format($ikut_tes) ?></div>
        <div class="label">Sudah Tes</div>
        <div class="percentage"><?= $persen_tes ?>% dari total</div>
      </div>
      
      <div class="stat-card">
        <div class="icon"><i class="fas fa-graduation-cap"></i></div>
        <div class="number"><?= number_format($lulus) ?></div>
        <div class="label">Lulus</div>
        <div class="percentage"><?= $persen_lulus ?>% dari total</div>
      </div>
      
      <div class="stat-card">
        <div class="icon"><i class="fas fa-times-circle"></i></div>
        <div class="number"><?= number_format($tidak_lulus) ?></div>
        <div class="label">Tidak Lulus</div>
        <div class="percentage"><?= $persen_tidak_lulus ?>% dari total</div>
      </div>
    </div>
    
    <!-- STATISTIK TAMBAHAN (3 MINI STATS) -->
    <div class="extra-stats">
      <h3><i class="fas fa-chart-pie"></i> Statistik Tambahan</h3>
      <div class="mini-stats">
        <div class="mini-stat">
          <div class="label">Belum Tes</div>
          <div class="value"><?= number_format($total_user - $ikut_tes) ?></div>
        </div>
        <div class="mini-stat">
          <div class="label">Daftar Ulang</div>
          <div class="value"><?= number_format($daftar_ulang) ?></div>
        </div>
        <div class="mini-stat">
          <div class="label">Tingkat Kelulusan</div>
          <div class="value"><?= $ikut_tes > 0 ? round(($lulus/$ikut_tes)*100) : 0 ?>%</div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>