<?php
session_start();
include("../config/db.php");

if(isset($_POST['login']))
{
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0)
    {
        $_SESSION['admin']=$username;
        header("Location: dashboard.php");
        exit();
    }
    else
    {
        $error="Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Administrator Login | Taste Haven</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

font-family:'Segoe UI',sans-serif;

background:
linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)),
url('../assets/images/menu-banner.png');

background-size:cover;
background-position:center;
background-repeat:no-repeat;
background-attachment:fixed;

min-height:100vh;

display:flex;
align-items:center;
justify-content:center;

padding:30px;

}

.login-card{

width:100%;
max-width:500px;

background:rgba(255,255,255,.96);

border-radius:25px;

padding:45px;

box-shadow:0 25px 60px rgba(0,0,0,.35);

backdrop-filter:blur(8px);

}

.logo{

text-align:center;
margin-bottom:30px;

}

.logo img{

width:90px;
height:90px;

border-radius:50%;

background:#fff;

padding:5px;

border:4px solid #ffc107;

}

.logo h2{

font-weight:700;

margin-top:15px;

}

.logo p{

color:#666;

}

.form-control{

height:55px;

border-radius:12px;

}

.form-control:focus{

border-color:#ffc107;

box-shadow:none;

}

.password-box{

position:relative;

}

.password-box i{

position:absolute;

right:18px;

top:18px;

cursor:pointer;

color:#777;

}

.btn-login{

background:#ffc107;

border:none;

height:55px;

font-size:18px;

font-weight:bold;

border-radius:12px;

transition:.3s;

}

.btn-login:hover{

background:#e0a800;

transform:translateY(-2px);

}

.footer{

text-align:center;

margin-top:25px;

font-size:14px;

color:#777;

}

</style>

</head>
<body>

<div class="login-card">

<div class="logo">

<img src="../assets/images/logo.png" alt="Taste Haven Logo">

<h2>Taste Haven</h2>

<p>Administrator Login</p>

</div>

<?php
if(isset($error))
{
?>
<div class="alert alert-danger">

<i class="fas fa-circle-exclamation"></i>

<?php echo $error; ?>

</div>
<?php
}
?>

<form method="POST">

<div class="mb-3">

<label class="form-label fw-semibold">

<i class="fas fa-user-shield text-warning"></i>

Username

</label>

<input
type="text"
name="username"
class="form-control"
placeholder="Enter administrator username"
required>

</div>

<div class="mb-3">

<label class="form-label fw-semibold">

<i class="fas fa-lock text-warning"></i>

Password

</label>

<div class="password-box">

<input
type="password"
name="password"
id="password"
class="form-control"
placeholder="Enter your password"
required>

<i
class="fas fa-eye"
id="togglePassword"
onclick="togglePassword()"></i>

</div>

</div>

<div class="d-flex justify-content-between align-items-center mb-4">

<div class="form-check">

<input
class="form-check-input"
type="checkbox"
id="remember">

<label
class="form-check-label"
for="remember">

Remember Me

</label>

</div>

<a
href="../index.php"
class="text-decoration-none text-warning fw-bold">

Back to Website

</a>

</div>

<div class="d-grid">

<button
type="submit"
name="login"
class="btn btn-login">

<i class="fas fa-right-to-bracket"></i>

Administrator Login

</button>

</div>

</form>

<hr class="my-4">

<div class="footer">

<i class="fas fa-shield-halved text-warning"></i>

Secure Administrator Access

<br><br>

© <?php echo date("Y"); ?>

Taste Haven Restaurant

<br>

All Rights Reserved.

</div>

</div>
<script>

function togglePassword()
{

const password=document.getElementById("password");

const icon=document.getElementById("togglePassword");

if(password.type==="password")
{

password.type="text";

icon.classList.remove("fa-eye");

icon.classList.add("fa-eye-slash");

}
else
{

password.type="password";

icon.classList.remove("fa-eye-slash");

icon.classList.add("fa-eye");

}

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>