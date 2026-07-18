<?php
include("includes/auth.php");
include("../config/db.php");
include("../config/config.php");

/* ==========================================
SEARCH
========================================== */

$search="";

$where="";

if(isset($_GET['search']))
{

    $search=mysqli_real_escape_string($conn,$_GET['search']);

    $where="

    WHERE

    fullname LIKE '%$search%'

    OR

    email LIKE '%$search%'

    OR

    phone LIKE '%$search%'

    ";

}

/* ==========================================
STATISTICS
========================================== */

$total_customers=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM users
"));

$total_orders=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
"));

$customers_with_orders=mysqli_num_rows(mysqli_query($conn,"
SELECT DISTINCT customer_name
FROM orders
"));

$revenue=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total_amount) AS total
FROM orders
WHERE order_status='Delivered'
"));

/* ==========================================
CUSTOMERS
========================================== */

$query=mysqli_query($conn,"
SELECT *
FROM users

$where

ORDER BY id DESC
");
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

Customers

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

.card-stat{

border:none;

border-radius:18px;

box-shadow:0 10px 30px rgba(0,0,0,.08);

transition:.3s;

}

.card-stat:hover{

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

.customer-avatar{

width:50px;

height:50px;

border-radius:50%;

background:#ffc107;

display:flex;

align-items:center;

justify-content:center;

color:white;

font-size:22px;

font-weight:bold;

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

Customer Management

</h2>

<p class="text-muted">

Manage all registered restaurant customers.

</p>

</div>

</div>

<div class="row mb-4">

<div class="col-lg-3 col-md-6 mb-3">

<div class="card card-stat text-center">

<div class="card-body">

<div class="icon-box bg-primary">

<i class="fas fa-users"></i>

</div>

<h3>

<?php echo $total_customers; ?>

</h3>

<p class="text-muted">

Total Customers

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="card card-stat text-center">

<div class="card-body">

<div class="icon-box bg-success">

<i class="fas fa-user-check"></i>

</div>

<h3>

<?php echo $customers_with_orders; ?>

</h3>

<p class="text-muted">

Customers With Orders

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="card card-stat text-center">

<div class="card-body">

<div class="icon-box bg-warning">

<i class="fas fa-shopping-cart"></i>

</div>

<h3>

<?php echo $total_orders; ?>

</h3>

<p class="text-muted">

Total Orders

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="card card-stat text-center bg-dark text-white">

<div class="card-body">

<div class="icon-box bg-danger">

<i class="fas fa-dollar-sign"></i>

</div>

<h5>

<?php echo CURRENCY." ".number_format($revenue['total'] ?? 0,2); ?>

</h5>

<p>

Revenue

</p>

</div>

</div>

</div>

</div>
<!-- SEARCH -->

<div class="card shadow border-0 mb-4">

<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-lg-10 mb-2">

<input

type="text"

name="search"

class="form-control form-control-lg"

placeholder="Search by Customer Name, Email or Phone"

value="<?php echo $search; ?>">

</div>

<div class="col-lg-2 mb-2">

<div class="d-grid">

<button

class="btn btn-warning btn-lg">

<i class="fas fa-search"></i>

Search

</button>

</div>

</div>

</div>

</form>

</div>

</div>

<!-- CUSTOMERS TABLE -->

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-users"></i>

Registered Customers

</h4>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Customer</th>

<th>Phone</th>

<th>Orders</th>

<th>Total Spent</th>

<th>Joined</th>

<th width="180">

Action

</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($query)>0)
{

while($row=mysqli_fetch_assoc($query))
{

/* CUSTOMER ORDERS */

$order_count=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM orders
WHERE customer_name='".$row['fullname']."'
"));

/* TOTAL SPENT */

$total_spent=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total_amount) AS total
FROM orders
WHERE customer_name='".$row['fullname']."'
AND order_status='Delivered'
"));

?>

<tr>

<td>

<?php echo $row['id']; ?>

</td>

<td>

<div class="d-flex align-items-center">

<div class="customer-avatar">

<i class="fas fa-user"></i>

</div>

<div class="ms-3">

<strong>

<?php echo $row['fullname']; ?>

</strong>

<br>

<small class="text-muted">

<?php echo $row['email']; ?>

</small>

</div>

</div>

</td>

<td>

<i class="fas fa-phone text-success"></i>

<?php echo $row['phone']; ?>

</td>

<td>

<span class="badge bg-primary">

<?php echo $order_count['total']; ?>

Orders

</span>

</td>

<td>

<strong class="text-danger">

<?php

echo CURRENCY." ".number_format($total_spent['total'] ?? 0,2);

?>

</strong>

</td>

<td>

<?php

if(isset($row['created_at']))
{

echo date("d M Y",strtotime($row['created_at']));

}
else
{

echo "-";

}

?>

</td>

<td>

<div class="btn-group">

<a

href="view_customer.php?id=<?php echo $row['id']; ?>"

class="btn btn-primary btn-sm">

<i class="fas fa-eye"></i>

</a>

<a

href="customer_orders.php?id=<?php echo $row['id']; ?>"

class="btn btn-success btn-sm">

<i class="fas fa-shopping-cart"></i>

</a>

<a

href="delete_customer.php?id=<?php echo $row['id']; ?>"

onclick="return confirm('Delete this customer?')"

class="btn btn-danger btn-sm">

<i class="fas fa-trash"></i>

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

<td colspan="7" class="text-center py-5">

<i class="fas fa-users fa-4x text-muted mb-3"></i>

<h4>

No Customers Found

</h4>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>