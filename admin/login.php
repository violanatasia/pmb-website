<?php
session_start();
include "../config/db.php";

if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = md5($_POST['password']); // SESUAI DB (MD5)

  $query = mysqli_query($conn, "
    SELECT * FROM admin 
    WHERE username='$username' 
    AND password='$password'
  ");

  $data = mysqli_fetch_assoc($query);

  if ($data) {

    session_regenerate_id(true);

    $_SESSION['admin'] = true;
    $_SESSION['admin_id'] = $data['id'];
    $_SESSION['username'] = $data['username'];

    header("Location: dashboard.php");
    exit;

  } else {
    $error = "Username atau Password salah!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Admin - Oriental University</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

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
  background:url('../assets/bg_class.jpg') center/cover no-repeat fixed;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ================= OVERLAY ================= */
.overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.55);
  display:flex;
  justify-content:center;
  align-items:center;
  padding:20px;
}

/* ================= CARD ================= */
.card{
  position:relative;
  background:rgba(255,255,255,.97);
  padding:85px 42px 42px;
  border-radius:26px;
  width:100%;
  max-width:420px;
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
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}

.greeting::before,
.greeting::after {
  content: "•";
  color: #7b0f0f;
  opacity: 0.5;
}

.card h2{
  font-family:'Playfair Display', serif;
  color:#7b0f0f;
  margin:0 0 20px;
  font-size:26px;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
}

/* ================= BADGE ADMIN ================= */
.admin-badge {
  display: inline-block;
  background: #7b0f0f;
  color: white;
  font-size: 10px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 20px;
  letter-spacing: 0.5px;
  margin-bottom: 10px;
  text-transform: uppercase;
}

/* ================= FORM GROUP ================= */
.form-group{
  text-align:left;
  margin-bottom:20px;
}

.form-group label{
  font-size:12.5px;
  font-weight:600;
  color:#555;
  display:block;
  margin-bottom:5px;
}

.form-group input{
  width:100%;
  padding:12px 14px;
  border-radius:13px;
  border:1px solid #e6e6e6;
  background:#f8f8f8;
  font-size:14px;
  transition: all 0.2s;
  font-family:'Poppins', sans-serif;
}

.form-group input:focus{
  outline:none;
  border-color:#7b0f0f;
  background:#fff;
  box-shadow: 0 0 0 3px rgba(123,15,15,0.1);
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
  font-size:15px;
  background:#7b0f0f;
  color:white;
  position: relative;
  overflow: hidden;
}

.btn:hover{
  background:#5e0b0b;
  transform:translateY(-1px);
  box-shadow:0 4px 12px rgba(123,15,15,.3);
}

.btn:active {
  transform: translateY(0);
}

/* ================= ERROR ================= */
.error{
  background:#ffe9e9;
  color:#b71c1c;
  padding:12px 16px;
  border-radius:12px;
  margin-bottom:20px;
  font-size:14px;
  text-align:center;
  border-left:4px solid #b71c1c;
  display: flex;
  align-items: center;
  gap: 8px;
}

.error::before {
  content: "!";
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 20px;
  height: 20px;
  background: #b71c1c;
  color: white;
  border-radius: 50%;
  font-weight: 600;
  font-size: 14px;
  flex-shrink: 0;
}

/* ================= FOOTER LINK ================= */
.footer-link{
  margin-top:25px;
  font-size:13px;
  color:#888;
  padding-top:15px;
  border-top: 1px solid #eee;
}

.footer-link a{
  color:#7b0f0f;
  text-decoration:none;
  font-weight:500;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  transition: 0.2s;
}

.footer-link a:hover{
  color: #5e0b0b;
  gap: 8px;
}

/* ================= INPUT ICON (OPSIONAL) ================= */
/* Kalau mau tambah icon di input, uncomment ini */
/*
.input-wrapper {
  position: relative;
}

.input-wrapper input {
  padding-left: 40px;
}

.input-wrapper i {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #7b0f0f;
  opacity: 0.6;
  font-size: 16px;
}
*/

/* mobile */
@media (max-width:500px){
  .card{
    padding:80px 24px 30px;
  }
  
  .card h2 {
    font-size: 24px;
  }
}
</style>
</head>

<body>

<div class="overlay">
  <div class="card">

    <div class="card-logo">
      <img src="../assets/logo_ou.png" alt="Logo OU">
    </div>

    <!-- Badge Admin -->
    <div class="admin-badge">ADMINISTRATOR</div>

    <div class="greeting">Welcome Back</div>
    <h2>Login Admin</h2>

    <?php if(isset($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="form-group">
        <label>Username</label>
        <!-- Kalau mau pake icon, ganti jadi ini:
        <div class="input-wrapper">
          <i class="fas fa-user"></i>
          <input type="text" name="username" placeholder="Masukkan username" required autofocus>
        </div>
        -->
        <input type="text" name="username" placeholder="Masukkan username" required autofocus>
      </div>

      <div class="form-group">
        <label>Password</label>
        <!-- Kalau mau pake icon, ganti jadi ini:
        <div class="input-wrapper">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" placeholder="Masukkan password" required>
        </div>
        -->
        <input type="password" name="password" placeholder="Masukkan password" required>
      </div>

      <button name="login" class="btn">LOGIN</button>
    </form>

    <div class="footer-link">
      <a href="../index.php">Kembali ke Beranda</a>
    </div>
    
    <!-- Tahun (optional) -->
    <div style="font-size: 11px; color: #ccc; margin-top: 10px;">
      © <?= date('Y') ?> Oriental University
    </div>

  </div>
</div>
</body>
</html>