<?php
include("includes/auth.php");
include("../config/db.php");
include("../config/config.php");

/* ==========================
REVENUE
========================== */

$total_revenue=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total_amount) AS total
FROM orders
WHERE order_status='Delivered'
"));

$today_revenue=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total_amount) AS total
FROM orders
WHERE DATE(created_at)=CURDATE()
AND order_status='Delivered'
"));

$total_orders=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
"));

$delivered_orders=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Delivered'
"));

$pending_orders=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Pending'
"));

$preparing_orders=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Preparing'
"));

$ready_orders=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Ready'
"));

$delivery_orders=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Out For Delivery'
"));

$cancelled_orders=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Cancelled'
"));
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

Sales Reports

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

.report-card{

border:none;

border-radius:18px;

box-shadow:0 10px 30px rgba(0,0,0,.08);

transition:.3s;

}

.report-card:hover{

transform:translateY(-5px);

}

.icon-box{

width:65px;

height:65px;

border-radius:50%;

display:flex;

align-items:center;

justify-content:center;

margin:auto;

margin-bottom:15px;

font-size:28px;

color:#fff;

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

Restaurant Reports

</h2>

<p class="text-muted">

Sales reports and business analytics.

</p>

</div>

<div>

</div>

</div>

<div class="row mb-4">

<div class="col-lg-3 col-md-6 mb-3">

<div class="card report-card text-center">

<div class="card-body">

<div class="icon-box bg-success">

<i class="fas fa-dollar-sign"></i>

</div>

<h4>

<?php echo CURRENCY." ".number_format($total_revenue['total'] ?? 0,2); ?>

</h4>

<p>Total Revenue</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="card report-card text-center">

<div class="card-body">

<div class="icon-box bg-primary">

<i class="fas fa-calendar-day"></i>

</div>

<h4>

<?php echo CURRENCY." ".number_format($today_revenue['total'] ?? 0,2); ?>

</h4>

<p>Today's Revenue</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="card report-card text-center">

<div class="card-body">

<div class="icon-box bg-warning">

<i class="fas fa-shopping-cart"></i>

</div>

<h3>

<?php echo $total_orders; ?>

</h3>

<p>Total Orders</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="card report-card text-center">

<div class="card-body">

<div class="icon-box bg-info">

<i class="fas fa-check-circle"></i>

</div>

<h3>

<?php echo $delivered_orders; ?>

</h3>

<p>Delivered Orders</p>

</div>

</div>

</div>

</div>
<!-- ORDER STATUS SUMMARY -->

<div class="row mb-4">

<div class="col-lg-8">

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-chart-pie"></i>

Order Status Summary

</h4>

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-light">

<tr>

<th>Order Status</th>

<th>Total Orders</th>

</tr>

</thead>

<tbody>

<tr>

<td><span class="badge bg-warning text-dark">Pending</span></td>

<td><?php echo $pending_orders; ?></td>

</tr>

<tr>

<td><span class="badge bg-primary">Preparing</span></td>

<td><?php echo $preparing_orders; ?></td>

</tr>

<tr>

<td><span class="badge bg-info text-dark">Ready</span></td>

<td><?php echo $ready_orders; ?></td>

</tr>

<tr>

<td><span class="badge bg-secondary">Out For Delivery</span></td>

<td><?php echo $delivery_orders; ?></td>

</tr>

<tr>

<td><span class="badge bg-success">Delivered</span></td>

<td><?php echo $delivered_orders; ?></td>

</tr>

<tr>

<td><span class="badge bg-danger">Cancelled</span></td>

<td><?php echo $cancelled_orders; ?></td>

</tr>

</tbody>

</table>

</div>

</div>

</div>

<!-- PAYMENT SUMMARY -->

<div class="col-lg-4">

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-credit-card"></i>

Payment Summary

</h4>

</div>

<div class="card-body">

<?php

$mtn=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM orders
WHERE payment_method='MTN Mobile Money'
"));

$airtel=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM orders
WHERE payment_method='Airtel Money'
"));

?>

<div class="d-flex justify-content-between mb-3">

<span>

<i class="fas fa-mobile-alt text-warning"></i>

MTN Mobile Money

</span>

<strong>

<?php echo $mtn['total']; ?>

</strong>

</div>

<div class="d-flex justify-content-between">

<span>

<i class="fas fa-mobile-alt text-danger"></i>

Airtel Money

</span>

<strong>

<?php echo $airtel['total']; ?>

</strong>

</div>

</div>

</div>

</div>

</div>

<!-- BEST SELLING FOODS -->

<div class="card shadow border-0 mb-4">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-utensils"></i>

Best Selling Foods

</h4>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover">

<thead class="table-dark">

<tr>

<th>Food Name</th>

<th>Total Orders</th>

</tr>

</thead>

<tbody>

<?php

$foods=mysqli_query($conn,"
SELECT

foods.food_name,

SUM(order_items.quantity) AS total

FROM order_items

INNER JOIN foods
ON foods.id=order_items.food_id

GROUP BY foods.id, foods.food_name

ORDER BY total DESC

LIMIT 10
");

if(mysqli_num_rows($foods)>0)
{

while($food=mysqli_fetch_assoc($foods))
{

?>

<tr>

<td>

<?php echo $food['food_name']; ?>

</td>

<td>

<span class="badge bg-success">

<?php echo $food['total']; ?>

Orders

</span>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="2" class="text-center">

No food sales available.

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

<!-- TOP CUSTOMERS -->

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-users"></i>

Top Customers

</h4>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover">

<thead class="table-dark">

<tr>

<th>Customer</th>

<th>Total Orders</th>

<th>Total Spent</th>

</tr>

</thead>

<tbody>

<?php

$customers=mysqli_query($conn,"
SELECT
customer_name,
COUNT(*) AS orders_count,
SUM(total_amount) AS spent
FROM orders
WHERE order_status='Delivered'
GROUP BY customer_name
ORDER BY spent DESC
LIMIT 10
");

if(mysqli_num_rows($customers)>0)
{

while($customer=mysqli_fetch_assoc($customers))
{

?>

<tr>

<td>

<?php echo $customer['customer_name']; ?>

</td>

<td>

<?php echo $customer['orders_count']; ?>

</td>

<td>

<strong class="text-success">

<?php echo CURRENCY." ".number_format($customer['spent'],2); ?>

</strong>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="3" class="text-center">

No customer records available.

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
<!-- REPORT SUMMARY -->

<div class="row mt-4">

<div class="col-lg-12">

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-chart-line"></i>

Business Summary

</h4>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3 text-center">

<h2 class="text-success">

<?php echo CURRENCY." ".number_format($total_revenue['total'] ?? 0,2); ?>

</h2>

<p class="text-muted">

Total Revenue Generated

</p>

</div>

<div class="col-md-3 text-center">

<h2 class="text-primary">

<?php echo $total_orders; ?>

</h2>

<p class="text-muted">

Orders Received

</p>

</div>

<div class="col-md-3 text-center">

<h2 class="text-warning">

<?php echo $delivered_orders; ?>

</h2>

<p class="text-muted">

Orders Delivered

</p>

</div>

<div class="col-md-3 text-center">

<h2 class="text-danger">

<?php echo $cancelled_orders; ?>

</h2>

<p class="text-muted">

Orders Cancelled

</p>

</div>

</div>

<hr>

<div class="text-center mt-3">

<button
class="btn btn-dark me-2"
onclick="window.print();">

<i class="fas fa-print"></i>

Print Report

</button>

<a href="export_excel.php" class="btn btn-success">

<i class="fas fa-file-excel"></i>

Export Excel

</a>

<a href="export_pdf.php" class="btn btn-danger">

<i class="fas fa-file-pdf"></i>

Export PDF

</a>

</div>

</div>

</div>

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