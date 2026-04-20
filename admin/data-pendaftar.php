<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$admin_username = $_SESSION['username'] ?? 'Admin';

// Ambil semua data tanpa pagination
$query = mysqli_query($conn, "
  SELECT id, nama, email, nomor_tes, status_tes, status_kelulusan, created_at
  FROM users
  ORDER BY created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Pendaftar - Oriental University</title>
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

/* ================= CONTENT ================= */
.content {
  flex: 1;
  margin-left: 260px;
  padding: 30px;
  min-height: 100vh;
}

/* HEADER */
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

/* TABLE - SCROLLABLE */
.table-responsive {
  overflow-x: auto;
  max-height: calc(100vh - 200px);
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
  background: #f8f9fa;
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

.badge-belum {
  background: #fff3cd;
  color: #856404;
}

.badge-sudah {
  background: #d4edda;
  color: #155724;
}

.badge-lulus {
  background: #d4edda;
  color: #155724;
}

.badge-tidak {
  background: #f8d7da;
  color: #721c24;
}

/* INFO TOTAL */
.total-info {
  margin-top: 20px;
  padding-top: 15px;
  border-top: 1px solid #eee;
  text-align: center;
  font-size: 13px;
  color: #666;
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
          <i class="fas fa-users"></i> Daftar Pendaftar
        </h3>
        <span>Total: <?= number_format(mysqli_num_rows($query)) ?> pendaftar</span>
      </div>
      
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Nomor Tes</th>
              <th>Status Tes</th>
              <th>Kelulusan</th>
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
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= $row['nomor_tes'] ?? '-' ?></td>
              <td>
                <span class="badge <?= $row['status_tes']=='sudah' ? 'badge-sudah' : 'badge-belum' ?>">
                  <?= $row['status_tes']=='sudah' ? 'Sudah Tes' : 'Belum Tes' ?>
                </span>
              </td>
              <td>
                <?php if($row['status_kelulusan'] == 'lulus'): ?>
                  <span class="badge badge-lulus">Lulus</span>
                <?php elseif($row['status_kelulusan'] == 'tidak'): ?>
                  <span class="badge badge-tidak">Tidak Lulus</span>
                <?php else: ?>
                  <span class="badge badge-belum">-</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php 
              endwhile;
            else:
            ?>
            <tr>
              <td colspan="6" style="text-align: center; padding: 50px;">
                <i class="fas fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 15px; display: block;"></i>
                Belum ada data pendaftar
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <div class="total-info">
        <i class="fas fa-database"></i> Menampilkan semua <?= number_format(mysqli_num_rows($query)) ?> data pendaftar
      </div>
    </div>
  </div>
</div>

</body>
</html>