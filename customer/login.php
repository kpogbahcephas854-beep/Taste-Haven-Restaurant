<?php
session_start();

include("../config/db.php");
include("../config/config.php");

$error = "";

if(isset($_POST['login']))
{

    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn,"
    SELECT *
    FROM users
    WHERE email='$email'
    ");

    if(mysqli_num_rows($query)>0)
    {

        $user = mysqli_fetch_assoc($query);

        if(password_verify($password,$user['password']))
        {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone'] = $user['phone'];

            header("Location: dashboard.php");
            exit();

        }
        else
        {

            $error = "Incorrect password.";

        }

    }
    else
    {

        $error = "Account not found.";

    }

}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Customer Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{

background:url('../assets/images/menu-banner.png');

background-repeat:no-repeat;

background-position:center top;

background-size:cover;

background-attachment:fixed;

min-height:100vh;

display:flex;

justify-content:center;

align-items:center;

padding:40px 15px;

}

.overlay{

position:fixed;

top:0;

left:0;

width:100%;

height:100%;

background:rgba(0,0,0,.35);

z-index:-1;

}

.login-card{

background:rgba(255,255,255,.97);

padding:45px;

border-radius:25px;

box-shadow:0 25px 60px rgba(0,0,0,.25);

width:100%;

max-width:500px;

border:1px solid rgba(255,193,7,.30);

backdrop-filter:blur(8px);

-webkit-backdrop-filter:blur(8px);

}
.logo{

text-align:center;

margin-bottom:20px;

}

.logo img{

width:90px;

height:90px;

border-radius:50%;

object-fit:cover;

}

.btn-login{

background:#ffc107;

border:none;

font-weight:bold;

padding:12px;

}

.btn-login:hover{

background:#e0a800;

}

</style>

</head>

<body>

<div class="overlay"></div>

<div class="login-card">

<div class="logo">

<img src="../assets/images/logo.png">

<h2 class="mt-3">

Taste Haven Restaurant

</h2>

<p class="text-muted">

Customer Login

</p>

</div>

<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label>Email Address</label>

<input

type="email"

name="email"

class="form-control"

required>

</div>

<div class="mb-3">

<label>Password</label>

<input

type="password"

name="password"

id="password"

class="form-control"

required>
</div>

<div class="form-check mb-3">

<input

class="form-check-input"

type="checkbox"

id="showPassword"

onclick="togglePassword()">

<label class="form-check-label" for="showPassword">

Show Password

</label>

</div>

<div class="d-grid">

<button

type="submit"

name="login"

class="btn btn-login btn-lg">

<i class="fas fa-sign-in-alt"></i>

Login

</button>

</div>

<div class="text-center mt-4">

<p>

Don't have an account?

<a

href="register.php"

class="text-decoration-none fw-bold text-warning">

Register Here

</a>

</p>

<p>

<a

href="../index.php"

class="btn btn-outline-dark">

<i class="fas fa-home"></i>

Back To Home

</a>

</p>

</div>

<hr>

<div class="text-center text-muted">

<small>

&copy; <?php echo date("Y"); ?>

Taste Haven Restaurant

All Rights Reserved.

</small>

</div>

</form>

</div>

<script>

function togglePassword()
{
    var password = document.getElementById("password");

    if(password.type === "password")
    {
        password.type = "text";
    }
    else
    {
        password.type = "password";
    }
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>