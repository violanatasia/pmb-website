<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['id'];

/* CEK STATUS TES */
$cek = mysqli_query($conn, "SELECT status_tes FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($cek);

if ($user && $user['status_tes'] == 'sudah') {
  header("Location: dashboard.php");
  exit;
}

/* AMBIL SEMUA SOAL */
$qSoal = mysqli_query($conn, "SELECT * FROM soal ORDER BY id ASC");

/* SUBMIT JAWABAN */
if (isset($_POST['submit_test'])) {

  foreach ($_POST['jawaban'] as $soal_id => $jawaban) {

    $cekJawaban = mysqli_query($conn, "
      SELECT id FROM jawaban 
      WHERE user_id='$user_id' AND soal_id='$soal_id'
    ");

    if (mysqli_num_rows($cekJawaban) > 0) {
      mysqli_query($conn, "
        UPDATE jawaban 
        SET jawaban='$jawaban'
        WHERE user_id='$user_id' AND soal_id='$soal_id'
      ");
    } else {
      mysqli_query($conn, "
        INSERT INTO jawaban (user_id, soal_id, jawaban)
        VALUES ('$user_id','$soal_id','$jawaban')
      ");
    }
  }

  /* UPDATE STATUS TES */
  mysqli_query($conn, "
    UPDATE users SET status_tes='sudah'
    WHERE id='$user_id'
  ");

  header("Location: hasil-tes.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tes Online PMB</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{box-sizing:border-box}
body{
  margin:0;
  font-family:'Poppins', sans-serif;
  background:#f3f4f6;
}

/* LAYOUT */
.container{
  display:flex;
  min-height:100vh;
}

/* ================= SIDEBAR ================= */
.sidebar{
  width:300px;
  min-width:300px;
  height:100vh;
  background:linear-gradient(180deg,#7b0f0f 0%, #5e0b0b 100%);
  color:white;
  position:sticky;
  top:0;

  display:flex;
  align-items:center;
  justify-content:center;
}

/* center wrapper — PERFECT CENTER */
.sidebar-center{
  width:100%;
  max-width:240px;
  text-align:center;

  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  gap:26px; /* ⭐ jarak rapi */
}

/* ================= LOGO ================= */

.logo-wrap img{
  width:180px;
  height:auto;
  filter:drop-shadow(0 6px 18px rgba(0,0,0,.35));
}

/* ================= TITLE ================= */
.title-wrap{
  line-height:1.35;
  margin-top: -35px; /* naikkan tulisan */
}

.title-small{
  margin:0;
  font-size:15px;
  font-weight:400;
  opacity:.9;
  letter-spacing:.3px;
}

.title-big{
  margin:4px 0 0;
  font-size:19px;
  font-weight:700;
  letter-spacing:1.2px;
}

/* ================= TIMER ================= */
.timer-wrap{
  margin-top:6px;
}

.timer{
  background:#f5b400;
  color:#000;
  padding:14px 28px;
  border-radius:14px;
  font-weight:800;
  font-size:22px;
  letter-spacing:2px;
  box-shadow:
    0 10px 25px rgba(0,0,0,.25),
    inset 0 -3px 0 rgba(0,0,0,.15);
}

/* CONTENT */
.content{
  flex:1;
  padding:40px;
}

/* CARD SOAL */
.soal-card{
  background:white;
  padding:28px;
  border-radius:18px;
  margin-bottom:22px;
  box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.soal-card h3{
  margin-top:0;
  color:#7b0f0f;
}

/* OPSI */
.opsi label{
  display:block;
  border:2px solid #7b0f0f;
  border-radius:12px;
  padding:12px;
  margin-bottom:10px;
  cursor:pointer;
  transition:.2s;
}

.opsi input{display:none}

.opsi input:checked + label{
  background:#7b0f0f;
  color:white;
}

/* SUBMIT */
.submit-box{
  text-align:center;
  margin:40px 0;
}

.submit-btn{
  background:#2ecc71;
  color:white;
  border:none;
  padding:16px 40px;
  font-size:18px;
  border-radius:30px;
  font-weight:700;
  cursor:pointer;
}

.submit-btn:hover{
  background:#27ae60;
}

/* MOBILE */
@media(max-width:900px){
  .sidebar{display:none}
  .content{padding:20px}
}
</style>
</head>

<body>

<div class="container">

<div class="sidebar">
  <div class="sidebar-center">

    <div class="logo-wrap">
      <img src="assets/logo_ou.png" alt="Logo OU">
    </div>

    <div class="title-wrap">
      <p class="title-small">Test Penerimaan Mahasiswa Baru</p>
      <h1 class="title-big">ORIENTAL UNIVERSITY</h1>
    </div>

    <div class="timer-wrap">
      <div class="timer" id="timer">01 : 00 : 00</div>
    </div>

  </div>
</div>

  <!-- CONTENT -->
  <div class="content">
    <form method="post">

      <?php
      $no = 1;
      while($soal = mysqli_fetch_assoc($qSoal)):
      ?>

      <div class="soal-card">
        <h3>Soal <?= $no ?></h3>
        <p><?= $soal['pertanyaan'] ?></p>

        <div class="opsi">
          <?php foreach(['A','B','C','D'] as $o): ?>
            <input type="radio"
                   name="jawaban[<?= $soal['id'] ?>]"
                   value="<?= $o ?>"
                   id="<?= $soal['id'].$o ?>">

            <label for="<?= $soal['id'].$o ?>">
              <?= $o ?>. <?= $soal['opsi_'.strtolower($o)] ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <?php $no++; endwhile; ?>

      <div class="submit-box">
        <button class="submit-btn" name="submit_test">
          SELESAI & KUMPULKAN
        </button>
      </div>

    </form>
  </div>

</div>

<script>
let t = 3600;

setInterval(()=>{
  let h=Math.floor(t/3600),
      m=Math.floor((t%3600)/60),
      s=t%60;

  timer.innerHTML=
    `${String(h).padStart(2,'0')} : ${String(m).padStart(2,'0')} : ${String(s).padStart(2,'0')}`;

  if(t>0)t--;
  else document.querySelector("form").submit();
},1000);
</script>

</body>
</html>