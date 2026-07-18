<?php
include("includes/auth.php");
include("../config/db.php");

$message="";

if(isset($_POST['save']))
{

    $restaurant_name=mysqli_real_escape_string($conn,$_POST['restaurant_name']);

    $phone=mysqli_real_escape_string($conn,$_POST['phone']);

    $mtn_number=mysqli_real_escape_string($conn,$_POST['mtn_number']);

    $airtel_number=mysqli_real_escape_string($conn,$_POST['airtel_number']);

    $email=mysqli_real_escape_string($conn,$_POST['email']);

    $address=mysqli_real_escape_string($conn,$_POST['address']);

    mysqli_query($conn,"
    UPDATE settings SET

    restaurant_name='$restaurant_name',

    phone='$phone',

    mtn_number='$mtn_number',

    airtel_number='$airtel_number',

    email='$email',

    address='$address'

    WHERE id=1
    ");

    $message="Settings updated successfully.";

}

$settings=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM settings
WHERE id=1
"));

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

Restaurant Settings

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../assets/css/admin.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="wrapper">

<?php include("includes/sidebar.php"); ?>

<div class="main">

<?php include("includes/header.php"); ?>

<div class="container-fluid p-4">

<div class="row">

<div class="col-lg-10 mx-auto">

<div class="card shadow">

<div class="card-header bg-dark text-white">

<h3>

<i class="fas fa-cogs"></i>

Restaurant Settings

</h3>

</div>

<div class="card-body">

<?php

if($message!="")
{

?>

<div class="alert alert-success">

<?php echo $message; ?>

</div>

<?php

}

?>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>

Restaurant Name

</label>

<input

type="text"

name="restaurant_name"

class="form-control"

value="<?php echo $settings['restaurant_name']; ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Restaurant Phone

</label>

<input

type="text"

name="phone"

class="form-control"

value="<?php echo $settings['phone']; ?>">

</div>

<div class="col-md-6 mb-3">

<label>

MTN Mobile Money

</label>

<input

type="text"

name="mtn_number"

class="form-control"

value="<?php echo $settings['mtn_number']; ?>">
</div>

<div class="col-md-6 mb-3">

<label>

Airtel Money Number

</label>

<input

type="text"

name="airtel_number"

class="form-control"

value="<?php echo $settings['airtel_number']; ?>">

</div>

<div class="col-md-6 mb-3">

<label>

Restaurant Email

</label>

<input

type="email"

name="email"

class="form-control"

value="<?php echo $settings['email']; ?>">

</div>

<div class="col-md-6 mb-3">

<label>

Restaurant Address

</label>

<textarea

name="address"

rows="3"

class="form-control"><?php echo $settings['address']; ?></textarea>

</div>

<div class="col-md-12 mb-4">

<label>

Restaurant Logo

</label>

<br><br>

<?php

if(!empty($settings['logo']))
{

?>

<img

src="../assets/images/<?php echo $settings['logo']; ?>"
style="width:150px;
height:150px;
border-radius:12px;
object-fit:cover;
border:2px solid #ddd;">

<?php

}
else
{

?>

<img

src="../assets/images/logo.png"

style="width:150px;
height:150px;
border-radius:12px;
object-fit:cover;">

<?php

}

?>

<p class="text-muted mt-2">

Logo upload will be added in the next update.

</p>

</div>

<div class="col-md-12">

<button

type="submit"

name="save"

class="btn btn-success btn-lg">

<i class="fas fa-save"></i>

Save Settings

</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>