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

$user = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$user_id'
"));

$total_orders = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
WHERE user_id='$user_id'
"));

$pending_orders = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
WHERE user_id='$user_id'
AND order_status='Pending'
"));

$completed_orders = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
WHERE user_id='$user_id'
AND order_status='Delivered'
"));

/* ==============================
   UNREAD MESSAGES
============================== */

$unread_messages = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM messages
WHERE user_id='$user_id'
AND sender='admin'
AND is_read='0'
"));

/* ==============================
   UNREAD NOTIFICATIONS
============================== */

$unread_notifications = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM notifications
WHERE user_id='$user_id'
AND is_read='0'
"));
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

Customer Dashboard

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../assets/css/style.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{

background:#f5f6fa;

}

.sidebar{

background:#212529;

min-height:100vh;

padding-top:30px;

}

.sidebar a{

display:block;

color:#fff;

padding:15px 20px;

text-decoration:none;

transition:.3s;

}

.sidebar a:hover{

background:#ffc107;

color:#000;

}

.dashboard-card{

border:none;

border-radius:15px;

box-shadow:0 10px 30px rgba(0,0,0,.1);

transition:.3s;

}

.dashboard-card:hover{

transform:translateY(-5px);

}

</style>

</head>

<body>

<div class="container-fluid">

<div class="row">

<div class="col-lg-2 sidebar">

<div class="text-center text-white mb-4">

<img
src="../assets/images/logo.png"
style="width:80px;height:80px;border-radius:50%;">

<h5 class="mt-3">

Taste Haven

</h5>

</div>

<a href="dashboard.php">

<i class="fas fa-home"></i>

Dashboard

</a>

<a href="my_orders.php">

<i class="fas fa-shopping-bag"></i>

My Orders

</a>

<a href="../track_order.php">

<i class="fas fa-search-location"></i>

Track Order

</a>

<a href="profile.php">

<i class="fas fa-user"></i>

My Profile

</a>

<a href="messages.php">

<i class="fas fa-envelope"></i>

Messages

<?php
if($unread_messages>0)
{
echo '<span class="badge bg-danger float-end">'.$unread_messages.'</span>';
}
?>

</a>

<a href="notifications.php">

<i class="fas fa-bell"></i>

Notifications

<?php
if($unread_notifications>0)
{
echo '<span class="badge bg-danger float-end">'.$unread_notifications.'</span>';
}
?>

</a>

<a href="../menu.php">

<i class="fas fa-utensils"></i>

Order Food

</a>

<a href="logout.php">

<i class="fas fa-sign-out-alt"></i>

Logout

</a>

</div>

<div class="col-lg-10">

<div class="p-4">

<h2>

Welcome,

<?php echo $user['fullname']; ?>

</h2>

<p class="text-muted">

Manage your orders and profile.

</p>

<div class="row">

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="card dashboard-card">

<div class="card-body text-center">

<i class="fas fa-shopping-cart fa-3x text-warning mb-3"></i>

<h3>

<?php echo $total_orders; ?>

</h3>

<h5>

Total Orders

</h5>

</div>

</div>

</div>

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="card dashboard-card">

<div class="card-body text-center">

<i class="fas fa-clock fa-3x text-primary mb-3"></i>

<h3>

<?php echo $pending_orders; ?>

</h3>

<h5>

Pending Orders

</h5>

</div>

</div>

</div>

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="card dashboard-card">

<div class="card-body text-center">

<i class="fas fa-check-circle fa-3x text-success mb-3"></i>

<h3>

<?php echo $completed_orders; ?>

</h3>

<h5>

Completed Orders

</h5>

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="card dashboard-card">

<div class="card-body text-center">

<i class="fas fa-envelope fa-3x text-success mb-3"></i>

<h3><?php echo $unread_messages; ?></h3>

<h5>Messages</h5>

</div>

</div>

</div>

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="card dashboard-card">

<div class="card-body text-center">

<i class="fas fa-bell fa-3x text-danger mb-3"></i>

<h3><?php echo $unread_notifications; ?></h3>

<h5>Notifications</h5>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

<div class="row">
<div class="col-lg-6 mb-4">

<div class="card dashboard-card">

<div class="card-header bg-warning">

<h4>

<i class="fas fa-bolt"></i>

Quick Actions

</h4>

</div>

<div class="card-body">

<div class="d-grid gap-3">

<a href="../menu.php" class="btn btn-warning btn-lg">

<i class="fas fa-utensils"></i>

Order Food

</a>

<a href="my_orders.php" class="btn btn-primary btn-lg">

<i class="fas fa-shopping-bag"></i>

My Orders

</a>

<a href="../track_order.php" class="btn btn-info btn-lg text-white">

<i class="fas fa-search-location"></i>

Track Order

</a>

<a href="messages.php" class="btn btn-success btn-lg">

<i class="fas fa-envelope"></i>

Messages

<?php
if($unread_messages>0)
{
    echo " <span class='badge bg-light text-dark ms-2'>$unread_messages</span>";
}
?>

</a>

<a href="notifications.php" class="btn btn-danger btn-lg">

<i class="fas fa-bell"></i>

Notifications

<?php
if($unread_notifications>0)
{
    echo " <span class='badge bg-light text-dark ms-2'>$unread_notifications</span>";
}
?>

</a>

<a href="profile.php" class="btn btn-secondary btn-lg">

<i class="fas fa-user-edit"></i>

Edit Profile

</a>

</div>

</div>

</div>

</div>

<div class="col-lg-6 mb-4">

<div class="card dashboard-card">

<div class="card-header bg-dark text-white">

<h4>

<i class="fas fa-history"></i>

Recent Orders

</h4>

</div>

<div class="card-body">

<?php

$orders=mysqli_query($conn,"
SELECT *
FROM orders
WHERE user_id='$user_id'
ORDER BY created_at DESC
LIMIT 5
");

if(mysqli_num_rows($orders)>0)
{

?>

<div class="table-responsive">

<table class="table table-hover">

<thead>

<tr>

<th>Transaction</th>

<th>Amount</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

while($order=mysqli_fetch_assoc($orders))
{

?>

<tr>

<td>

<?php echo $order['transaction_id']; ?>

</td>

<td>

<?php echo CURRENCY." ".number_format($order['total_amount'],2); ?>

</td>

<td>

<?php

$status=$order['order_status'];

if($status=="Pending")
{

echo "<span class='badge bg-warning text-dark'>Pending</span>";

}
elseif($status=="Preparing")
{

echo "<span class='badge bg-primary'>Preparing</span>";

}
elseif($status=="Ready")
{

echo "<span class='badge bg-info'>Ready</span>";

}
elseif($status=="Out For Delivery")
{

echo "<span class='badge bg-secondary'>Out For Delivery</span>";

}
elseif($status=="Delivered")
{

echo "<span class='badge bg-success'>Delivered</span>";

}
else
{

echo "<span class='badge bg-danger'>Cancelled</span>";

}

?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<?php

}
else
{

?>

<div class="text-center py-5">

<i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>

<h5>

No Orders Yet

</h5>

<p class="text-muted">

You haven't placed any orders yet.

</p>

<a href="../menu.php" class="btn btn-warning">

Start Ordering

</a>

</div>

<?php

}

?>

</div>

</div>

</div>

</div>

<div class="card mt-4 shadow border-0">

<div class="card-body text-center">

<h4>

Thank you for choosing Taste Haven Restaurant 🍽️

</h4>

<p class="text-muted">

We're committed to serving fresh, delicious meals with fast and reliable delivery.

</p>

<a href="../menu.php" class="btn btn-warning btn-lg">

<i class="fas fa-utensils"></i>

Browse Menu

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>