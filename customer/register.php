<?php
session_start();
include("../config/db.php");
include("../config/config.php");

$message = "";
$error = "";

if(isset($_POST['register']))
{

   $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
$address = mysqli_real_escape_string($conn,$_POST['address']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password != $confirm_password)
    {
        $error = "Passwords do not match.";
    }
    else
    {

        $check = mysqli_query($conn,"
        SELECT id
        FROM users
        WHERE email='$email'
        OR phone='$phone'
        ");

        if(mysqli_num_rows($check)>0)
        {
            $error = "Email or phone number already exists.";
        }
        else
        {

            $password = password_hash($password,PASSWORD_DEFAULT);

            mysqli_query($conn,"
            INSERT INTO users
(
fullname,
phone,
email,
address,
password
)

VALUES
(
'$fullname',
'$phone',
'$email',
'$address',
'$password'
)
            ");

            $message = "Registration successful. You can now login.";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Customer Registration</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{

background:url('../assets/images/menu-banner.png') center center/cover no-repeat fixed;

min-height:100vh;

display:flex;

align-items:center;

justify-content:center;

}

.overlay{

position:fixed;

top:0;

left:0;

width:100%;

height:100%;

background:rgba(0,0,0,.65);

z-index:-1;

}

.register-card{

background:#fff;

border-radius:20px;

padding:40px;

box-shadow:0 20px 50px rgba(0,0,0,.35);

width:100%;

max-width:600px;

}

.logo{

text-align:center;

margin-bottom:20px;

}

.logo img{

width:90px;

height:90px;

object-fit:cover;

border-radius:50%;

}

.btn-register{

background:#ffc107;

border:none;

font-weight:bold;

padding:12px;

}

.btn-register:hover{

background:#e0a800;

}

</style>

</head>

<body>

<div class="overlay"></div>

<div class="register-card">

<div class="logo">

<img src="../assets/images/logo.png">

<h2 class="mt-3">

Taste Haven Restaurant

</h2>

<p class="text-muted">

Create your customer account

</p>

</div>

<?php if($message!=""){ ?>

<div class="alert alert-success">

<?php echo $message; ?>

</div>

<?php } ?>

<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Full Name</label>

<input
type="text"
name="fullname"
class="form-control"
placeholder="Enter your full name"
required>

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Phone Number</label>

<input
type="text"
name="phone"
class="form-control"
required>
<div class="col-md-12 mb-3">

<label>

Delivery Address

</label>

<textarea

name="address"

class="form-control"

rows="3"

placeholder="Enter your delivery address"

required></textarea>

</div>

<div class="col-md-6 mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">

<label>

Confirm Password

</label>

<input

type="password"

name="confirm_password"

class="form-control"

required>

</div>

</div>

<div class="d-grid mt-3">

<button

type="submit"

name="register"

class="btn btn-register btn-lg">

<i class="fas fa-user-plus"></i>

Create Account

</button>

</div>

<div class="text-center mt-4">

<p>

Already have an account?

<a

href="login.php"

class="text-decoration-none fw-bold text-warning">

Login Here

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>