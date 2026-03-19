<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['id'];

/* ================= AMBIL USER ================= */
$qUser = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user  = mysqli_fetch_assoc($qUser);

if (!$user) {
  die("User tidak ditemukan");
}

/* ================= HANYA LULUS ================= */
if (strtolower((string)$user['status_kelulusan']) !== 'lulus') {
  header("Location: dashboard.php");
  exit;
}

/* ================= CEK SUDAH DAFTAR ================= */
$qCek = mysqli_query($conn, "SELECT * FROM daftar_ulang WHERE user_id='$user_id'");
$sudah_daftar = mysqli_num_rows($qCek) > 0;

$prodi = '';
$nama_kartu = $user['nama'];
$foto = '';
$nim_display = $user['nim'] ?? '-';

/* 🔥 DEFAULT VALUE */
$form_nama_lengkap = $user['nama'];
$form_nik = '';
$form_no_hp = '';
$form_tempat_lahir = '';
$form_tanggal_lahir = '';
$form_jenis_kelamin = '';
$form_asal_sekolah = '';
$form_nama_ortu = '';
$form_no_hp_ortu = '';
$form_alamat = '';
$form_prodi = '';

if ($sudah_daftar) {
  $d = mysqli_fetch_assoc($qCek);
  $prodi = $d['prodi'];
  $nama_kartu = $d['nama_lengkap'];
  $foto = $d['foto'] ?? '';

  $form_nama_lengkap = $d['nama_lengkap'];
  $form_nik = $d['nik'];
  $form_no_hp = $d['no_hp'];
  $form_tempat_lahir = $d['tempat_lahir'] ?? '';
  $form_tanggal_lahir = $d['tanggal_lahir'] ?? '';
  $form_jenis_kelamin = $d['jenis_kelamin'] ?? '';
  $form_asal_sekolah = $d['asal_sekolah'] ?? '';
  $form_nama_ortu = $d['nama_ortu'];
  $form_no_hp_ortu = $d['no_hp_ortu'];
  $form_alamat = $d['alamat'];
  $form_prodi = $d['prodi'];
}

/* ================= FUNCTION UPLOAD ================= */
function uploadFile($field, $folder = 'uploads'){
  if (!empty($_FILES[$field]['name'])) {

    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) return '';

    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','pdf'];

    if (!in_array($ext,$allowed)) return '';
    if ($_FILES[$field]['size'] > 2 * 1024 * 1024) return '';

    if (!is_dir($folder)) {
      mkdir($folder,0777,true);
    }

    $newName = time().'_'.$field.'.'.$ext;
    $path = $folder.'/'.$newName;

    if (move_uploaded_file($_FILES[$field]['tmp_name'],$path)) {
      return $newName;
    }
  }
  return '';
}

/* ================= PROSES SUBMIT ================= */
if (isset($_POST['daftar_ulang']) && !$sudah_daftar) {

  $nama_lengkap = mysqli_real_escape_string($conn,$_POST['nama_lengkap']);
  $nik          = mysqli_real_escape_string($conn,$_POST['nik']);
  $no_hp        = mysqli_real_escape_string($conn,$_POST['no_hp']);
  $tempat_lahir = mysqli_real_escape_string($conn,$_POST['tempat_lahir']);
  $tanggal_lahir = mysqli_real_escape_string($conn,$_POST['tanggal_lahir']);
  $jenis_kelamin = mysqli_real_escape_string($conn,$_POST['jenis_kelamin']);
  $asal_sekolah = mysqli_real_escape_string($conn,$_POST['asal_sekolah']);
  $nama_ortu    = mysqli_real_escape_string($conn,$_POST['nama_ortu']);
  $no_hp_ortu   = mysqli_real_escape_string($conn,$_POST['no_hp_ortu']);
  $alamat       = mysqli_real_escape_string($conn,$_POST['alamat']);
  $prodi        = mysqli_real_escape_string($conn,$_POST['prodi']);

  $file_ijazah = uploadFile('ijazah');
  $file_kk     = uploadFile('kk');
  $file_foto   = uploadFile('foto','uploads/foto');

  if (!$file_ijazah || !$file_kk || !$file_foto) {
    die("Upload file gagal. Pastikan format benar dan ukuran < 2MB.");
  }

  mysqli_query($conn, "
    INSERT INTO daftar_ulang
    (user_id, nama_lengkap, nik, no_hp, tempat_lahir, tanggal_lahir, jenis_kelamin, asal_sekolah, nama_ortu, no_hp_ortu, alamat, prodi, ijazah, kk, foto)
    VALUES
    ('$user_id','$nama_lengkap','$nik','$no_hp','$tempat_lahir','$tanggal_lahir','$jenis_kelamin','$asal_sekolah','$nama_ortu','$no_hp_ortu','$alamat','$prodi','$file_ijazah','$file_kk','$file_foto')
  ");

  /* ================= GENERATE NIM ================= */
  if (empty($user['nim'])) {

    $tahun = date("Y");
    $kode_prodi = strtoupper(substr(str_replace(' ','',$prodi),0,2));

    $qNim = mysqli_query($conn,"
      SELECT nim FROM users
      WHERE nim IS NOT NULL
      ORDER BY nim DESC LIMIT 1
    ");

    $urut = 1;
    if ($n = mysqli_fetch_assoc($qNim)) {
      $urut = (int)substr($n['nim'],-4) + 1;
    }

    $nim = $tahun.$kode_prodi.str_pad($urut,4,'0',STR_PAD_LEFT);

    mysqli_query($conn,"UPDATE users SET nim='$nim' WHERE id='$user_id'");
    $user['nim'] = $nim;
    $nim_display = $nim;
  }

  $sudah_daftar = true;
  $nama_kartu = $nama_lengkap;
  $foto = $file_foto;
  
  // Refresh data
  $qCek = mysqli_query($conn, "SELECT * FROM daftar_ulang WHERE user_id='$user_id'");
  $d = mysqli_fetch_assoc($qCek);
  $prodi = $d['prodi'];
  $form_tempat_lahir = $d['tempat_lahir'];
  $form_tanggal_lahir = $d['tanggal_lahir'];
  $form_jenis_kelamin = $d['jenis_kelamin'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Ulang - Oriental University</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>

/* ✅ STICKY FOOTER FIX */
html,body{
  height:100%;
}
body{
  margin:0;
  font-family:'Poppins',sans-serif;
  background:#f8f9fa;
  display:flex;
  flex-direction:column;
}

/* HEADER */
.header{
  background:#7b0f0f;
  color:white;
  padding:16px 20px;
  text-align:center;
  background: linear-gradient(135deg, #7b0f0f 0%, #a51d1d 100%);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.header img{
  height:90px;
  width:auto;
  object-fit:contain;
  display:block;
  margin:0 auto 6px;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.header h1{
  margin:4px 0 2px;
  font-family:'Playfair Display', serif;
  letter-spacing:1px;
  font-weight:700;
}

.header p{
  margin:0;
  font-size:14px;
  opacity:.9;
}

/* MAIN FLEX */
.main{
  flex:1;
}

/* CONTAINER */
.container{
  max-width:860px;
  margin:40px auto;
  padding:0 20px;
}

/* KARTU MAHASISWA SIMPLE ELEGAN */
.student-card {
  width: 100%;
  max-width: 550px;
  margin: 20px auto;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  background: white;
  transition: transform 0.3s ease;
}

.student-card:hover {
  transform: translateY(-5px);
}

.card-header {
  background: #7b0f0f;
  padding: 20px;
  text-align: center;
  border-bottom: 3px solid #f1c40f;
}

.card-header h3 {
  margin: 0;
  color: white;
  font-family: 'Playfair Display', serif;
  font-size: 22px;
  letter-spacing: 1px;
}

.card-header p {
  margin: 5px 0 0;
  color: rgba(255,255,255,0.9);
  font-size: 14px;
}

.card-content {
  padding: 25px;
  display: flex;
  gap: 25px;
  background: white;
}

.photo-box {
  width: 120px;
  height: 150px;
  background: #f0f0f0;
  border-radius: 12px;
  overflow: hidden;
  border: 3px solid #7b0f0f;
  flex-shrink: 0;
}

.photo-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.no-photo {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #e0e0e0;
  color: #666;
  font-size: 13px;
  text-align: center;
}

.info-details {
  flex: 1;
}

.info-details h2 {
  margin: 0 0 5px 0;
  font-size: 20px;
  color: #333;
  font-weight: 600;
}

.nim {
  color: #7b0f0f;
  font-weight: 600;
  font-size: 18px;
  margin: 0 0 15px 0;
  padding-bottom: 10px;
  border-bottom: 2px dashed #ddd;
}

.info-row {
  margin-bottom: 10px;
  display: flex;
  align-items: baseline;
}

.info-label {
  font-size: 13px;
  color: #777;
  width: 100px;
  flex-shrink: 0;
}

.info-value {
  font-size: 14px;
  color: #333;
  font-weight: 500;
}

.card-footer {
  background: #f8f8f8;
  padding: 15px 20px;
  text-align: center;
  border-top: 1px solid #eee;
  font-size: 12px;
  color: #7b0f0f;
  font-weight: 500;
}

/* FORM */
.form-container {
  background: white;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.form-container h2 {
  color: #333;
  font-family: 'Playfair Display', serif;
  margin-top: 0;
  margin-bottom: 20px;
  font-size: 26px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

.form-group {
  margin-bottom: 5px;
}

.form-group.full-width {
  grid-column: span 2;
}

label {
  display: block;
  margin-bottom: 6px;
  color: #555;
  font-weight: 500;
  font-size: 14px;
}

input, textarea, select {
  width: 100%;
  padding: 12px 15px;
  border-radius: 12px;
  border: 1.5px solid #e0e0e0;
  font-family: 'Poppins', sans-serif;
  transition: all 0.3s;
  box-sizing: border-box;
  font-size: 14px;
}

input:focus, textarea:focus, select:focus {
  outline: none;
  border-color: #7b0f0f;
  box-shadow: 0 0 0 3px rgba(123, 15, 15, 0.1);
}

.file-input {
  padding: 8px 12px;
  background: #f9f9f9;
}

.btn {
  margin-top: 25px;
  width: 100%;
  padding: 14px;
  border: none;
  border-radius: 12px;
  background: #7b0f0f;
  color: white;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s;
}

.btn:hover {
  background: #5e0b0b;
}

.btn-secondary {
  background: white;
  color: #7b0f0f;
  border: 2px solid #7b0f0f;
  margin-top: 10px;
}

.btn-secondary:hover {
  background: #fff5f5;
}

.button-group {
  display: flex;
  gap: 15px;
  justify-content: center;
  margin-top: 25px;
}

.button-group .btn {
  width: auto;
  padding: 12px 30px;
  margin-top: 0;
}

/* FOOTER */
.footer{
  background:#0c0c0c;
  text-align:center;
  padding:25px;
  font-size:14px;
  color:#aaa;
  border-top:1px solid rgba(255,255,255,.08);
}

</style>
</head>

<body>

<div class="header">
  <img src="assets/logo_ou.png" alt="Oriental University">
  <h1>ORIENTAL UNIVERSITY</h1>
  <p>Pendaftaran Ulang Mahasiswa</p>
</div>

<div class="main">
<div class="container">

<?php if($sudah_daftar): ?>

<!-- KARTU MAHASISWA SIMPLE -->
<div class="student-card" id="cardArea">
  <div class="card-header">
    <h3>KARTU MAHASISWA</h3>
    <p>ORIENTAL UNIVERSITY</p>
  </div>

  <div class="card-content">
    <div class="photo-box">
      <?php if(!empty($foto) && file_exists("uploads/foto/".$foto)): ?>
        <img src="uploads/foto/<?= htmlspecialchars($foto) ?>" alt="foto mahasiswa">
      <?php else: ?>
        <div class="no-photo">
          FOTO<br>TIDAK ADA
        </div>
      <?php endif; ?>
    </div>
    
    <div class="info-details">
      <h2><?= htmlspecialchars($nama_kartu) ?></h2>
      <div class="nim">NIM: <?= htmlspecialchars($nim_display) ?></div>
      
      <div class="info-row">
        <span class="info-label">Prodi</span>
        <span class="info-value">: <?= htmlspecialchars($prodi) ?></span>
      </div>
      
      <div class="info-row">
        <span class="info-label">Jenis Kelamin</span>
        <span class="info-value">: <?= htmlspecialchars($form_jenis_kelamin) ?></span>
      </div>
      
      <div class="info-row">
        <span class="info-label">Tempat, Tanggal Lahir</span>
        <span class="info-value">: <?= htmlspecialchars($form_tempat_lahir) ?>, <?= date('d/m/Y', strtotime($form_tanggal_lahir)) ?></span>
      </div>
    </div>
  </div>

  <div class="card-footer">
    <span>✧ Kartu ini berlaku selama menjadi mahasiswa aktif ✧</span>
  </div>
</div>

<div class="button-group">
  <button onclick="downloadCard()" class="btn">
    Download Kartu
  </button>
  <a href="dashboard.php" class="btn btn-secondary">
    Dashboard
  </a>
</div>

<?php else: ?>

<div class="form-container">
  <h2>Form Daftar Ulang</h2>
  <p style="color: #666; margin-bottom: 25px;">Silakan lengkapi data diri Anda untuk proses daftar ulang</p>

  <form method="post" enctype="multipart/form-data">
    <div class="form-grid">
      <div class="form-group full-width">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($form_nama_lengkap) ?>" required>
      </div>

      <div class="form-group">
        <label>NIK</label>
        <input type="text" name="nik" value="<?= htmlspecialchars($form_nik) ?>" required>
      </div>

      <div class="form-group">
        <label>No HP Peserta</label>
        <input type="text" name="no_hp" value="<?= htmlspecialchars($form_no_hp) ?>" required>
      </div>

      <div class="form-group">
        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" value="<?= htmlspecialchars($form_tempat_lahir) ?>" required>
      </div>

      <div class="form-group">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($form_tanggal_lahir) ?>" required>
      </div>

      <div class="form-group">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" required>
          <option value="">-- Pilih --</option>
          <option value="Laki-laki" <?= $form_jenis_kelamin=='Laki-laki'?'selected':'' ?>>Laki-laki</option>
          <option value="Perempuan" <?= $form_jenis_kelamin=='Perempuan'?'selected':'' ?>>Perempuan</option>
        </select>
      </div>

      <div class="form-group full-width">
        <label>Asal Sekolah</label>
        <input type="text" name="asal_sekolah" value="<?= htmlspecialchars($form_asal_sekolah) ?>" required>
      </div>

      <div class="form-group">
        <label>Nama Orang Tua</label>
        <input type="text" name="nama_ortu" value="<?= htmlspecialchars($form_nama_ortu) ?>" required>
      </div>

      <div class="form-group">
        <label>No HP Orang Tua</label>
        <input type="text" name="no_hp_ortu" value="<?= htmlspecialchars($form_no_hp_ortu) ?>" required>
      </div>

      <div class="form-group full-width">
        <label>Alamat Lengkap</label>
        <textarea name="alamat" rows="3" required><?= htmlspecialchars($form_alamat) ?></textarea>
      </div>

      <div class="form-group full-width">
        <label>Program Studi</label>
        <select name="prodi" required>
          <option value="">-- Pilih Program Studi --</option>
          <option <?= $form_prodi=='Computer Science'?'selected':'' ?>>Computer Science</option>
          <option <?= $form_prodi=='Information Systems'?'selected':'' ?>>Information Systems</option>
          <option <?= $form_prodi=='International Relations'?'selected':'' ?>>International Relations</option>
          <option <?= $form_prodi=='Business Administration'?'selected':'' ?>>Business Administration</option>
          <option <?= $form_prodi=='Economics'?'selected':'' ?>>Economics</option>
        </select>
      </div>

      <div class="form-group">
        <label>Upload Ijazah (PDF/JPG)</label>
        <input type="file" name="ijazah" class="file-input" required>
      </div>

      <div class="form-group">
        <label>Upload Kartu Keluarga</label>
        <input type="file" name="kk" class="file-input" required>
      </div>

      <div class="form-group full-width">
        <label>Upload Foto Formal</label>
        <input type="file" name="foto" class="file-input" accept="image/*" required>
        <small style="color: #666;">Format: JPG/PNG, Max 2MB</small>
      </div>
    </div>

    <button name="daftar_ulang" class="btn">SUBMIT PENDAFTARAN ULANG</button>
  </form>
  
  <div style="text-align: center; margin-top: 20px;">
    <a href="dashboard.php" style="color: #7b0f0f; text-decoration: none; font-size: 14px;">Kembali Ke Dashboard</a>
  </div>
</div>

<?php endif; ?>

</div>
</div>

<div class="footer">
  © <?= date('Y') ?> Oriental University • Admission System
</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
function downloadCard() {
  const card = document.getElementById("cardArea");
  html2canvas(card, {
    scale: 2,
    backgroundColor: '#ffffff'
  }).then(canvas => {
    const link = document.createElement("a");
    link.download = "kartu_mahasiswa_<?= $user['nim'] ?? 'baru' ?>.png";
    link.href = canvas.toDataURL("image/png");
    link.click();
  });
}
</script>

</body>
</html>