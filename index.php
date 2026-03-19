<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>PMB Oriental University 2026</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box}
body{
  margin:0;
  font-family:'Poppins', sans-serif;
  background:#111;
  color:white;
}

/* HEADER */
header{
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
  gap:12px; /* lebih rapi dari margin manual */
}

.header img{
  width:75px;
  height:auto;
}

.logo-text{
  display:flex;
  flex-direction:column;
  line-height:1.2;
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

/* tombol admin */
.admin-btn{
  background:#fff;
  color:#7b0f0f;
  padding:8px 18px;
  border-radius:10px;
  font-size:14px;
  font-weight:600;
  text-decoration:none;
  transition:all .25s ease;
  white-space:nowrap;
}

.admin-btn:hover{
  background:#f3f3f3;
  transform:translateY(-1px);
  box-shadow:0 4px 12px rgba(0,0,0,.15);
}

/* HERO */
.hero{
  background:
    linear-gradient(to right, rgba(0,0,0,.75), rgba(0,0,0,.4)),
    url('assets/bg_ou.jpg') center/cover;
  display:flex;
  flex-direction:column;
  justify-content:center;
  height:100vh;
  padding:120px 80px;
  margin-top:70px;
}

.hero h1{
  font-family:'Playfair Display', serif;
  font-size:48px;
  margin-bottom:10px;
}

.hero p{
  font-size:18px;
  max-width:620px;
  line-height:1.6;
}

.btn-group{margin-top:35px}

.btn{
  display:inline-block;
  padding:14px 30px;
  border-radius:30px;
  font-weight:600;
  text-decoration:none;
  transition:.3s;
}

.btn-register{
  background:#f5b400;
  color:black;
}

.btn-register:hover{
  background:#ffd24d;
}

.btn-login{
  border:2px solid #f5b400;
  color:#f5b400;
  margin-left:15px;
}

.btn-login:hover{
  background:#f5b400;
  color:black;
}

/* SECTION LIGHT */
.section-light{
  padding:80px 80px;
  background:#fafafa;
  color:#222;
}

.section-light h2{
  text-align:center;
  font-family:'Playfair Display', serif;
  font-size:36px;
  margin-bottom:55px;
  color:#7b0f0f;
}

/* SECTION DARK (JADWAL) */
.section-dark{
  padding:80px 80px;
  background:linear-gradient(135deg,#7b0f0f,#5e0b0b);
}

.section-dark h2{
  text-align:center;
  font-family:'Playfair Display', serif;
  font-size:36px;
  margin-bottom:55px;
  color:white;
}

/* GARIS BAWAH JUDUL */
.section-light h2::after,
.section-dark h2::after{
  content:"";
  display:block;
  width:80px;
  height:4px;
  background:#f5b400;
  margin:18px auto 0;
  border-radius:2px;
}

/* ALUR & KEUNGGULAN GRID */
.alur{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:25px;
}

.box{
  background:white;
  padding:35px 30px;
  border-radius:22px;
  text-align:center;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  transition:.35s;
  border-top:4px solid #7b0f0f;
}

.box:hover{
  transform:translateY(-8px);
  box-shadow:0 18px 40px rgba(0,0,0,.15);
}

.box span{
  font-size:42px;
}

.box h4{
  margin:18px 0 10px;
  color:#7b0f0f;
  font-weight:600;
}

.box p{
  font-size:14px;
  line-height:1.7;
  color:#555;
  text-align:justify;
}

/* JADWAL */
.jadwal{
  max-width:700px;
  margin:0 auto;
  display:flex;
  flex-direction:column;
  gap:20px;
}

.jadwal-item{
  background:rgba(255,255,255,.05);
  border-left:5px solid #f5b400;
  padding:22px 28px;
  border-radius:16px;
  transition:.3s;
  backdrop-filter:blur(6px);
}

.jadwal-item h3{
  margin:0 0 6px;
  font-size:20px;
  color:white;
}

.jadwal-item p{
  margin-top:8px;
  font-size:14px;
  color:#eee;
}

.jadwal-item:hover{
  background:rgba(255,255,255,.1);
  transform:translateX(6px);
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

<header class="header">
  <div class="header-left">
    <img src="assets/logo_ou.png" alt="Logo OU">
    <div class="logo-text">
      <span class="small-text">Penerimaan Mahasiswa Baru</span>
      <span class="big-text">ORIENTAL UNIVERSITY</span>
    </div>
  </div>

  <a href="admin/login.php" class="admin-btn">Admin Login</a>
</header>

<!-- HERO -->
<div class="hero">
  <h1>Penerimaan Mahasiswa Baru<br>Oriental University 2026</h1>
  <p>
    Website resmi Penerimaan Mahasiswa Baru Oriental University.
    Daftarkan diri Anda sekarang dan ikuti seleksi secara online
    dengan sistem yang modern dan transparan.
  </p>

  <div class="btn-group">
    <a href="register.php" class="btn btn-register">Daftar Sekarang</a>
    <a href="login.php" class="btn btn-login">Login</a>
  </div>
</div>

<!-- ALUR -->
<div class="section-light">
  <h2>Alur Penerimaan Mahasiswa Baru</h2>
  <div class="alur">
    <div class="box">
      <span>📝</span>
      <h4>Registrasi</h4>
      <p>Buat akun PMB secara online melalui website resmi.</p>
    </div>
    <div class="box">
      <span>🧠</span>
      <h4>Tes Online</h4>
      <p>Kerjakan tes PMB secara daring dengan sistem terintegrasi.</p>
    </div>
    <div class="box">
      <span>📊</span>
      <h4>Pengumuman</h4>
      <p>Cek hasil seleksi langsung melalui dashboard peserta.</p>
    </div>
    <div class="box">
      <span>🎓</span>
      <h4>Daftar Ulang</h4>
      <p>Mahasiswa lulus melakukan daftar ulang dan mendapatkan NIM resmi.</p>
    </div>
  </div>
</div>

<!-- JADWAL -->
<section class="section-dark">
  <h2>Program Studi Oriental University</h2>
  <div class="jadwal">
    <div class="jadwal-item">
      <h3>Computer Science</h3>
      <p>Mempelajari pengembangan perangkat lunak, AI, dan teknologi komputasi modern.</p>
    </div>
    <div class="jadwal-item">
      <h3>Information Systems</h3>
      <p>Fokus pada pengelolaan sistem informasi untuk mendukung kebutuhan bisnis.</p>
    </div>
    <div class="jadwal-item">
      <h3>International Relations</h3>
      <p>Mempelajari hubungan antar negara, diplomasi, dan isu global.</p>
    </div>
    <div class="jadwal-item">
      <h3>Business Administration</h3>
      <p>Mengembangkan kemampuan manajemen, kepemimpinan, dan strategi bisnis.</p>
    </div>
    <div class="jadwal-item">
      <h3>Economics</h3>
      <p>Mempelajari analisis ekonomi, kebijakan publik, dan dinamika pasar.</p>
    </div>
  </div>
</section>

<!-- KEUNGGULAN (SAMA WARNA DENGAN ALUR) -->
<div class="section-light">
  <h2>Keunggulan Oriental University</h2>
  <div class="alur">
    <div class="box">
      <h4>Global Academic Excellence</h4>
      <p>Oriental University mengedepankan standar pendidikan internasional dengan kurikulum berbasis riset dan teknologi modern. Mahasiswa dibimbing oleh tenaga pengajar profesional dan berpengalaman di bidangnya.</p>
    </div>
    <div class="box">
      <h4>Innovation & Technology Driven</h4>
      <p>Didukung sistem pembelajaran digital dan fasilitas teknologi terkini, Oriental University mempersiapkan mahasiswa untuk menghadapi era industri 5.0 dengan kompetensi global dan adaptif.</p>
    </div>
    <div class="box">
      <h4>Character & Leadership Development</h4>
      <p>Tidak hanya fokus pada akademik, Oriental University membentuk karakter, kepemimpinan, dan integritas mahasiswa agar siap menjadi pemimpin masa depan yang beretika dan berdaya saing tinggi.</p>
    </div>
  </div>
</div>

<div class="footer">
  © <?= date('Y') ?> Oriental University • Admission System
</div>

</body>
</html>
