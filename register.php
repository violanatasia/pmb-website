<?php
session_start();
include "config/db.php";

$success = false;

if (isset($_POST['daftar'])) {

  $nama  = htmlspecialchars($_POST['nama']);
  $email = htmlspecialchars($_POST['email']);
  $p1    = $_POST['password'];
  $p2    = $_POST['konfirmasi'];

  if ($p1 !== $p2) {
    $error = "Password dan konfirmasi tidak sama!";
  } else {

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
      $error = "Email sudah terdaftar!";
    } else {

      $password = password_hash($p1, PASSWORD_DEFAULT);

      $nomor_tes = "";

      mysqli_query($conn, "
        INSERT INTO users (nama,email,password,nomor_tes)
        VALUES ('$nama','$email','$password','$nomor_tes')
      ");

      $id_user = mysqli_insert_id($conn);

      $nomor_tes = "PMB-" . date("Y") . "-" . str_pad($id_user, 4, "0", STR_PAD_LEFT);

      mysqli_query($conn, "
        UPDATE users SET nomor_tes='$nomor_tes'
        WHERE id='$id_user'
      ");

      $_SESSION['nomor_tes'] = $nomor_tes;
      $_SESSION['nama'] = $nama;

      $success = true;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Registrasi PMB</title>

<style>
*{
  box-sizing:border-box;
  font-family:Arial, sans-serif;
}
body{
  margin:0;
  height:100vh;
}
.wrapper{
  display:flex;
  width:100%;
  height:100vh;
}
.left{
  width:50%;
  background:#5b0f14;
  display:flex;
  justify-content:center;
  align-items:center;
}
.right{
  width:50%;
  position:relative;
  background:url("assets/ou.jpg") center / cover no-repeat;
}
.right::before{
  content:"";
  position:absolute;
  inset:0;
  background:rgba(0,0,0,0.45);
}
.card{
  width:360px;
  background:rgba(255,255,255,0.25);
  padding:35px;
  border-radius:18px;
  backdrop-filter:blur(8px);
  color:white;
  position:relative;
  box-shadow:0 15px 35px rgba(0,0,0,.3);
}
.card h1{
  text-align:center;
  margin-top:45px;
  margin-bottom:25px;
  color:#5b0f14;
}
.logo-wrapper{
  width:100px;
  height:100px;
  background:#5b0f14;
  border-radius:50%;
  display:flex;
  justify-content:center;
  align-items:center;
  position:absolute;
  top:-40px;
  left:50%;
  transform:translateX(-50%);
}
.logo-wrapper img{
  width:140px;
  height:140px;
}
label{
  font-size:14px;
}
input{
  width:100%;
  padding:12px;
  margin-top:6px;
  margin-bottom:14px;
  border:none;
  border-radius:10px;
}
button{
  width:100%;
  padding:12px;
  background:#f4b400;
  border:none;
  border-radius:10px;
  font-weight:bold;
  cursor:pointer;
}
button:hover{
  background:#ffcc33;
}
.error{
  background:#ffcccc;
  color:#800000;
  padding:10px;
  border-radius:8px;
  margin-bottom:15px;
  font-size:14px;
  text-align:center;
}
.success{
  text-align:center;
}
.nomor{
  background:white;
  color:#5b0f14;
  font-size:22px;
  font-weight:bold;
  padding:12px;
  border-radius:12px;
  margin:15px 0;
}
.login{
  text-align:center;
  margin-top:15px;
}
.login a{
  color:#f4b400;
  text-decoration:none;
}

@media(max-width:768px){
  .wrapper{flex-direction:column;}
  .left,.right{width:100%;height:50vh;}
}
</style>
</head>

<body>

<div class="wrapper">
  <div class="left">
    <div class="card">

      <div class="logo-wrapper">
        <img src="assets/logo_ou.png">
      </div>

<?php if ($success): ?>

      <!-- ===== SUKSES ===== -->
      <div class="success">
        <h1>Registrasi Berhasil</h1>
        <p>Halo <b><?= $_SESSION['nama']; ?></b><br>Nomor Test Anda:</p>

        <div class="nomor">
          <?= $_SESSION['nomor_tes']; ?>
        </div>

        <p><b>Simpan nomor test ini</b><br>
        Digunakan untuk login ujian</p>

        <button onclick="location.href='login.php'">Login</button>
      </div>

<?php else: ?>

      <!-- ===== FORM ===== -->
      <h1>Registrasi</h1>

      <?php if (isset($error)) { ?>
        <div class="error"><?= $error ?></div>
      <?php } ?>

      <form method="POST">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" placeholder="Enter your full name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <label>Konfirmasi Password</label>
        <input type="password" name="konfirmasi" placeholder="Confirm your password" required>

        <button name="daftar">DAFTAR</button>
      </form>

      <div class="login">
        Sudah punya akun? <a href="login.php">Login</a>
      </div>

<?php endif; ?>

    </div>
  </div>
  <div class="right"></div>
</div>

</body>
</html>
