<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['id'];

/* ================= AMBIL DATA USER ================= */
$qUser = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user  = mysqli_fetch_assoc($qUser);

if (!$user) {
  die("User tidak ditemukan");
}

/* ================= CEK APA SUDAH ADA NILAI ================= */
if (
  $user['status_tes'] === 'sudah' &&
  $user['nilai_tes'] !== null &&
  $user['status_kelulusan'] !== null
) {
  $nilai  = (int)$user['nilai_tes'];
  $status = strtoupper((string)$user['status_kelulusan']);

} else {

  // total soal
  $qTotal = mysqli_query($conn, "SELECT COUNT(*) AS total FROM soal");
  $total_soal = (int)mysqli_fetch_assoc($qTotal)['total'];

  // ambil jawaban user
  $qJawab = mysqli_query($conn, "
    SELECT j.jawaban AS jawaban_user, s.jawaban AS jawaban_benar
    FROM jawaban j
    JOIN soal s ON j.soal_id = s.id
    WHERE j.user_id='$user_id'
  ");

  $benar = 0;

  while ($row = mysqli_fetch_assoc($qJawab)) {
    if ($row['jawaban_user'] === $row['jawaban_benar']) {
      $benar++;
    }
  }

  // hitung nilai
  $nilai  = $total_soal > 0 ? round(($benar / $total_soal) * 100) : 0;
  $status = $nilai >= 70 ? "LULUS" : "TIDAK LULUS";

  // simpan ke database
  mysqli_query($conn, "
    UPDATE users SET
      nilai_tes='$nilai',
      status_kelulusan='".strtolower($status)."',
      status_tes='sudah'
    WHERE id='$user_id'
  ");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hasil Tes PMB</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

<style>
*{
  box-sizing:border-box;
}

body{
  margin:0;
  font-family:'Poppins', sans-serif;
  background:#f3f3f3;

  /* ⭐ STICKY FOOTER FIX */
  min-height:100vh;
  display:flex;
  flex-direction:column;
}

/* HEADER */
.header{
  background:#7b0f0f;
  color:white;
  padding:16px 20px;
  text-align:center;
}

.header img{
  height:90px;
  width:auto;
  object-fit:contain;
  display:block;
  margin:0 auto 6px;
}

.header h1{
  margin:4px 0 2px;
  font-family:'Playfair Display', serif;
  letter-spacing:1px;
}

.header p{
  margin:0;
  font-size:14px;
  opacity:.9;
}

/* ⭐ MAIN CONTENT WRAPPER */
.main-content{
  flex:1;
  width:100%;
}

/* CONTAINER */
.container{
  max-width:960px;
  margin:40px auto;
  padding:0 16px;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:24px;
}

/* CARD */
.card{
  background:white;
  padding:30px;
  border-radius:18px;
  box-shadow:0 15px 40px rgba(0,0,0,.08);
}

.card h2{
  margin-top:0;
  color:#7b0f0f;
  font-family:'Playfair Display', serif;
}

/* LINE */
.line{
  border-bottom:1px dashed #ccc;
  margin:14px 0;
}

/* BADGE */
.badge{
  padding:24px;
  border-radius:16px;
  text-align:center;
  font-weight:bold;
  letter-spacing:1px;
  margin-top:10px;
  font-size:22px;
}

.lulus{
  background:linear-gradient(135deg,#c8f7c5,#9de39a);
  color:#0a6b1d;
}

.tidak{
  background:linear-gradient(135deg,#ffd6d6,#ffb3b3);
  color:#8b0000;
}

/* BUTTON */
.btn{
  display:block;
  margin-top:22px;
  padding:14px;
  border-radius:14px;
  text-align:center;
  font-weight:bold;
  text-decoration:none;
  color:white;
  transition:.3s;
}

.dashboard{ background:#7b0f0f; }
.dashboard:hover{ background:#5e0b0b; }

.daftar{ background:#0a6b1d; }
.daftar:hover{ background:#084f15; }

/* FOOTER */
.footer{
  background:#0c0c0c;
  text-align:center;
  padding:25px;
  font-size:14px;
  color:#aaa;
  border-top:1px solid rgba(255,255,255,.08);
}

/* 📱 MOBILE */
@media (max-width:768px){
  .container{
    grid-template-columns:1fr;
  }

  .header img{
    height:70px;
  }
}
</style>
</head>

<body>

<div class="header">
  <img src="assets/logo_ou.png" alt="Oriental University">
  <h1>ORIENTAL UNIVERSITY</h1>
  <p>Hasil Tes Penerimaan Mahasiswa Baru</p>
</div>

<!-- ⭐ WRAPPER PENTING -->
<main class="main-content">

  <div class="container">

    <!-- DATA PESERTA -->
    <div class="card">
      <h2>Data Peserta</h2>
      <div class="line"></div>

      <p><b>Nama</b><br><?= htmlspecialchars($user['nama']) ?></p>
      <p><b>Nomor Tes</b><br><?= htmlspecialchars($user['nomor_tes']) ?></p>
      <p><b>Email</b><br><?= htmlspecialchars($user['email']) ?></p>
    </div>

    <!-- HASIL -->
    <div class="card">
      <h2>Hasil Tes</h2>
      <div class="line"></div>

      <div class="badge <?= $status=="LULUS"?'lulus':'tidak' ?>">
        <?= $status ?>
      </div>

      <?php if ($status == "LULUS"): ?>
        <a href="daftar-ulang.php" class="btn daftar">LANJUT DAFTAR ULANG</a>
      <?php else: ?>
        <a href="dashboard.php" class="btn dashboard">KEMBALI KE DASHBOARD</a>
      <?php endif; ?>
    </div>

  </div>

</main>

<div class="footer">
  © <?= date('Y') ?> Oriental University • Admission System
</div>

</body>
</html>