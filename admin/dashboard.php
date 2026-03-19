<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Ambil username admin
$admin_username = $_SESSION['username'] ?? 'Admin';

/* HITUNG DATA */
$total_user   = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM users"))['total'];
$ikut_tes     = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM users WHERE status_tes='sudah'"))['total'];
$lulus        = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM users WHERE status_kelulusan='lulus'"))['total'];
$daftar_ulang = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM daftar_ulang"))['total'];

// Hitung persentase
$persen_tes = $total_user > 0 ? round(($ikut_tes / $total_user) * 100) : 0;
$persen_lulus = $total_user > 0 ? round(($lulus / $total_user) * 100) : 0;
$persen_daftar_ulang = $total_user > 0 ? round(($daftar_ulang / $total_user) * 100) : 0;

// Ambil 5 pendaftar terbaru
$pendaftar_terbaru = mysqli_query($conn, "
  SELECT nama, nomor_tes, status_tes, status_kelulusan 
  FROM users 
  ORDER BY id DESC 
  LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin - Oriental University</title>
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

/* Welcome text */
.welcome-text {
  background: #fff9e6;
  border-left: 4px solid #f5b400;
  padding: 15px 20px;
  border-radius: 12px;
  margin-bottom: 25px;
  font-size: 14px;
  color: #856404;
  display: flex;
  align-items: center;
  gap: 10px;
}

.welcome-text i {
  color: #f5b400;
  font-size: 20px;
}

/* Cards */
.cards {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 22px;
  margin-bottom: 30px;
}

.card {
  background: white;
  padding: 22px;
  border-radius: 16px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.03);
  border: 1px solid #eee;
  transition: all 0.3s;
  position: relative;
  overflow: hidden;
}

.card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #7b0f0f, #f5b400);
  opacity: 0;
  transition: opacity 0.3s;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(123,15,15,0.1);
  border-color: #7b0f0f;
}

.card:hover::before {
  opacity: 1;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.card-header i {
  font-size: 32px;
  color: #7b0f0f;
  opacity: 0.2;
  transition: opacity 0.3s;
}

.card:hover .card-header i {
  opacity: 0.4;
}

.card h3 {
  font-size: 32px;
  color: #7b0f0f;
  margin-bottom: 5px;
  font-weight: 600;
}

.card p {
  color: #666;
  font-size: 14px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.card small {
  display: block;
  margin-top: 10px;
  color: #999;
  font-size: 12px;
}

/* Progress Section */
.progress-section {
  background: white;
  padding: 25px;
  border-radius: 16px;
  margin-bottom: 30px;
  border: 1px solid #eee;
}

.progress-section h3 {
  margin-bottom: 20px;
  font-size: 18px;
  display: flex;
  align-items: center;
  gap: 8px;
  color: #333;
}

.progress-section h3 i {
  color: #7b0f0f;
}

.progress-item {
  margin-bottom: 20px;
}

.progress-info {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
  font-size: 14px;
  color: #555;
}

.progress-bar {
  height: 8px;
  background: #f0f0f0;
  border-radius: 4px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: #7b0f0f;
  border-radius: 4px;
  width: 0%;
  transition: width 1s ease;
}

/* Bottom Grid */
.bottom-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 25px;
}

.bottom-card {
  background: white;
  padding: 25px;
  border-radius: 16px;
  border: 1px solid #eee;
}

.bottom-card h3 {
  margin-bottom: 20px;
  font-size: 18px;
  display: flex;
  align-items: center;
  gap: 8px;
  color: #333;
}

.bottom-card h3 i {
  color: #7b0f0f;
}

/* Mini Stats */
.mini-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

.mini-stat {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 12px;
  text-align: center;
}

.mini-stat .label {
  font-size: 12px;
  color: #777;
  margin-bottom: 5px;
}

.mini-stat .value {
  font-size: 22px;
  font-weight: 600;
  color: #7b0f0f;
}

/* Table (untuk pendaftar terbaru) */
.table-responsive {
  overflow-x: auto;
}

.mini-table {
  width: 100%;
  border-collapse: collapse;
}

.mini-table th {
  text-align: left;
  padding: 12px 10px;
  background: #f8f9fa;
  color: #555;
  font-size: 12px;
  font-weight: 600;
  border-radius: 8px 8px 0 0;
}

.mini-table td {
  padding: 10px;
  border-bottom: 1px solid #eee;
  font-size: 13px;
  color: #444;
}

.mini-table tr:last-child td {
  border-bottom: none;
}

.status-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 500;
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

.view-link {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  margin-top: 15px;
  color: #7b0f0f;
  text-decoration: none;
  font-size: 13px;
  font-weight: 500;
  transition: 0.2s;
}

.view-link:hover {
  gap: 8px;
}

/* Info cepat */
.info-item {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 12px;
  margin-bottom: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.info-item .label {
  color: #666;
  font-size: 14px;
}

.info-item .value {
  font-weight: 600;
  color: #7b0f0f;
  font-size: 18px;
}

/* Responsive */
@media (max-width: 992px) {
  .cards {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .bottom-grid {
    grid-template-columns: 1fr;
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
  
  .cards {
    grid-template-columns: 1fr;
  }
  
  .mini-stats {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .card h3 {
    font-size: 28px;
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
      <h1>Dashboard Admin</h1>
      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= htmlspecialchars($admin_username) ?></span>
      </div>
    </div>

    <!-- WELCOME TEXT -->
    <div class="welcome-text">
      <i class="fas fa-info-circle"></i>
      Selamat datang, <?= htmlspecialchars($admin_username) ?>. Berikut ringkasan data PMB.
    </div>

    <!-- CARDS STATISTIK -->
    <div class="cards">
      <div class="card">
        <div class="card-header">
          <i class="fas fa-users"></i>
        </div>
        <h3><?= number_format($total_user) ?></h3>
        <p>Total Pendaftar</p>
        <small>Seluruh pendaftar</small>
      </div>

      <div class="card">
        <div class="card-header">
          <i class="fas fa-pencil-alt"></i>
        </div>
        <h3><?= number_format($ikut_tes) ?></h3>
        <p>Sudah Tes</p>
        <small><?= $persen_tes ?>% dari total</small>
      </div>

      <div class="card">
        <div class="card-header">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <h3><?= number_format($lulus) ?></h3>
        <p>Lulus</p>
        <small><?= $persen_lulus ?>% dari total</small>
      </div>

      <div class="card">
        <div class="card-header">
          <i class="fas fa-file-signature"></i>
        </div>
        <h3><?= number_format($daftar_ulang) ?></h3>
        <p>Daftar Ulang</p>
        <small><?= $lulus - $daftar_ulang ?> sisa lulus</small>
      </div>
    </div>

    <!-- PROGRESS SECTION -->
    <div class="progress-section">
      <h3><i class="fas fa-chart-line"></i> Progress PMB</h3>
      
      <div class="progress-item">
        <div class="progress-info">
          <span>Sudah Tes</span>
          <span><?= $persen_tes ?>%</span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="width: <?= $persen_tes ?>%"></div>
        </div>
      </div>

      <div class="progress-item">
        <div class="progress-info">
          <span>Lulus</span>
          <span><?= $persen_lulus ?>%</span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="width: <?= $persen_lulus ?>%"></div>
        </div>
      </div>

      <div class="progress-item">
        <div class="progress-info">
          <span>Daftar Ulang</span>
          <span><?= $persen_daftar_ulang ?>%</span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="width: <?= $persen_daftar_ulang ?>%"></div>
        </div>
      </div>
    </div>

    <!-- BOTTOM GRID -->
    <div class="bottom-grid">
      
      <!-- STATISTIK TAMBAHAN -->
      <div class="bottom-card">
        <h3><i class="fas fa-chart-pie"></i> Statistik Tambahan</h3>
        
        <div class="mini-stats">
          <div class="mini-stat">
            <div class="label">Belum Tes</div>
            <div class="value"><?= number_format($total_user - $ikut_tes) ?></div>
          </div>
          <div class="mini-stat">
            <div class="label">Tidak Lulus</div>
            <div class="value"><?= number_format($ikut_tes - $lulus) ?></div>
          </div>
          <div class="mini-stat">
            <div class="label">Sisa Lulus</div>
            <div class="value"><?= number_format($lulus - $daftar_ulang) ?></div>
          </div>
          <div class="mini-stat">
            <div class="label">% Kelulusan</div>
            <div class="value"><?= $ikut_tes > 0 ? round(($lulus/$ikut_tes)*100) : 0 ?>%</div>
          </div>
        </div>
      </div>

      <!-- PENDAFTAR TERBARU -->
      <div class="bottom-card">
        <h3><i class="fas fa-user-plus"></i> Pendaftar Terbaru</h3>
        
        <div class="table-responsive">
          <table class="mini-table">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Nomor Tes</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if(mysqli_num_rows($pendaftar_terbaru) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($pendaftar_terbaru)): ?>
                <tr>
                  <td><?= htmlspecialchars($row['nama']) ?></td>
                  <td><?= $row['nomor_tes'] ?? '-' ?></td>
                  <td>
                    <?php if($row['status_tes'] == 'belum'): ?>
                      <span class="status-badge status-belum">Belum</span>
                    <?php else: ?>
                      <span class="status-badge status-sudah">Sudah</span>
                    <?php endif; ?>
                    
                    <?php if($row['status_kelulusan'] == 'lulus'): ?>
                      <span class="status-badge status-lulus">Lulus</span>
                    <?php elseif($row['status_kelulusan'] == 'tidak'): ?>
                      <span class="status-badge status-tidak">Tdk Lulus</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="3" style="text-align: center; padding: 20px; color: #999;">
                    Belum ada pendaftar
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        
        <a href="data-pendaftar.php" class="view-link">
          Lihat semua pendaftar <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>

  </div>

</div>

</body>
</html>