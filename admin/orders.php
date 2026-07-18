<?php
include("includes/auth.php");
include("../config/db.php");
include("../config/config.php");

/* ==========================================
UPDATE ORDER
========================================== */

if(isset($_POST['update']))
{

$id=(int)$_POST['id'];

$payment_status=mysqli_real_escape_string($conn,$_POST['payment_status']);

$order_status=mysqli_real_escape_string($conn,$_POST['order_status']);

mysqli_query($conn,"
UPDATE orders
SET

payment_status='$payment_status',

order_status='$order_status'

WHERE id='$id'
");

}

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

transaction_id LIKE '%$search%'

OR customer_name LIKE '%$search%'

OR phone LIKE '%$search%'

";

}

/* ==========================================
STATISTICS
========================================== */

$total_orders=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
"));

$pending=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Pending'
"));

$preparing=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Preparing'
"));

$ready=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Ready'
"));

$delivery=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Out For Delivery'
"));

$delivered=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Delivered'
"));

$cancelled=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE order_status='Cancelled'
"));

$revenue=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total_amount) AS total
FROM orders
WHERE order_status='Delivered'
"));

$query=mysqli_query($conn,"
SELECT *
FROM orders

$where

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

Restaurant Orders

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

body{

background:#f4f6f9;

}

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

Restaurant Orders

</h2>

<p class="text-muted">

Manage customer orders, verify payments and monitor deliveries.

</p>

</div>

</div>
<div class="row mb-4">

<div class="col-lg col-md-6 mb-3">

<div class="card card-stat text-center">

<div class="card-body">

<div class="icon-box bg-primary">

<i class="fas fa-shopping-cart"></i>

</div>

<h3>

<?php echo $total_orders; ?>

</h3>

<p class="text-muted mb-0">

Total Orders

</p>

</div>

</div>

</div>

<div class="col-lg col-md-6 mb-3">

<div class="card card-stat text-center">

<div class="card-body">

<div class="icon-box bg-warning">

<i class="fas fa-clock"></i>

</div>

<h3>

<?php echo $pending; ?>

</h3>

<p class="text-muted mb-0">

Pending

</p>

</div>

</div>

</div>

<div class="col-lg col-md-6 mb-3">

<div class="card card-stat text-center">

<div class="card-body">

<div class="icon-box bg-info">

<i class="fas fa-user-chef"></i>

</div>

<h3>

<?php echo $preparing; ?>

</h3>

<p class="text-muted mb-0">

Preparing

</p>

</div>

</div>

</div>

<div class="col-lg col-md-6 mb-3">

<div class="card card-stat text-center">

<div class="card-body">

<div class="icon-box bg-secondary">

<i class="fas fa-bell"></i>

</div>

<h3>

<?php echo $ready; ?>

</h3>

<p class="text-muted mb-0">

Ready

</p>

</div>

</div>

</div>

<div class="col-lg col-md-6 mb-3">

<div class="card card-stat text-center">

<div class="card-body">

<div class="icon-box bg-dark">

<i class="fas fa-motorcycle"></i>

</div>

<h3>

<?php echo $delivery; ?>

</h3>

<p class="text-muted mb-0">

Out For Delivery

</p>

</div>

</div>

</div>

<div class="col-lg col-md-6 mb-3">

<div class="card card-stat text-center">

<div class="card-body">

<div class="icon-box bg-success">

<i class="fas fa-check-circle"></i>

</div>

<h3>

<?php echo $delivered; ?>

</h3>

<p class="text-muted mb-0">

Delivered

</p>

</div>

</div>

</div>

<div class="col-lg col-md-6 mb-3">

<div class="card card-stat text-center bg-warning">

<div class="card-body">

<div class="icon-box bg-danger">

<i class="fas fa-dollar-sign"></i>

</div>

<h5>

<?php echo CURRENCY." ".number_format($revenue['total'] ?? 0,2); ?>

</h5>

<p class="mb-0">

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

placeholder="Search by Transaction ID, Customer Name or Phone Number"

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

<!-- ORDERS TABLE -->

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-list"></i>

Customer Orders

</h4>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Transaction</th>

<th>Customer</th>

<th>Payment</th>

<th>Total</th>

<th>Payment Status</th>

<th>Order Status</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>

<tbody>
<?php

if(mysqli_num_rows($query)>0)
{

while($row=mysqli_fetch_assoc($query))
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

<?php echo $row['customer_name']; ?>

</strong>

<br>

<small class="text-muted">

<?php echo $row['email']; ?>

</small>

<br>

<small>

<i class="fas fa-phone"></i>

<?php echo $row['phone']; ?>

</small>

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

<?php echo CURRENCY." ".number_format($row['total_amount'],2); ?>

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

<button

class="btn btn-success btn-sm"

data-bs-toggle="modal"

data-bs-target="#order<?php echo $row['id']; ?>">

<i class="fas fa-edit"></i>

</button>

<a

href="../receipt.php?transaction=<?php echo $row['transaction_id']; ?>"

target="_blank"

class="btn btn-dark btn-sm">

<i class="fas fa-print"></i>

</a>

</div>

</td>

</tr>

<!-- UPDATE MODAL -->

<div

class="modal fade"

id="order<?php echo $row['id']; ?>">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST">

<div class="modal-header bg-warning">

<h5>

Update Order

</h5>

<button

type="button"

class="btn-close"

data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<input

type="hidden"

name="id"

value="<?php echo $row['id']; ?>">

<label>

Payment Status

</label>

<select

name="payment_status"

class="form-select mb-3">

<option value="Pending" <?php if($row['payment_status']=="Pending") echo "selected"; ?>>

Pending

</option>

<option value="Verified" <?php if($row['payment_status']=="Verified") echo "selected"; ?>>

Verified

</option>

<option value="Rejected" <?php if($row['payment_status']=="Rejected") echo "selected"; ?>>

Rejected

</option>

</select>

<label>

Order Status

</label>

<select

name="order_status"

class="form-select">

<option value="Pending" <?php if($status=="Pending") echo "selected"; ?>>

Pending

</option>

<option value="Preparing" <?php if($status=="Preparing") echo "selected"; ?>>

Preparing

</option>

<option value="Ready" <?php if($status=="Ready") echo "selected"; ?>>

Ready

</option>

<option value="Out For Delivery" <?php if($status=="Out For Delivery") echo "selected"; ?>>

Out For Delivery

</option>

<option value="Delivered" <?php if($status=="Delivered") echo "selected"; ?>>

Delivered

</option>

<option value="Cancelled" <?php if($status=="Cancelled") echo "selected"; ?>>

Cancelled

</option>

</select>

</div>

<div class="modal-footer">

<button

class="btn btn-success"

name="update">

<i class="fas fa-save"></i>

Save Changes

</button>

</div>

</form>

</div>

</div>

</div>

<?php

}

}
else
{

?>

<tr>

<td colspan="9" class="text-center py-5">

<i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>

<h4>

No Orders Found

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>