<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

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

/* TOTAL CARD */
.total-card {
  background: white;
  padding: 15px 25px;
  border-radius: 12px;
  margin-bottom: 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  border-left: 4px solid #7b0f0f;
}

.total-card span {
  font-size: 14px;
  color: #666;
}

.total-card .badge {
  background: #7b0f0f;
  color: white;
  padding: 6px 18px;
  border-radius: 25px;
  font-size: 14px;
  font-weight: 600;
}

/* FORM CARD */
.form-card {
  background: white;
  padding: 25px;
  border-radius: 12px;
  margin-bottom: 25px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.form-card h3 {
  color: #7b0f0f;
  font-size: 18px;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #f0f0f0;
}

.form-card h3 i {
  margin-right: 8px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-size: 13px;
  font-weight: 500;
  color: #555;
  margin-bottom: 6px;
}

.form-group label i {
  color: #7b0f0f;
  margin-right: 5px;
  width: 18px;
}

input, textarea, select {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  transition: 0.2s;
}

textarea {
  min-height: 80px;
  resize: vertical;
}

input:focus, textarea:focus, select:focus {
  outline: none;
  border-color: #7b0f0f;
  box-shadow: 0 0 0 3px rgba(123,15,15,0.1);
}

.options-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
  margin-bottom: 5px;
}

.btn {
  width: 100%;
  padding: 12px;
  background: #7b0f0f;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  margin-top: 10px;
  transition: 0.3s;
}

.btn:hover {
  background: #5e0b0b;
  transform: translateY(-2px);
}

/* TABLE CARD */
.table-card {
  background: white;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.table-card h3 {
  color: #7b0f0f;
  font-size: 18px;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #f0f0f0;
}

.table-card h3 i {
  margin-right: 8px;
}

.table-responsive {
  overflow-x: auto;
  max-height: 500px;
  overflow-y: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  text-align: left;
  padding: 12px;
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

/* BADGE */
.badge-jawaban {
  background: #f5b400;
  color: #7b0f0f;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}

/* ACTION */
.action-buttons {
  display: flex;
  gap: 10px;
}

.action-buttons a {
  color: #7b0f0f;
  text-decoration: none;
  font-size: 12px;
  padding: 4px 8px;
  border-radius: 6px;
  transition: 0.2s;
}

.action-buttons a:hover {
  background: #f0f0f0;
}

.action-buttons .delete {
  color: #dc3545;
}

.action-buttons .delete:hover {
  background: #ffe9e9;
}

/* PREVIEW */
.preview-row td {
  background: #fafafa;
  padding: 15px 20px !important;
}

.preview-box {
  background: white;
  border-radius: 10px;
  padding: 15px;
  border: 1px solid #e0e0e0;
}

.preview-question {
  font-size: 13px;
  margin-bottom: 12px;
  padding-left: 10px;
  border-left: 3px solid #7b0f0f;
  font-weight: 500;
}

.preview-opsi {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 8px;
  font-size: 12px;
}

.preview-opsi span {
  padding: 6px 10px;
  background: #f8f9fa;
  border-radius: 6px;
  border: 1px solid #eee;
}

.preview-opsi span.benar {
  background: #e8f5e9;
  border-color: #28a745;
  color: #155724;
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
  
  .options-grid {
    grid-template-columns: 1fr;
  }
  
  .preview-opsi {
    grid-template-columns: 1fr;
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
      <h1>Kelola Soal</h1>
      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= htmlspecialchars($admin_username) ?></span>
      </div>
    </div>
    
    <!-- TOTAL CARD -->
    <div class="total-card">
      <span><i class="fas fa-database"></i> Total Soal dalam Sistem</span>
      <div class="badge"><?= $total ?> Soal</div>
    </div>
    
    <!-- FORM CARD -->
    <div class="form-card">
      <h3>
        <i class="fas fa-<?= $edit ? 'edit' : 'plus-circle' ?>"></i>
        <?= $edit ? "Edit Soal" : "Tambah Soal Baru" ?>
      </h3>
      
      <form method="post">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
        
        <div class="form-group">
          <label><i class="fas fa-question-circle"></i> Pertanyaan</label>
          <textarea name="pertanyaan" placeholder="Tulis pertanyaan di sini..." required><?= $edit['pertanyaan'] ?? '' ?></textarea>
        </div>
        
        <div class="options-grid">
          <div class="form-group">
            <label><i class="fas fa-circle"></i> Opsi A</label>
            <input name="opsi_a" placeholder="Opsi A" value="<?= $edit['opsi_a'] ?? '' ?>" required>
          </div>
          <div class="form-group">
            <label><i class="fas fa-circle"></i> Opsi B</label>
            <input name="opsi_b" placeholder="Opsi B" value="<?= $edit['opsi_b'] ?? '' ?>" required>
          </div>
          <div class="form-group">
            <label><i class="fas fa-circle"></i> Opsi C</label>
            <input name="opsi_c" placeholder="Opsi C" value="<?= $edit['opsi_c'] ?? '' ?>" required>
          </div>
          <div class="form-group">
            <label><i class="fas fa-circle"></i> Opsi D</label>
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
          <i class="fas fa-save"></i> <?= $edit ? "Update Soal" : "Simpan Soal" ?>
        </button>
      </form>
    </div>
    
    <!-- TABLE CARD -->
    <div class="table-card">
      <h3><i class="fas fa-list"></i> Daftar Soal</h3>
      
      <div class="table-responsive">
        <table class="data-table">
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
            mysqli_data_seek($soal, 0);
            if($total > 0):
              while($row = mysqli_fetch_assoc($soal)): 
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['pertanyaan']) ?></td>
              <td><span class="badge-jawaban"><?= $row['jawaban'] ?></span></td>
              <td class="action-buttons">
                <a href="?edit=<?= $row['id'] ?>"><i class="fas fa-edit"></i> Edit</a>
                <a href="?hapus=<?= $row['id'] ?>" class="delete" onclick="return confirm('Yakin ingin menghapus soal ini?')"><i class="fas fa-trash"></i> Hapus</a>
              </td>
            </tr>
            <tr class="preview-row">
              <td colspan="4" style="padding: 0 20px 15px 20px;">
                <div class="preview-box">
                  <div class="preview-question">
                    <?= htmlspecialchars($row['pertanyaan']) ?>
                  </div>
                  <div class="preview-opsi">
                    <?php foreach(['A','B','C','D'] as $huruf): 
                      $opsi = strtolower($huruf);
                    ?>
                      <span class="<?= $huruf == $row['jawaban'] ? 'benar' : '' ?>">
                        <strong><?= $huruf ?>.</strong> <?= htmlspecialchars($row['opsi_'.$opsi]) ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                </div>
              </td>
            </tr>
            <?php 
              endwhile;
            else:
            ?>
            <tr>
              <td colspan="4" style="text-align: center; padding: 50px; color: #999;">
                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
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