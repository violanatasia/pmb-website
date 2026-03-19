<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Ambil username admin
$admin_username = $_SESSION['username'] ?? 'Admin';

/* SIMPAN */
if (isset($_POST['simpan'])) {
  $pertanyaan = mysqli_real_escape_string($conn, $_POST['pertanyaan']);
  $a = mysqli_real_escape_string($conn, $_POST['opsi_a']);
  $b = mysqli_real_escape_string($conn, $_POST['opsi_b']);
  $c = mysqli_real_escape_string($conn, $_POST['opsi_c']);
  $d = mysqli_real_escape_string($conn, $_POST['opsi_d']);
  $jawaban = $_POST['jawaban'];

  if ($_POST['id'] == "") {
    mysqli_query($conn,"
      INSERT INTO soal 
      (pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban)
      VALUES
      ('$pertanyaan','$a','$b','$c','$d','$jawaban')
    ");
  } else {
    $id = (int)$_POST['id'];
    mysqli_query($conn,"
      UPDATE soal SET
        pertanyaan='$pertanyaan',
        opsi_a='$a',
        opsi_b='$b',
        opsi_c='$c',
        opsi_d='$d',
        jawaban='$jawaban'
      WHERE id='$id'
    ");
  }
  header("Location: kelolasoal.php");
  exit;
}

/* HAPUS */
if (isset($_GET['hapus'])) {
  $id = (int)$_GET['hapus'];
  mysqli_query($conn,"DELETE FROM soal WHERE id='$id'");
  header("Location: kelolasoal.php");
  exit;
}

/* EDIT */
$edit = null;
if (isset($_GET['edit'])) {
  $id = (int)$_GET['edit'];
  $q = mysqli_query($conn,"SELECT * FROM soal WHERE id='$id'");
  $edit = mysqli_fetch_assoc($q);
}

$soal = mysqli_query($conn,"SELECT * FROM soal ORDER BY id DESC");
$total = mysqli_num_rows($soal);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Soal - Oriental University</title>
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

/* Cards */
.card {
  background: white;
  padding: 25px;
  border-radius: 16px;
  margin-bottom: 25px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.03);
  border: 1px solid #eee;
}

.card h3 {
  color: #7b0f0f;
  font-size: 18px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.card h3 i {
  color: #7b0f0f;
}

/* Form */
.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 6px;
  font-size: 13px;
  font-weight: 500;
  color: #555;
}

.form-group label i {
  color: #7b0f0f;
  margin-right: 5px;
}

input, textarea, select {
  width: 100%;
  padding: 12px 15px;
  border-radius: 12px;
  border: 1.5px solid #e0e0e0;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  transition: all 0.2s;
}

input:focus, textarea:focus, select:focus {
  outline: none;
  border-color: #7b0f0f;
  box-shadow: 0 0 0 3px rgba(123,15,15,0.1);
}

textarea {
  min-height: 100px;
  resize: vertical;
}

.btn {
  background: #7b0f0f;
  color: white;
  border: none;
  padding: 14px 20px;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  width: 100%;
  font-size: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn:hover {
  background: #5e0b0b;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(123,15,15,0.2);
}

.btn i {
  font-size: 16px;
}

/* Table */
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

/* Preview Soal */
.preview-row {
  background: #fafafa;
}

.preview-cell {
  padding: 20px !important;
  background: #f8f9fa;
  border-bottom: 2px solid #ddd !important;
}

.preview-box {
  background: white;
  border-radius: 14px;
  padding: 20px;
  border: 1px solid #e0e0e0;
}

.preview-title {
  font-size: 14px;
  color: #7b0f0f;
  font-weight: 600;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 5px;
}

.preview-question {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 10px;
  margin-bottom: 15px;
  font-size: 15px;
  color: #333;
  border-left: 4px solid #7b0f0f;
}

.opsi-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
}

.opsi-item {
  padding: 12px 15px;
  border-radius: 10px;
  border: 1.5px solid #e0e0e0;
  font-size: 14px;
  transition: all 0.2s;
}

.opsi-item.benar {
  background: #e8f5e9;
  border-color: #2e7d32;
  color: #2e7d32;
  font-weight: 500;
  position: relative;
}

.opsi-item.benar::after {
  content: '✓';
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  font-weight: bold;
}

.opsi-item .huruf {
  font-weight: 600;
  color: #7b0f0f;
  margin-right: 8px;
}

.opsi-item.benar .huruf {
  color: #2e7d32;
}

/* Badge */
.badge {
  display: inline-block;
  padding: 5px 12px;
  border-radius: 30px;
  font-size: 12px;
  font-weight: 600;
  background: #f5b400;
  color: #7b0f0f;
}

/* Action Links */
.action-links {
  display: flex;
  gap: 10px;
}

.action-links a {
  color: #7b0f0f;
  text-decoration: none;
  font-size: 13px;
  font-weight: 500;
  padding: 5px 10px;
  border-radius: 8px;
  transition: 0.2s;
}

.action-links a:hover {
  background: #f0f0f0;
}

.action-links a.hapus {
  color: #b00000;
}

.action-links a.hapus:hover {
  background: #ffebee;
}

/* Total Soal Badge */
.total-badge {
  display: inline-block;
  background: #7b0f0f;
  color: white;
  padding: 8px 16px;
  border-radius: 30px;
  font-size: 14px;
  font-weight: 500;
}

.total-badge i {
  margin-right: 5px;
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
  
  .opsi-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .card {
    padding: 20px;
  }
  
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
    
    <!-- HEADER -->
    <div class="header">
      <h1>Kelola Soal</h1>
      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= htmlspecialchars($admin_username) ?></span>
      </div>
    </div>

    <!-- TOTAL SOAL CARD -->
    <div class="card" style="padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-database" style="color: #7b0f0f; font-size: 20px;"></i>
        <span style="font-weight: 500;">Total Soal</span>
      </div>
      <span class="total-badge">
        <i class="fas fa-question-circle"></i> <?= $total ?> Soal
      </span>
    </div>

    <!-- FORM TAMBAH/EDIT SOAL -->
    <div class="card">
      <h3>
        <i class="fas fa-<?= $edit ? 'edit' : 'plus-circle' ?>"></i>
        <?= $edit ? "Edit Soal" : "Tambah Soal Baru" ?>
      </h3>

      <form method="post">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

        <div class="form-group">
          <label><i class="fas fa-question"></i> Pertanyaan</label>
          <textarea name="pertanyaan" placeholder="Tulis pertanyaan di sini..." required><?= $edit['pertanyaan'] ?? '' ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 15px;">
          <div class="form-group">
            <label><i class="fas fa-a"></i> Opsi A</label>
            <input name="opsi_a" placeholder="Opsi A" value="<?= $edit['opsi_a'] ?? '' ?>" required>
          </div>
          <div class="form-group">
            <label><i class="fas fa-b"></i> Opsi B</label>
            <input name="opsi_b" placeholder="Opsi B" value="<?= $edit['opsi_b'] ?? '' ?>" required>
          </div>
          <div class="form-group">
            <label><i class="fas fa-c"></i> Opsi C</label>
            <input name="opsi_c" placeholder="Opsi C" value="<?= $edit['opsi_c'] ?? '' ?>" required>
          </div>
          <div class="form-group">
            <label><i class="fas fa-d"></i> Opsi D</label>
            <input name="opsi_d" placeholder="Opsi D" value="<?= $edit['opsi_d'] ?? '' ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label><i class="fas fa-check-circle"></i> Jawaban Benar</label>
          <select name="jawaban" required>
            <option value="">-- Pilih Jawaban Benar --</option>
            <?php foreach(['A','B','C','D'] as $j): ?>
              <option value="<?= $j ?>" <?= ($edit && $edit['jawaban']==$j)?'selected':'' ?>>
                <?= $j ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <button name="simpan" class="btn">
          <i class="fas fa-<?= $edit ? 'save' : 'plus' ?>"></i>
          <?= $edit ? "Update Soal" : "Simpan Soal" ?>
        </button>
      </form>
    </div>

    <!-- DAFTAR SOAL -->
    <div class="card">
      <h3>
        <i class="fas fa-list"></i>
        Daftar Soal
      </h3>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Pertanyaan</th>
              <th>Jawaban</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1; 
            mysqli_data_seek($soal, 0); // Reset pointer
            while($row = mysqli_fetch_assoc($soal)): 
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['pertanyaan']) ?></td>
              <td>
                <span class="badge"><?= $row['jawaban'] ?></span>
              </td>
              <td>
                <div class="action-links">
                  <a href="?edit=<?= $row['id'] ?>">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <a class="hapus" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus soal ini?')">
                    <i class="fas fa-trash"></i> Hapus
                  </a>
                </div>
              </td>
            </tr>
            <tr class="preview-row">
              <td colspan="4" class="preview-cell">
                <div class="preview-box">
                  <div class="preview-title">
                    <i class="fas fa-eye"></i> Preview Soal
                  </div>
                  
                  <div class="preview-question">
                    <?= htmlspecialchars($row['pertanyaan']) ?>
                  </div>

                  <div class="opsi-grid">
                    <?php foreach(['a','b','c','d'] as $o): ?>
                      <div class="opsi-item <?= strtoupper($o) == $row['jawaban'] ? 'benar' : '' ?>">
                        <span class="huruf"><?= strtoupper($o) ?>.</span>
                        <?= htmlspecialchars($row['opsi_'.$o]) ?>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>

            <?php if($total == 0): ?>
            <tr>
              <td colspan="4" style="text-align: center; padding: 40px; color: #999;">
                <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 15px; display: block;"></i>
                Belum ada soal. Silakan tambah soal pertama.
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