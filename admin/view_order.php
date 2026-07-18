<?php
include("includes/auth.php");
include("../config/db.php");
include("../config/config.php");

/* ==========================================
CHECK ORDER ID
========================================== */

if(!isset($_GET['id']))
{
    header("Location:orders.php");
    exit();
}

$id=(int)$_GET['id'];

/* ==========================================
UPDATE ORDER
========================================== */

if(isset($_POST['update']))
{

$payment_status=mysqli_real_escape_string($conn,$_POST['payment_status']);

$order_status=mysqli_real_escape_string($conn,$_POST['order_status']);

mysqli_query($conn,"
UPDATE orders
SET

payment_status='$payment_status',

order_status='$order_status'

WHERE id='$id'
");

header("Location:view_order.php?id=".$id);

exit();

}

/* ==========================================
GET ORDER
========================================== */

$order=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM orders
WHERE id='$id'
"));

if(!$order)
{
    header("Location:orders.php");
    exit();
}

/* ==========================================
GET ORDER ITEMS
========================================== */

$order_items=mysqli_query($conn,"
SELECT

order_items.*,

foods.food_name,

foods.image

FROM order_items

LEFT JOIN foods

ON order_items.food_id=foods.id

WHERE order_items.order_id='$id'
");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

View Order

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

.order-card{

background:#fff;

border:none;

border-radius:20px;

box-shadow:0 15px 40px rgba(0,0,0,.08);

margin-bottom:30px;

}

.card-header{

border-radius:20px 20px 0 0 !important;

}

.food-img{

width:70px;

height:70px;

object-fit:cover;

border-radius:12px;

}

.timeline{

display:flex;

justify-content:space-between;

margin-top:40px;

}

.timeline-step{

text-align:center;

flex:1;

}

.circle{

width:70px;

height:70px;

border-radius:50%;

background:#dee2e6;

display:flex;

align-items:center;

justify-content:center;

margin:auto;

font-size:28px;

color:white;

}

.active{

background:#198754;

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

View Order

</h2>

<p class="text-muted">

Transaction ID:

<strong class="text-primary">

<?php echo $order['transaction_id']; ?>

</strong>

</p>

</div>

<div>

<a

href="orders.php"

class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>

Back To Orders

</a>

</div>

</div>
<div class="row">

<!-- CUSTOMER INFORMATION -->

<div class="col-lg-6">

<div class="card order-card">

<div class="card-header bg-warning">

<h4 class="mb-0">

<i class="fas fa-user"></i>

Customer Information

</h4>

</div>

<div class="card-body">

<table class="table table-borderless">

<tr>

<th width="35%">

Customer

</th>

<td>

<?php echo $order['customer_name']; ?>

</td>

</tr>

<tr>

<th>

Email

</th>

<td>

<?php echo $order['email']; ?>

</td>

</tr>

<tr>

<th>

Phone

</th>

<td>

<?php echo $order['phone']; ?>

</td>

</tr>

<tr>

<th>

Address

</th>

<td>

<?php echo $order['address']; ?>

</td>

</tr>

<tr>

<th>

Customer Notes

</th>

<td>

<?php

if(!empty($order['notes']))
{

echo nl2br($order['notes']);

}
else
{

echo "<span class='text-muted'>No notes provided.</span>";

}

?>

</td>

</tr>

</table>

</div>

</div>

</div>

<!-- PAYMENT INFORMATION -->

<div class="col-lg-6">

<div class="card order-card">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-credit-card"></i>

Payment Information

</h4>

</div>

<div class="card-body">

<table class="table table-borderless">

<tr>

<th width="40%">

Payment Method

</th>

<td>

<?php echo $order['payment_method']; ?>

</td>

</tr>

<tr>

<th>

Payment Phone

</th>

<td>

<?php echo $order['payment_phone']; ?>

</td>

</tr>

<tr>

<th>

Grand Total

</th>

<td>

<strong class="text-danger fs-5">

<?php echo CURRENCY." ".number_format($order['total_amount'],2); ?>

</strong>

</td>

</tr>

<tr>

<th>

Payment Status

</th>

<td>

<?php

if($order['payment_status']=="Pending")
{

echo "<span class='badge bg-warning text-dark'>Pending</span>";

}
elseif($order['payment_status']=="Verified")
{

echo "<span class='badge bg-success'>Verified</span>";

}
else
{

echo "<span class='badge bg-danger'>Rejected</span>";

}

?>

</td>

</tr>

<tr>

<th>

Order Status

</th>

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

</tr>

<tr>

<th>

Order Date

</th>

<td>

<?php echo date("d M Y h:i A",strtotime($order['created_at'])); ?>

</td>

</tr>

</table>

</div>

</div>

</div>

</div>

<!-- ORDER ITEMS -->

<div class="card order-card">

<div class="card-header bg-success text-white">

<h4 class="mb-0">

<i class="fas fa-utensils"></i>

Ordered Food Items

</h4>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>

Image

</th>

<th>

Food

</th>

<th>

Price

</th>

<th>

Quantity

</th>

<th>

Subtotal

</th>

</tr>

</thead>

<tbody>
<?php

while($item=mysqli_fetch_assoc($order_items))
{

$image="../assets/images/logo.png";

if(!empty($item['image']) && file_exists("../uploads/foods/".$item['image']))
{

$image="../uploads/foods/".$item['image'];

}

?>

<tr>

<td>

<img

src="<?php echo $image; ?>"

class="food-img">

</td>

<td>

<strong>

<?php echo $item['food_name']; ?>

</strong>

</td>

<td>

<?php echo CURRENCY." ".number_format($item['price'],2); ?>

</td>

<td>

<span class="badge bg-primary">

<?php echo $item['quantity']; ?>

</span>

</td>

<td>

<strong class="text-danger">

<?php echo CURRENCY." ".number_format($item['subtotal'],2); ?>

</strong>

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

<!-- ORDER PROGRESS -->

<div class="card order-card">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

<i class="fas fa-route"></i>

Order Progress

</h4>

</div>

<div class="card-body">

<?php

$current=1;

switch($order['order_status'])
{

case "Pending":

$current=1;

break;

case "Preparing":

$current=2;

break;

case "Ready":

$current=3;

break;

case "Out For Delivery":

$current=4;

break;

case "Delivered":

$current=5;

break;

}

?>

<div class="timeline">

<div class="timeline-step">

<div class="circle <?php if($current>=1) echo 'active'; ?>">

<i class="fas fa-receipt"></i>

</div>

<h6 class="mt-3">

Pending

</h6>

</div>

<div class="timeline-step">

<div class="circle <?php if($current>=2) echo 'active'; ?>">

<i class="fas fa-user-chef"></i>

</div>

<h6 class="mt-3">

Preparing

</h6>

</div>

<div class="timeline-step">

<div class="circle <?php if($current>=3) echo 'active'; ?>">

<i class="fas fa-bell"></i>

</div>

<h6 class="mt-3">

Ready

</h6>

</div>

<div class="timeline-step">

<div class="circle <?php if($current>=4) echo 'active'; ?>">

<i class="fas fa-motorcycle"></i>

</div>

<h6 class="mt-3">

Out For Delivery

</h6>

</div>

<div class="timeline-step">

<div class="circle <?php if($current>=5) echo 'active'; ?>">

<i class="fas fa-check-circle"></i>

</div>

<h6 class="mt-3">

Delivered

</h6>

</div>

</div>

</div>

</div>

<!-- UPDATE ORDER -->

<div class="card order-card">

<div class="card-header bg-warning">

<h4 class="mb-0">

<i class="fas fa-edit"></i>

Update Order

</h4>

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="fw-bold">

Payment Status

</label>

<select

name="payment_status"

class="form-select">

<option value="Pending" <?php if($order['payment_status']=="Pending") echo "selected"; ?>>

Pending

</option>

<option value="Verified" <?php if($order['payment_status']=="Verified") echo "selected"; ?>>

Verified

</option>

<option value="Rejected" <?php if($order['payment_status']=="Rejected") echo "selected"; ?>>

Rejected

</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Order Status

</label>

<select

name="order_status"

class="form-select">

<option value="Pending" <?php if($order['order_status']=="Pending") echo "selected"; ?>>

Pending

</option>

<option value="Preparing" <?php if($order['order_status']=="Preparing") echo "selected"; ?>>

Preparing

</option>

<option value="Ready" <?php if($order['order_status']=="Ready") echo "selected"; ?>>

Ready

</option>

<option value="Out For Delivery" <?php if($order['order_status']=="Out For Delivery") echo "selected"; ?>>

Out For Delivery

</option>

<option value="Delivered" <?php if($order['order_status']=="Delivered") echo "selected"; ?>>

Delivered

</option>

<option value="Cancelled" <?php if($order['order_status']=="Cancelled") echo "selected"; ?>>

Cancelled

</option>

</select>

</div>

</div>

<div class="row mt-4">
<div class="col-lg-4 mb-3">

<div class="d-grid">

<button

type="submit"

name="update"

class="btn btn-success btn-lg">

<i class="fas fa-save"></i>

Save Changes

</button>

</div>

</div>

<div class="col-lg-4 mb-3">

<div class="d-grid">

<a

href="../receipt.php?transaction=<?php echo $order['transaction_id']; ?>"

target="_blank"

class="btn btn-primary btn-lg">

<i class="fas fa-print"></i>

Print Receipt

</a>

</div>

</div>

<div class="col-lg-4 mb-3">

<div class="d-grid">

<a

href="orders.php"

class="btn btn-secondary btn-lg">

<i class="fas fa-arrow-left"></i>

Back To Orders

</a>

</div>

</div>

</div>

</form>

</div>

</div>

<!-- CONTACT CUSTOMER -->

<div class="card order-card">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="fas fa-address-book"></i>

Customer Contact

</h4>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-4 text-center">

<i class="fas fa-user-circle fa-4x text-primary mb-3"></i>

<h5>

<?php echo $order['customer_name']; ?>

</h5>

</div>

<div class="col-md-8">

<p>

<i class="fas fa-envelope text-warning"></i>

<strong>Email:</strong>

<?php echo $order['email']; ?>

</p>

<p>

<i class="fas fa-phone text-success"></i>

<strong>Phone:</strong>

<?php echo $order['phone']; ?>

</p>

<p>

<i class="fas fa-map-marker-alt text-danger"></i>

<strong>Delivery Address:</strong>

<?php echo $order['address']; ?>

</p>

<?php

if(!empty($order['notes']))
{

?>

<p>

<i class="fas fa-note-sticky text-info"></i>

<strong>Customer Notes:</strong>

<br>

<?php echo nl2br($order['notes']); ?>

</p>

<?php

}

?>

</div>

</div>

</div>

</div>

<!-- ORDER SUMMARY -->

<div class="card order-card">

<div class="card-body text-center">

<h3 class="text-success">

<i class="fas fa-circle-check"></i>

Restaurant Order Summary

</h3>

<p class="text-muted">

Use the buttons above to update the order status, verify payment, or print the customer's receipt.

</p>

<div class="row mt-4">

<div class="col-md-4">

<h5 class="text-primary">

<?php echo $order['transaction_id']; ?>

</h5>

<small class="text-muted">

Transaction ID

</small>

</div>

<div class="col-md-4">

<h5 class="text-danger">

<?php echo CURRENCY." ".number_format($order['total_amount'],2); ?>

</h5>

<small class="text-muted">

Total Amount

</small>

</div>

<div class="col-md-4">

<h5 class="text-success">

<?php echo $order['payment_method']; ?>

</h5>

<small class="text-muted">

Payment Method

</small>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>