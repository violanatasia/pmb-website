<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$admin_username = $_SESSION['username'] ?? 'Admin';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

// Hitung total
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM daftar_ulang");
$total = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total / $limit);

// Ambil data
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
  ORDER BY d.created_at DESC
  LIMIT $limit OFFSET $offset
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Mahasiswa - Oriental University</title>
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

/* TOTAL CARD */
.total-card {
  background: white;
  padding: 20px 25px;
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
  padding: 8px 20px;
  border-radius: 25px;
  font-size: 16px;
  font-weight: 600;
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
  max-height: calc(100vh - 350px);
  overflow-y: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 1200px;
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
  vertical-align: middle;
}

.data-table tr:hover td {
  background: #fafafa;
}

/* BADGES */
.badge-prodi {
  background: #e3f2fd;
  color: #0d47a1;
  padding: 4px 10px;
  border-radius: 15px;
  font-size: 11px;
  font-weight: 500;
  display: inline-block;
}

.badge-jk {
  padding: 3px 8px;
  border-radius: 12px;
  font-size: 10px;
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

/* FOTO */
.foto-thumb {
  width: 40px;
  height: 50px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #ddd;
  cursor: pointer;
}

/* FILE LINK */
.file-link {
  color: #7b0f0f;
  text-decoration: none;
  font-size: 11px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: #f8f9fa;
  padding: 3px 8px;
  border-radius: 5px;
  margin: 2px;
}

.file-link:hover {
  background: #7b0f0f;
  color: white;
}

/* ACTION BUTTONS */
.action-buttons {
  display: flex;
  gap: 8px;
}

.btn-edit, .btn-delete {
  padding: 5px 10px;
  border-radius: 6px;
  font-size: 11px;
  border: none;
  cursor: pointer;
  transition: 0.2s;
}

.btn-edit {
  background: #f5b400;
  color: #7b0f0f;
}

.btn-edit:hover {
  background: #e0a800;
}

.btn-delete {
  background: #dc3545;
  color: white;
}

.btn-delete:hover {
  background: #c82333;
}

/* PAGINATION */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  margin-top: 25px;
  padding-top: 20px;
  border-top: 1px solid #eee;
}

.page-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 36px;
  height: 36px;
  padding: 0 10px;
  border-radius: 8px;
  background: white;
  border: 1px solid #e0e0e0;
  color: #333;
  text-decoration: none;
  font-size: 13px;
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
  cursor: not-allowed;
  pointer-events: none;
}

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
  margin: 50px auto;
  border-radius: 12px;
  width: 90%;
  max-width: 600px;
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
}

.close-modal {
  font-size: 24px;
  cursor: pointer;
}

.modal-body {
  padding: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-size: 12px;
  font-weight: 500;
  color: #555;
  margin-bottom: 5px;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #7b0f0f;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.modal-footer {
  padding: 15px 20px;
  background: #f8f9fa;
  border-radius: 0 0 12px 12px;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.btn-save, .btn-cancel {
  padding: 8px 20px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-weight: 500;
}

.btn-save {
  background: #7b0f0f;
  color: white;
}

.btn-cancel {
  background: #6c757d;
  color: white;
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
  .form-row {
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
      <h1>Data Mahasiswa</h1>
      <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <span><?= htmlspecialchars($admin_username) ?></span>
      </div>
    </div>
    
    <!-- TOTAL CARD -->
    <div class="total-card">
      <span><i class="fas fa-users"></i> Total Mahasiswa Terdaftar</span>
      <div class="badge"><?= number_format($total) ?> Mahasiswa</div>
    </div>
    
    <!-- TABLE CARD -->
    <div class="table-card">
      <div class="table-header">
        <h3><i class="fas fa-list"></i> Daftar Mahasiswa</h3>
        <span>Total: <?= number_format($total) ?> data</span>
      </div>
      
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Foto</th>
              <th>Nama</th>
              <th>NIM</th>
              <th>Prodi</th>
              <th>JK</th>
              <th>Tempat/Tgl Lahir</th>
              <th>Alamat</th>
              <th>No HP</th>
              <th>File</th>
              <th>Aksi</th>
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
                  <img src="../uploads/foto/<?= $row['foto'] ?>" class="foto-thumb" onclick="window.open(this.src)">
                <?php else: ?>
                  <i class="fas fa-user-circle" style="font-size: 30px; color: #ccc;"></i>
                <?php endif; ?>
               </td>
              <td><strong><?= htmlspecialchars($row['nama_lengkap'] ?: $row['nama_akun']) ?></strong></td>
              <td class="nomor-tes"><?= $row['nomor_tes'] ?></td>
              <td><span class="badge-prodi"><?= htmlspecialchars($row['prodi'] ?: '-') ?></span></td>
              <td>
                <?php if($row['jenis_kelamin']): ?>
                  <span class="badge-jk <?= $row['jenis_kelamin'] == 'Laki-laki' ? 'jk-laki' : 'jk-perempuan' ?>">
                    <?= $row['jenis_kelamin'] == 'Laki-laki' ? 'L' : 'P' ?>
                  </span>
                <?php else: ?>-<?php endif; ?>
               </td>
              <td><?= htmlspecialchars($row['tempat_lahir'] ?: '-') ?>, <?= $tgl_lahir ?></td>
              <td><?= htmlspecialchars(substr($row['alamat'] ?? '-', 0, 30)) ?>...</td>
              <td><?= htmlspecialchars($row['no_hp'] ?: '-') ?></td>
              <td>
                <?php if($row['ijazah']): ?>
                  <a href="../uploads/<?= $row['ijazah'] ?>" target="_blank" class="file-link"><i class="fas fa-file"></i> Ijazah</a>
                <?php endif; ?>
                <?php if($row['kk']): ?>
                  <a href="../uploads/<?= $row['kk'] ?>" target="_blank" class="file-link"><i class="fas fa-file"></i> KK</a>
                <?php endif; ?>
               </td>
              <td class="action-buttons">
                <button class="btn-edit" onclick="editData(<?= $row['id'] ?>)"><i class="fas fa-edit"></i> Edit</button>
                <button class="btn-delete" onclick="deleteData(<?= $row['id'] ?>, '<?= addslashes($row['nama_lengkap']) ?>')"><i class="fas fa-trash"></i> Hapus</button>
               </td>
             </tr>
            <?php 
              endwhile;
            else:
            ?>
             <tr>
              <td colspan="11" class="empty-state">
                <i class="fas fa-inbox"></i>
                Belum ada data mahasiswa
               </td>
             </tr>
            <?php endif; ?>
          </tbody>
         </table>
      </div>
      
      <!-- PAGINATION -->
      <?php if($total_pages > 1 && mysqli_num_rows($query) > 0): ?>
      <div class="pagination">
        <a href="?page=<?= max(1, $page-1) ?>" class="page-link <?= $page==1?'disabled':'' ?>"><i class="fas fa-chevron-left"></i></a>
        <?php 
        $start = max(1, $page - 2);
        $end = min($total_pages, $page + 2);
        for($i = $start; $i <= $end; $i++): 
        ?>
          <a href="?page=<?= $i ?>" class="page-link <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <a href="?page=<?= min($total_pages, $page+1) ?>" class="page-link <?= $page==$total_pages?'disabled':'' ?>"><i class="fas fa-chevron-right"></i></a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- MODAL EDIT -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="fas fa-edit"></i> Edit Data Mahasiswa</h3>
      <span class="close-modal" onclick="closeModal()">&times;</span>
    </div>
    <form id="editForm" method="POST" action="proses-edit-mahasiswa.php">
      <input type="hidden" name="id" id="edit_id">
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required>
          </div>
          <div class="form-group">
            <label>NIK</label>
            <input type="text" name="nik" id="edit_nik">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Tempat Lahir</label>
            <input type="text" name="tempat_lahir" id="edit_tempat_lahir">
          </div>
          <div class="form-group">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" id="edit_jenis_kelamin">
              <option value="">Pilih</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
          <div class="form-group">
            <label>Program Studi</label>
            <select name="prodi" id="edit_prodi" required>
              <option value="">Pilih</option>
              <option value="Computer Science">Computer Science</option>
              <option value="Information Systems">Information Systems</option>
              <option value="International Relations">International Relations</option>
              <option value="Business Administration">Business Administration</option>
              <option value="Economics">Economics</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>No HP</label>
            <input type="text" name="no_hp" id="edit_no_hp">
          </div>
          <div class="form-group">
            <label>Asal Sekolah</label>
            <input type="text" name="asal_sekolah" id="edit_asal_sekolah">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Nama Orang Tua</label>
            <input type="text" name="nama_ortu" id="edit_nama_ortu">
          </div>
          <div class="form-group">
            <label>No HP Orang Tua</label>
            <input type="text" name="no_hp_ortu" id="edit_no_hp_ortu">
          </div>
        </div>
        <div class="form-group">
          <label>Alamat</label>
          <textarea name="alamat" id="edit_alamat" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn-save">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
function editData(id) {
    fetch(`get-mahasiswa.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_nama_lengkap').value = data.nama_lengkap || '';
                document.getElementById('edit_nik').value = data.nik || '';
                document.getElementById('edit_tempat_lahir').value = data.tempat_lahir || '';
                document.getElementById('edit_tanggal_lahir').value = data.tanggal_lahir || '';
                document.getElementById('edit_jenis_kelamin').value = data.jenis_kelamin || '';
                document.getElementById('edit_prodi').value = data.prodi || '';
                document.getElementById('edit_no_hp').value = data.no_hp || '';
                document.getElementById('edit_asal_sekolah').value = data.asal_sekolah || '';
                document.getElementById('edit_nama_ortu').value = data.nama_ortu || '';
                document.getElementById('edit_no_hp_ortu').value = data.no_hp_ortu || '';
                document.getElementById('edit_alamat').value = data.alamat || '';
                document.getElementById('editModal').style.display = 'block';
            } else {
                Swal.fire('Error', 'Gagal mengambil data', 'error');
            }
        })
        .catch(error => Swal.fire('Error', 'Terjadi kesalahan', 'error'));
}

function deleteData(id, nama) {
    Swal.fire({
        title: 'Hapus Data?',
        html: `Yakin hapus <strong>${nama}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `hapus-mahasiswa.php?id=${id}`;
        }
    });
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

</body>
</html>