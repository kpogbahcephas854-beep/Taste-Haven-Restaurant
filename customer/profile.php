<?php
session_start();

include("../config/db.php");
include("../config/config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$message="";
$error="";

$user=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$user_id'
"));

if(isset($_POST['update_profile']))
{

    $fullname=mysqli_real_escape_string($conn,$_POST['fullname']);

    $phone=mysqli_real_escape_string($conn,$_POST['phone']);

    $email=mysqli_real_escape_string($conn,$_POST['email']);

    $address=mysqli_real_escape_string($conn,$_POST['address']);

    $check=mysqli_query($conn,"
    SELECT id
    FROM users
    WHERE
    (email='$email' OR phone='$phone')
    AND id!='$user_id'
    ");

    if(mysqli_num_rows($check)>0)
    {

        $error="Email or phone number already exists.";

    }
    else
    {

        mysqli_query($conn,"
        UPDATE users SET

        fullname='$fullname',

        phone='$phone',

        email='$email',

        address='$address'

        WHERE id='$user_id'
        ");

        $_SESSION['fullname']=$fullname;
        $_SESSION['email']=$email;
        $_SESSION['phone']=$phone;

        $message="Profile updated successfully.";

        $user=mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT *
        FROM users
        WHERE id='$user_id'
        "));

    }

}

if(isset($_POST['change_password']))
{

    $current=$_POST['current_password'];

    $new=$_POST['new_password'];

    $confirm=$_POST['confirm_password'];

    if(!password_verify($current,$user['password']))
    {

        $error="Current password is incorrect.";

    }
    elseif($new!=$confirm)
    {

        $error="New passwords do not match.";

    }
    else
    {

        $hash=password_hash($new,PASSWORD_DEFAULT);

        mysqli_query($conn,"
        UPDATE users
        SET password='$hash'
        WHERE id='$user_id'
        ");

        $message="Password changed successfully.";

    }

}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">

<title>My Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{

background:#f5f6fa;

}

.card{

border:none;

border-radius:15px;

box-shadow:0 10px 30px rgba(0,0,0,.1);

}

</style>

</head>

<body>

<div class="container mt-5 mb-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card">

<div class="card-header bg-dark text-white">

<h3>

<i class="fas fa-user"></i>

My Profile

</h3>

</div>

<div class="card-body">

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
value="<?php echo $user['fullname']; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Phone Number</label>

<input
type="text"
name="phone"
class="form-control"
value="<?php echo $user['phone']; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?php echo $user['email']; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Address</label>

<textarea
name="address"
class="form-control"
rows="2"
required><?php echo $user['address']; ?></textarea>
</div>

</div>

<div class="d-grid mt-3 mb-4">

<button

type="submit"

name="update_profile"

class="btn btn-warning btn-lg">

<i class="fas fa-save"></i>

Update Profile

</button>

</div>

</form>

<hr>

<h4 class="mb-4">

<i class="fas fa-lock"></i>

Change Password

</h4>

<form method="POST">

<div class="mb-3">

<label>

Current Password

</label>

<input

type="password"

name="current_password"

class="form-control"

required>

</div>

<div class="mb-3">

<label>

New Password

</label>

<input

type="password"

name="new_password"

class="form-control"

required>

</div>

<div class="mb-3">

<label>

Confirm New Password

</label>

<input

type="password"

name="confirm_password"

class="form-control"

required>

</div>

<div class="d-grid">

<button

type="submit"

name="change_password"

class="btn btn-dark btn-lg">

<i class="fas fa-key"></i>

Change Password

</button>

</div>

</form>

<hr>

<div class="text-center mt-4">

<a

href="dashboard.php"

class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>

Back To Dashboard

</a>

<a

href="../menu.php"

class="btn btn-warning">

<i class="fas fa-utensils"></i>

Order Food

</a>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>