<?php
include("includes/auth.php");
include("../config/db.php");
include("../config/config.php");

if(!isset($_GET['id']))
{
    header("Location: customers.php");
    exit();
}

$id = (int)$_GET['id'];

$customer = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$id'
"));

if(!$customer)
{
    header("Location: customers.php");
    exit();
}

/* ==========================
CUSTOMER STATISTICS
========================== */

$total_orders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM orders
WHERE customer_name='".$customer['fullname']."'
"));

$completed_orders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM orders
WHERE customer_name='".$customer['fullname']."'
AND order_status='Delivered'
"));

$pending_orders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM orders
WHERE customer_name='".$customer['fullname']."'
AND order_status='Pending'
"));

$total_spent = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total_amount) AS total
FROM orders
WHERE customer_name='".$customer['fullname']."'
AND order_status='Delivered'
"));

$recent_orders = mysqli_query($conn,"
SELECT *
FROM orders
WHERE customer_name='".$customer['fullname']."'
ORDER BY created_at DESC
LIMIT 5
");
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

View Customer

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

<style>

.profile-card{

border:none;

border-radius:20px;

box-shadow:0 10px 30px rgba(0,0,0,.08);

}

.avatar{

width:120px;

height:120px;

border-radius:50%;

background:#ffc107;

display:flex;

align-items:center;

justify-content:center;

font-size:50px;

color:#fff;

margin:auto;

}

.stat-card{

border:none;

border-radius:18px;

box-shadow:0 10px 25px rgba(0,0,0,.08);

transition:.3s;

}

.stat-card:hover{

transform:translateY(-5px);

}

.stat-icon{

width:65px;

height:65px;

border-radius:50%;

display:flex;

align-items:center;

justify-content:center;

font-size:28px;

color:#fff;

margin:auto;

margin-bottom:15px;

}

</style>

</head>

<body>

<div class="wrapper">

<?php include("includes/sidebar.php"); ?>

<div class="main">

<?php include("includes/header.php"); ?>

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

Customer Profile

</h2>

<p class="text-muted">

View customer information and activity.

</p>

</div>

<div>

<a href="customers.php" class="btn btn-dark">

<i class="fas fa-arrow-left"></i>

Back to Customers

</a>

</div>

</div>
<!-- CUSTOMER PROFILE -->

<div class="card profile-card mb-4">

<div class="card-body">

<div class="row align-items-center">

<div class="col-lg-3 text-center">

<div class="avatar">

<i class="fas fa-user"></i>

</div>

<h4 class="mt-3">

<?php echo $customer['fullname']; ?>

</h4>

<p class="text-muted">

Registered Customer

</p>

</div>

<div class="col-lg-9">

<div class="row">

<div class="col-md-6 mb-3">

<h6 class="text-muted">

Full Name

</h6>

<p class="fw-bold">

<?php echo $customer['fullname']; ?>

</p>

</div>

<div class="col-md-6 mb-3">

<h6 class="text-muted">

Email Address

</h6>

<p class="fw-bold">

<?php echo $customer['email']; ?>

</p>

</div>

<div class="col-md-6 mb-3">

<h6 class="text-muted">

Phone Number

</h6>

<p class="fw-bold">

<?php echo $customer['phone']; ?>

</p>

</div>

<div class="col-md-6 mb-3">

<h6 class="text-muted">

Registration Date

</h6>

<p class="fw-bold">

<?php

if(isset($customer['created_at']))
{

echo date("d M Y",strtotime($customer['created_at']));

}
else
{

echo "-";

}

?>

</p>

</div>

</div>

</div>

</div>

</div>

</div>

<!-- CUSTOMER STATISTICS -->

<div class="row mb-4">

<div class="col-lg-3 col-md-6 mb-3">

<div class="card stat-card text-center">

<div class="card-body">

<div class="stat-icon bg-primary">

<i class="fas fa-shopping-cart"></i>

</div>

<h3>

<?php echo $total_orders['total']; ?>

</h3>

<p class="text-muted">

Total Orders

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="card stat-card text-center">

<div class="card-body">

<div class="stat-icon bg-success">

<i class="fas fa-check-circle"></i>

</div>

<h3>

<?php echo $completed_orders['total']; ?>

</h3>

<p class="text-muted">

Completed Orders

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="card stat-card text-center">

<div class="card-body">

<div class="stat-icon bg-warning">

<i class="fas fa-clock"></i>

</div>

<h3>

<?php echo $pending_orders['total']; ?>

</h3>

<p class="text-muted">

Pending Orders

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="card stat-card text-center bg-dark text-white">

<div class="card-body">

<div class="stat-icon bg-danger">

<i class="fas fa-dollar-sign"></i>

</div>

<h4>

<?php

echo CURRENCY." ".number_format($total_spent['total'] ?? 0,2);

?>

</h4>

<p>

Total Spent

</p>

</div>

</div>

</div>

</div>
<!-- RECENT ORDERS -->

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-receipt"></i>

Recent Orders

</h4>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Transaction ID</th>

<th>Payment Method</th>

<th>Total Amount</th>

<th>Order Status</th>

<th>Date</th>

<th width="120">

Action

</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($recent_orders)>0)
{

while($order=mysqli_fetch_assoc($recent_orders))
{

?>

<tr>

<td>

<strong class="text-primary">

<?php echo $order['transaction_id']; ?>

</strong>

</td>

<td>

<?php echo $order['payment_method']; ?>

</td>

<td>

<strong class="text-danger">

<?php

echo CURRENCY." ".number_format($order['total_amount'],2);

?>

</strong>

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

echo "<span class='badge bg-info text-dark'>Ready</span>";

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

<td>

<?php echo date("d M Y",strtotime($order['created_at'])); ?>

<br>

<small>

<?php echo date("h:i A",strtotime($order['created_at'])); ?>

</small>

</td>

<td>

<a

href="view_order.php?id=<?php echo $order['id']; ?>"

class="btn btn-primary btn-sm">

<i class="fas fa-eye"></i>

</a>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="6" class="text-center py-5">

<i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>

<h4>

No Orders Found

</h4>

<p class="text-muted">

This customer has not placed any orders yet.

</p>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

<div class="mt-4 text-end">

<a

href="customer_orders.php?id=<?php echo $customer['id']; ?>"

class="btn btn-success me-2">

<i class="fas fa-shopping-bag"></i>

View All Orders

</a>

<a

href="delete_customer.php?id=<?php echo $customer['id']; ?>"

class="btn btn-danger"

onclick="return confirm('Are you sure you want to delete this customer?');">

<i class="fas fa-trash"></i>

Delete Customer

</a>

</div>

<div class="admin-footer">

<hr>

<p>

© <?php echo date("Y"); ?>

Taste Haven Restaurant Management System

</p>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>