<?php
include("includes/auth.php");
include("../config/db.php");
include("../config/config.php");

if(!isset($_GET['id']))
{
    header("Location: customers.php");
    exit();
}

$id=(int)$_GET['id'];

$customer=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$id'
"));

if(!$customer)
{
    header("Location: customers.php");
    exit();
}

$total_orders=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM orders
WHERE customer_name='".$customer['fullname']."'
"));

$total_spent=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total_amount) AS total
FROM orders
WHERE customer_name='".$customer['fullname']."'
AND order_status='Delivered'
"));

$orders=mysqli_query($conn,"
SELECT *
FROM orders
WHERE customer_name='".$customer['fullname']."'
ORDER BY created_at DESC
");
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

Customer Orders

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

.stat-card{

border:none;

border-radius:18px;

box-shadow:0 10px 30px rgba(0,0,0,.08);

transition:.3s;

}

.stat-card:hover{

transform:translateY(-5px);

}

.icon-box{

width:65px;

height:65px;

border-radius:50%;

display:flex;

align-items:center;

justify-content:center;

font-size:28px;

margin:auto;

margin-bottom:15px;

color:white;

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

Customer Orders

</h2>

<p class="text-muted">

Order history for

<strong>

<?php echo $customer['fullname']; ?>

</strong>

</p>

</div>

<div>

<a href="view_customer.php?id=<?php echo $customer['id']; ?>" class="btn btn-dark">

<i class="fas fa-arrow-left"></i>

Back to Profile

</a>

</div>

</div>

<div class="row mb-4">

<div class="col-lg-6">

<div class="card stat-card text-center">

<div class="card-body">

<div class="icon-box bg-primary">

<i class="fas fa-shopping-cart"></i>

</div>

<h2>

<?php echo $total_orders['total']; ?>

</h2>

<p>

Total Orders

</p>

</div>

</div>

</div>

<div class="col-lg-6">

<div class="card stat-card text-center bg-dark text-white">

<div class="card-body">

<div class="icon-box bg-danger">

<i class="fas fa-dollar-sign"></i>

</div>

<h3>

<?php echo CURRENCY." ".number_format($total_spent['total'] ?? 0,2); ?>

</h3>

<p>

Total Spent

</p>

</div>

</div>

</div>

</div>
<!-- CUSTOMER ORDERS TABLE -->

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-list"></i>

Order History

</h4>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Transaction ID</th>

<th>Payment</th>

<th>Total Amount</th>

<th>Payment Status</th>

<th>Order Status</th>

<th>Date</th>

<th width="150">

Action

</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($orders)>0)
{

while($row=mysqli_fetch_assoc($orders))
{

?>

<tr>

<td>

<?php echo $row['id']; ?>

</td>

<td>

<strong class="text-primary">

<?php echo $row['transaction_id']; ?>

</strong>

</td>

<td>

<strong>

<?php echo $row['payment_method']; ?>

</strong>

<br>

<small class="text-muted">

<?php echo $row['payment_phone']; ?>

</small>

</td>

<td>

<strong class="text-danger">

<?php

echo CURRENCY." ".number_format($row['total_amount'],2);

?>

</strong>

</td>

<td>

<?php

if($row['payment_status']=="Pending")
{

echo "<span class='badge bg-warning text-dark'>Pending</span>";

}
elseif($row['payment_status']=="Verified")
{

echo "<span class='badge bg-success'>Verified</span>";

}
else
{

echo "<span class='badge bg-danger'>Rejected</span>";

}

?>

</td>

<td>

<?php

$status=$row['order_status'];

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

<?php echo date("d M Y",strtotime($row['created_at'])); ?>

<br>

<small>

<?php echo date("h:i A",strtotime($row['created_at'])); ?>

</small>

</td>

<td>

<div class="btn-group">

<a

href="view_order.php?id=<?php echo $row['id']; ?>"

class="btn btn-primary btn-sm">

<i class="fas fa-eye"></i>

</a>

<a

href="../receipt.php?transaction=<?php echo $row['transaction_id']; ?>"

target="_blank"

class="btn btn-dark btn-sm">

<i class="fas fa-print"></i>

</a>

</div>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="8" class="text-center py-5">

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
<div class="mt-4 d-flex justify-content-between align-items-center">

<div>

<a

href="customers.php"

class="btn btn-secondary">

<i class="fas fa-users"></i>

Back to Customers

</a>

</div>

<div>

<a

href="view_customer.php?id=<?php echo $customer['id']; ?>"

class="btn btn-primary me-2">

<i class="fas fa-user"></i>

Customer Profile

</a>

<a

href="orders.php"

class="btn btn-success">

<i class="fas fa-shopping-cart"></i>

All Orders

</a>

</div>

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