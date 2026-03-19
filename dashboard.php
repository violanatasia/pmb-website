<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$nomor_tes = $_SESSION['nomor_tes'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE nomor_tes='$nomor_tes'");
$user  = mysqli_fetch_assoc($query);

if (!$user) {
    echo "Data tidak ditemukan";
    exit;
}

// Cek status daftar ulang dan ambil NIM
$user_id = $user['id'];
$cek_daftar_ulang = mysqli_query($conn, "SELECT * FROM daftar_ulang WHERE user_id='$user_id'");
$sudah_daftar_ulang = mysqli_num_rows($cek_daftar_ulang) > 0;

// Ambil data daftar ulang kalau ada
$data_daftar_ulang = null;
if ($sudah_daftar_ulang) {
    $data_daftar_ulang = mysqli_fetch_assoc($cek_daftar_ulang);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard PMB</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<!-- Font Awesome untuk icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
*{
  box-sizing:border-box;
  margin:0;
  padding:0;
}

html,body{
  height:100%;
  font-family:'Poppins', sans-serif;
}

/* ================= BACKGROUND ================= */
body{
  background:url('assets/bg_class.jpg') center/cover no-repeat fixed;
}

/* ================= HEADER ================= */
.header{
  position:fixed;
  top:0;
  left:0;
  right:0;
  height:70px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:12px 24px;
  z-index:1000;
  background:rgba(123,15,15,.9);
  backdrop-filter: blur(8px);
}

/* kiri header */
.header-left{
  display:flex;
  align-items:center;
  gap:12px;
}

.header img{
  width:75px;
  height:auto;
}

.logo-text{
  display:flex;
  flex-direction:column;
  line-height:1.2;
  color:#fff;
}

.small-text{
  font-size:12px;
  font-weight:400;
  opacity:.85;
}

.big-text{
  font-size:20px;
  font-weight:700;
  letter-spacing:1.5px;
}

/* tombol logout */
.logout-btn{
  background:#fff;
  color:#7b0f0f;
  padding:8px 18px;
  border-radius:10px;
  font-size:14px;
  font-weight:600;
  text-decoration:none;
  transition:all .25s ease;
  white-space:nowrap;
  display: flex;
  align-items: center;
  gap: 6px;
}

.logout-btn:hover{
  background:#f3f3f3;
  transform:translateY(-1px);
  box-shadow:0 4px 12px rgba(0,0,0,.15);
}

/* ================= OVERLAY ================= */
.overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.55);
  padding-top:150px;
  padding-bottom:40px;
  display:flex;
  justify-content:center;
  align-items:flex-start;
  overflow-y:auto;
}

/* ================= CARD ================= */
.card{
  position:relative;
  background:rgba(255,255,255,.97);
  padding:85px 42px 42px;
  border-radius:26px;
  width:100%;
  max-width:440px;
  text-align:center;
  box-shadow:0 30px 70px rgba(0,0,0,.35);
  animation:fadeUp .5s ease;
}

@keyframes fadeUp{
  from{opacity:0; transform:translateY(25px);}
  to{opacity:1; transform:translateY(0);}
}

/* logo bulat */
.card-logo{
  width:110px;
  height:110px;
  background:linear-gradient(135deg,#7b0f0f,#a41616);
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  position:absolute;
  top:-55px;
  left:50%;
  transform:translateX(-50%);
  box-shadow:0 18px 40px rgba(0,0,0,.35);
}

.card-logo img{
  width:160px;
}

/* greeting */
.greeting{
  font-size:14px;
  color:#666;
  margin-bottom:4px;
}

.greeting i {
  color: #7b0f0f;
}

.card h2{
  font-family:'Playfair Display', serif;
  color:#7b0f0f;
  margin:0 0 26px;
  font-size:26px;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
}

/* ================= INFO RINGKASAN (DUAL) ================= */
.info-ringkasan {
  background: linear-gradient(135deg, #f8f9fa, #ffffff);
  border-radius: 18px;
  padding: 15px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  border: 1px solid rgba(123,15,15,0.1);
  box-shadow: 0 4px 8px rgba(0,0,0,0.02);
}

.info-icon {
  width: 45px;
  height: 45px;
  background: #7b0f0f;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 22px;
}

.info-text {
  flex: 1;
  text-align: left;
}

.info-text .label {
  font-size: 11px;
  color: #888;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.info-text .value {
  font-size: 18px;
  font-weight: 600;
  color: #333;
  margin-top: 2px;
}

/* ================= FORM GROUP ================= */
.form-group{
  text-align:left;
  margin-bottom:14px;
}

.form-group label{
  font-size:12.5px;
  font-weight:600;
  color:#555;
  display:block;
  margin-bottom:5px;
}

.form-group label i {
  margin-right: 4px;
  color: #7b0f0f;
  font-size: 12px;
}

.form-group input{
  width:100%;
  padding:12px 14px;
  border-radius:13px;
  border:1px solid #e6e6e6;
  background:#f8f8f8;
  font-size:14px;
  transition: all 0.2s;
}

.form-group input:focus {
  outline: none;
  border-color: #7b0f0f;
  background: #fff;
}

/* ================= BADGE ================= */
.badge-container {
  display: flex;
  gap: 8px;
  justify-content: center;
  margin: 15px 0 10px;
  flex-wrap: wrap;
}

.badge{
  display:inline-block;
  padding:6px 14px;
  border-radius:20px;
  font-size:12px;
  font-weight:600;
}

.badge-belum{
  background:#ffe9a6;
  color:#8a6d00;
}

.badge-sudah{
  background:#d4f8e4;
  color:#1e874b;
}

.badge-daftar {
  background: #e3f2fd;
  color: #0d47a1;
}

.badge i {
  margin-right: 4px;
  font-size: 11px;
}

/* ================= BUTTON ================= */
.btn{
  margin-top:20px;
  width:100%;
  padding:14px;
  border-radius:30px;
  font-weight:700;
  text-align:center;
  text-decoration:none;
  display:block;
  transition:.25s;
  border: none;
  cursor: pointer;
  font-size: 15px;
}

.btn i {
  margin-right: 8px;
}

.btn-test{
  background:#f5b400;
  color:black;
}

.btn-test:hover{background:#ffd24d}

.btn-hasil{
  background:#2ecc71;
  color:white;
}

.btn-hasil:hover{background:#27ae60}

.btn-daftar {
  background: #7b0f0f;
  color: white;
  margin-top: 10px;
}

.btn-daftar:hover {
  background: #5e0b0b;
}

/* mobile */
@media (max-width:500px){
  .card{padding:80px 24px 30px}
  
  .header-left .big-text {
    font-size: 16px;
  }
  
  .header img {
    width: 50px;
  }
}
</style>
</head>

<body>

<header class="header">
  <div class="header-left">
    <img src="assets/logo_ou.png" alt="Logo OU">
    <div class="logo-text">
      <span class="small-text">Penerimaan Mahasiswa Baru</span>
      <span class="big-text">ORIENTAL UNIVERSITY</span>
    </div>
  </div>

  <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Log out</a>
</header>

<div class="overlay">
  <div class="card">

    <div class="card-logo">
      <img src="assets/logo_ou.png">
    </div>

    <div class="greeting">
      <i class="fas fa-hand-peace"></i> Halo, <?= htmlspecialchars($user['nama']); ?>
    </div>
    
    <h2>Dashboard Peserta</h2>

    <!-- INFO RINGKASAN NOMOR TES & NIM (DUAL) -->
    <div class="info-ringkasan">
      <div class="info-icon">
        <i class="fas fa-id-card"></i>
      </div>
      <div class="info-text">
        <div class="label">Nomor Tes</div>
        <div class="value"><?= htmlspecialchars($user['nomor_tes']); ?></div>
        <!-- TAMPILKAN NIM HANYA JIKA SUDAH DAFTAR ULANG DAN LULUS -->
        <?php if ($sudah_daftar_ulang && !empty($user['nim'])): ?>
          <div style="margin-top: 5px; padding-top: 5px; border-top: 1px dashed #ccc;">
            <span style="font-size: 11px; color: #888;">NIM</span>
            <div style="font-size: 16px; font-weight: 600; color: #7b0f0f;"><?= htmlspecialchars($user['nim']); ?></div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- BADGE STATUS -->
    <div class="badge-container">
      <?php if ($user['status_tes']=='belum') { ?>
        <span class="badge badge-belum"><i class="fas fa-clock"></i> Belum Tes</span>
      <?php } else { ?>
        <span class="badge badge-sudah"><i class="fas fa-check-circle"></i> Sudah Tes</span>
      <?php } ?>
      
      <?php if ($sudah_daftar_ulang) { ?>
        <span class="badge badge-daftar"><i class="fas fa-check-double"></i> Terdaftar</span>
      <?php } ?>
    </div>

    <!-- FORM GROUP -->
    <div class="form-group">
      <label><i class="fas fa-user"></i> Nama Lengkap</label>
      <input type="text" value="<?= htmlspecialchars($user['nama']); ?>" readonly>
    </div>

    <div class="form-group">
      <label><i class="fas fa-envelope"></i> Email</label>
      <input type="text" value="<?= htmlspecialchars($user['email']); ?>" readonly>
    </div>

    <!-- BUTTON UTAMA -->
    <?php if ($user['status_tes'] == 'belum') { ?>
      <a href="tes-online.php" class="btn btn-test">
        <i class="fas fa-pencil-alt"></i> MULAI TES
      </a>
    <?php } else { ?>
      <a href="hasil-tes.php" class="btn btn-hasil">
        <i class="fas fa-chart-bar"></i> LIHAT HASIL TES
      </a>
      
      <!-- TOMBOL DAFTAR ULANG (KALAU LULUS) -->
      <?php if (strtolower((string)$user['status_kelulusan']) == 'lulus' && !$sudah_daftar_ulang) { ?>
        <a href="daftar-ulang.php" class="btn btn-daftar">
          <i class="fas fa-file-signature"></i> DAFTAR ULANG
        </a>
      <?php } elseif ($sudah_daftar_ulang) { ?>
        <a href="daftar-ulang.php" class="btn btn-daftar">
          <i class="fas fa-id-card"></i> LIHAT KARTU
        </a>
      <?php } ?>
    <?php } ?>

  </div>
</div>

</body>
</html>