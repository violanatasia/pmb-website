<?php
session_start();
include "../config/db.php";

if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = md5($_POST['password']);

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

<style>
*{
  box-sizing:border-box;
  font-family:'Poppins', Arial, sans-serif;
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
  background:url("../assets/ou.jpg") center / cover no-repeat;
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
  display: block;
  margin-bottom: 5px;
}
input{
  width:100%;
  padding:12px;
  margin-top:6px;
  margin-bottom:14px;
  border:none;
  border-radius:10px;
  font-size:14px;
}
.password-box{
  position:relative;
}
.password-box input{
  padding-right:48px;
}
.eye{
  position:absolute;
  right:10px;
  top:50%;
  transform:translateY(-50%);
  width:20px;
  height:20px;
  cursor:pointer;
  background:url("../assets/eye.svg") center / contain no-repeat;
  opacity:.6;
}
.eye.active{
  background:url("../assets/eye-off.svg") center / contain no-repeat;
}
button{
  width:100%;
  padding:12px;
  background:#f4b400;
  border:none;
  border-radius:10px;
  font-weight:bold;
  cursor:pointer;
  font-size:16px;
  margin-top:10px;
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
.back-link{
  text-align:center;
  margin-top:15px;
  font-size:13px;
}
.back-link a{
  color:#ffd966;
  text-decoration:none;
}
.back-link a:hover{
  text-decoration:underline;
}
.badge-admin{
  display: inline-block;
  background: rgba(255,255,255,0.2);
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  margin-bottom: 10px;
  text-align: center;
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
        <img src="../assets/logo_ou.png" alt="Logo">
      </div>

      <h1>Admin Login</h1>
      
      <div style="text-align: center;">
        <span class="badge-admin">🔐 ADMINISTRATOR</span>
      </div>

      <?php if (isset($error)) { ?>
        <div class="error"><?= $error ?></div>
      <?php } ?>

      <form method="POST">

        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" required autofocus>

        <label>Password</label>
        <div class="password-box">
          <input type="password" name="password" id="password"
                 placeholder="Masukkan password" required>
          <span class="eye"
                onclick="togglePasswordIcon(this,'password')"></span>
        </div>

        <button name="login">LOGIN</button>
      </form>

      <div class="back-link">
        <a href="../index.php">← Kembali ke Beranda</a>
      </div>

    </div>
  </div>

  <div class="right"></div>
</div>

<script>
function togglePasswordIcon(el, id){
  const input = document.getElementById(id);
  if(input.type === "password"){
    input.type = "text";
    el.classList.add("active");
  }else{
    input.type = "password";
    el.classList.remove("active");
  }
}
</script>

</body>
</html>