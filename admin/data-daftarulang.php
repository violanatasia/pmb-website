<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$admin_username = $_SESSION['username'] ?? 'Admin';

// Tampilkan pesan sukses
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';
unset($_SESSION['success']);
unset($_SESSION['error']);

// Ambil semua data (tanpa filter)
$query = mysqli_query($conn, "
  SELECT d.*, u.nama, u.nomor_tes
  FROM daftar_ulang d
  JOIN users u ON d.user_id = u.id
  ORDER BY d.created_at DESC
");

// Hitung statistik
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM daftar_ulang"))['total'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM daftar_ulang WHERE status_verifikasi = 'pending'"))['total'];
$verified = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM daftar_ulang WHERE status_verifikasi = 'verified'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Ulang - Oriental University</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

/* STATS CARDS */
.stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 25px;
}

.stat-card {
  background: white;
  padding: 20px;
  border-radius: 12px;
  text-align: center;
  transition: 0.3s;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  border-left: 4px solid #7b0f0f;
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 20px rgba(123,15,15,0.1);
}

.stat-card.total h3 { color: #7b0f0f; }
.stat-card.pending h3 { color: #f5b400; }
.stat-card.verified h3 { color: #28a745; }

.stat-card h3 {
  font-size: 32px;
  margin-bottom: 5px;
  font-weight: 700;
}

.stat-card p {
  color: #666;
  font-size: 13px;
  font-weight: 500;
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
  padding: 12px;
  background: #f8f9fa;
  color: #555;
  font-size: 12px;
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

/* STATUS BADGE */
.status-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 500;
}

.status-pending {
  background: #fff3cd;
  color: #856404;
}

.status-verified {
  background: #d4edda;
  color: #155724;
}

/* BUTTON */
.btn-verify {
  background: #28a745;
  color: white;
  border: none;
  padding: 6px 14px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 11px;
  font-weight: 500;
  transition: 0.2s;
}

.btn-verify:hover {
  background: #218838;
}

.btn-verify i {
  margin-right: 4px;
}

.done-badge {
  color: #28a745;
  font-size: 12px;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

/* MODAL */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
}

.modal-content {
  background-color: white;
  margin: 100px auto;
  border-radius: 12px;
  width: 90%;
  max-width: 450px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}

.modal-header {
  padding: 15px 20px;
  background: #7b0f0f;
  color: white;
  border-radius: 12px 12px 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  font-size: 18px;
  margin: 0;
}

.close-modal {
  font-size: 24px;
  cursor: pointer;
}

.modal-body {
  padding: 20px;
}

.modal-footer {
  padding: 15px 20px;
  background: #f8f9fa;
  border-radius: 0 0 12px 12px;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.btn-cancel {
  background: #6c757d;
  color: white;
  border: none;
  padding: 8px 20px;
  border-radius: 6px;
  cursor: pointer;
}

.btn-confirm {
  background: #28a745;
  color: white;
  border: none;
  padding: 8px 20px;
  border-radius: 6px;
  cursor: pointer;
}

/* EMPTY STATE */
.empty-state {
  text-align: center;
  padding: 50px;
  color: #999;
}

.empty-state i {
  font-size: 48px;
  margin-bottom: 15px;
  display: block;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .sidebar {
    width: 70px;
  }
  .sidebar h2, .sidebar a span {
    display: none;
  }
  .sidebar a {
    justify-content: center;
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
  .stats {
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
      <h1>Verifikasi Daftar Ulang</h1>
      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= htmlspecialchars($admin_username) ?></span>
      </div>
    </div>
    
    <!-- STATS CARDS -->
    <div class="stats">
      <div class="stat-card total">
        <h3><?= $total ?></h3>
        <p>Total Pengajuan</p>
      </div>
      <div class="stat-card pending">
        <h3><?= $pending ?></h3>
        <p>Menunggu Verifikasi</p>
      </div>
      <div class="stat-card verified">
        <h3><?= $verified ?></h3>
        <p>Sudah Diverifikasi</p>
      </div>
    </div>
    
    <!-- TABLE CARD -->
    <div class="table-card">
      <div class="table-header">
        <h3><i class="fas fa-list"></i> Daftar Pengajuan Daftar Ulang</h3>
        <span>Total: <?= number_format(mysqli_num_rows($query)) ?> data</span>
      </div>
      
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Nomor Tes</th>
              <th>Prodi</th>
              <th>Status</th>
              <th>Aksi</th>
            </thead>
          <tbody>
            <?php 
            $no = 1;
            if(mysqli_num_rows($query) > 0):
              while($row = mysqli_fetch_assoc($query)):
                $status = $row['status_verifikasi'] ?: 'pending';
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><strong><?= htmlspecialchars($row['nama_lengkap'] ?: $row['nama']) ?></strong></td>
              <td class="nomor-tes"><?= $row['nomor_tes'] ?></td>
              <td><?= htmlspecialchars($row['prodi'] ?: '-') ?></td>
              <td>
                <span class="status-badge <?= $status == 'pending' ? 'status-pending' : 'status-verified' ?>">
                  <?= $status == 'pending' ? 'Menunggu' : 'Diverifikasi' ?>
                </span>
               </td>
              <td>
                <?php if($status == 'pending'): ?>
                  <button class="btn-verify" onclick="verify(<?= $row['id'] ?>, '<?= addslashes($row['nama_lengkap'] ?: $row['nama']) ?>')">
                    <i class="fas fa-check"></i> Verifikasi
                  </button>
                <?php else: ?>
                  <span class="done-badge"><i class="fas fa-check-circle"></i> Selesai</span>
                <?php endif; ?>
               </td>
             </tr>
            <?php 
              endwhile;
            else:
            ?>
            <tr>
              <td colspan="6" class="empty-state">
                <i class="fas fa-inbox"></i>
                Belum ada pengajuan daftar ulang
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- MODAL VERIFIKASI -->
<div id="modalVerify" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="fas fa-check-circle"></i> Verifikasi Daftar Ulang</h3>
      <span class="close-modal" onclick="closeModal()">&times;</span>
    </div>
    <form method="POST" action="proses-verifikasi.php">
      <input type="hidden" name="id" id="verify_id">
      <input type="hidden" name="action" value="verify">
      <div class="modal-body">
        <p>Verifikasi daftar ulang <strong id="verify_name"></strong>?</p>
        <p style="color: #28a745; margin-top: 10px; font-size: 13px;">
          <i class="fas fa-info-circle"></i> Setelah diverifikasi, data akan masuk ke data mahasiswa.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn-confirm">Ya, Verifikasi</button>
      </div>
    </form>
  </div>
</div>

<script>
function verify(id, name) {
    document.getElementById('verify_id').value = id;
    document.getElementById('verify_name').innerHTML = name;
    document.getElementById('modalVerify').style.display = 'block';
}

function closeModal() {
    document.getElementById('modalVerify').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('modalVerify');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

<?php if($success_message): ?>
Swal.fire({
    title: 'Berhasil!',
    text: '<?= $success_message ?>',
    icon: 'success',
    timer: 2000,
    showConfirmButton: false
});
<?php endif; ?>

<?php if($error_message): ?>
Swal.fire({
    title: 'Gagal!',
    text: '<?= $error_message ?>',
    icon: 'error'
});
<?php endif; ?>
</script>

</body>
</html>