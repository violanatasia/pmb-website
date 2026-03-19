<?php
include "config/db.php";

if (isset($_POST['cek'])) {

  $email = htmlspecialchars($_POST['email']);

  $query = mysqli_query($conn, "SELECT nomor_tes FROM users WHERE email='$email'");

  if (mysqli_num_rows($query) == 1) {
    $data = mysqli_fetch_assoc($query);
    $nomor_tes = $data['nomor_tes'];
  } else {
    $error = "Email tidak terdaftar!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lupa Nomor Test</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

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
  box-shadow:0 8px 20px rgba(0,0,0,.3);
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
  background:#d4edda;
  color:#155724;
  padding:12px;
  border-radius:10px;
  text-align:center;
  font-size:14px;
  margin-bottom:15px;
}
.back{
  text-align:center;
  margin-top:15px;
  font-size:14px;
}
.back a{
  color:#ffd966;
  text-decoration:none;
}
@media(max-width:768px){
  .wrapper{
    flex-direction:column;
  }
  .left,.right{
    width:100%;
    height:50vh;
  }
}
</style>
</head>

<body>

<div class="wrapper">

  <div class="left">
    <div class="card">

      <div class="logo-wrapper">
        <img src="assets/logo_ou.png" alt="Logo ou">
      </div>

      <h1>Cek Nomor Test</h1>

      <?php if (isset($error)) { ?>
        <div class="error"><?= $error ?></div>
      <?php } ?>

      <?php if (isset($nomor_tes)) { ?>
        <div class="success">
          Nomor test kamu adalah:<br>
          <strong><?= $nomor_tes ?></strong>
        </div>
      <?php } ?>

      <form method="POST">
        <label>Email</label>
        <input type="email" name="email"
               placeholder="Masukkan email saat daftar"
               required>

        <button name="cek">CEK NOMOR TEST</button>
      </form>

      <div class="back">
        Kembali Ke <a href="login.php">Login</a>
      </div>

    </div>
  </div>

  <div class="right"></div>
</div>

</body>
</html>
