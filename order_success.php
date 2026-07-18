<?php
session_start();

include("config/db.php");
include("config/config.php");

if(!isset($_GET['transaction']))
{
    header("Location:index.php");
    exit();
}

$transaction=mysqli_real_escape_string($conn,$_GET['transaction']);

$query=mysqli_query($conn,"
SELECT *
FROM orders
WHERE transaction_id='$transaction'
");

if(mysqli_num_rows($query)==0)
{
    header("Location:index.php");
    exit();
}

$order=mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>

Order Successful

</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{

background:#f8f6f2;

}

.hero{

background:url('assets/images/menu-banner.png');

background-repeat:no-repeat;

background-position:center top;

background-size:cover;

height:700px;

position:relative;

overflow:hidden;

}

.hero::after{

content:"";

position:absolute;

left:0;

bottom:-1px;

width:100%;

height:10px;

background:#fff;

border-top-left-radius:50% 100%;

border-top-right-radius:50% 100%;

}

.success-card{

background:#fff;

border-radius:25px;

box-shadow:0 20px 45px rgba(0,0,0,.10);

padding:45px;

margin-top:-8px;

position:relative;

z-index:100;

}

.success-icon{

width:120px;

height:120px;

background:#198754;

border-radius:50%;

display:flex;

align-items:center;

justify-content:center;

margin:auto;

margin-top:-100px;

color:#fff;

font-size:60px;

border:8px solid white;

}

.info-box{

background:#fff8e6;

padding:20px;

border-radius:15px;

margin-bottom:20px;

}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<section class="hero">
</section>

<div class="container mb-5">

<div class="success-card">

<div class="success-icon">

<i class="fas fa-check"></i>

</div>

<div class="text-center mt-4">

<h2 class="fw-bold text-success">

Order Successful

</h2>

<p class="text-muted">

Thank you for choosing Taste Haven Restaurant.

</p>

</div>

<div class="row mt-5">
<div class="col-lg-6 mb-4">

<div class="info-box">

<h4 class="mb-4">

<i class="fas fa-receipt text-warning"></i>

Order Information

</h4>

<table class="table table-borderless">

<tr>

<th width="45%">

Transaction ID

</th>

<td>

<strong class="text-primary">

<?php echo $order['transaction_id']; ?>

</strong>

</td>

</tr>

<tr>

<th>

Customer Name

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

Phone Number

</th>

<td>

<?php echo $order['phone']; ?>

</td>

</tr>

<tr>

<th>

Delivery Address

</th>

<td>

<?php echo $order['address']; ?>

</td>

</tr>

<?php

if(!empty($order['notes']))
{

?>

<tr>

<th>

Order Notes

</th>

<td>

<?php echo nl2br($order['notes']); ?>

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

<div class="col-lg-6 mb-4">

<div class="info-box">

<h4 class="mb-4">

<i class="fas fa-credit-card text-warning"></i>

Payment Details

</h4>

<table class="table table-borderless">

<tr>

<th width="45%">

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

Order Status

</th>

<td>

<span class="badge bg-warning text-dark">

<?php echo $order['order_status']; ?>

</span>

</td>

</tr>

<tr>

<th>

Payment Status

</th>

<td>

<?php

if($order['payment_status']=="Paid")
{

echo "<span class='badge bg-success'>Paid</span>";

}
else
{

echo "<span class='badge bg-danger'>Pending Verification</span>";

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

<hr class="my-5">

<h3 class="text-center mb-4">

Ordered Items

</h3>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

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

$items=mysqli_query($conn,"
SELECT order_items.*,foods.food_name
FROM order_items
LEFT JOIN foods
ON order_items.food_id=foods.id
WHERE order_items.order_id='".$order['id']."'
");

while($item=mysqli_fetch_assoc($items))
{

?>

<tr>

<td>

<?php echo $item['food_name']; ?>

</td>

<td>

<?php echo CURRENCY." ".number_format($item['price'],2); ?>

</td>

<td>

<?php echo $item['quantity']; ?>

</td>

<td>

<strong>

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

<hr>

<div class="alert alert-success">

<h5>

<i class="fas fa-circle-info"></i>

Important Information

</h5>

<p class="mb-2">

Please save your <strong>Transaction ID</strong>. You will need it to track your order.

</p>

<p class="mb-0">

Your order will begin preparation once your Mobile Money payment has been verified.

</p>

</div>

<!-- ACTION BUTTONS -->
 <div class="row mt-4">

<div class="col-lg-3 col-md-6 mb-3">

<div class="d-grid">

<a

href="receipt.php?transaction=<?php echo $order['transaction_id']; ?>"

class="btn btn-primary btn-lg">

<i class="fas fa-print"></i>

Print Receipt

</a>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="d-grid">

<a

href="track_order.php"

class="btn btn-warning btn-lg">

<i class="fas fa-truck"></i>

Track Order

</a>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="d-grid">

<a

href="menu.php"

class="btn btn-success btn-lg">

<i class="fas fa-utensils"></i>

Order Again

</a>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="d-grid">

<a

href="index.php"

class="btn btn-dark btn-lg">

<i class="fas fa-home"></i>

Back Home

</a>

</div>

</div>

</div>

<hr class="my-5">

<div class="text-center mb-5">

<h3 class="fw-bold">

What Happens Next?

</h3>

<p class="text-muted">

Your order will go through the following stages.

</p>

</div>

<div class="row text-center">

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow h-100 p-4">

<i class="fas fa-receipt fa-3x text-warning mb-3"></i>

<h5>

Order Received

</h5>

<p>

Your order has been received successfully.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow h-100 p-4">

<i class="fas fa-user-chef fa-3x text-primary mb-3"></i>

<h5>

Preparing Food

</h5>

<p>

Our chefs begin preparing your delicious meal.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow h-100 p-4">

<i class="fas fa-motorcycle fa-3x text-success mb-3"></i>

<h5>

Out For Delivery

</h5>

<p>

Your order is on the way to your location.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow h-100 p-4">

<i class="fas fa-check-circle fa-3x text-danger mb-3"></i>

<h5>

Delivered

</h5>

<p>

Enjoy your meal. Thank you for choosing Taste Haven.

</p>

</div>

</div>

</div>

<div class="alert alert-warning mt-5">

<h5>

<i class="fas fa-lightbulb"></i>

Reminder

</h5>

<p class="mb-0">

Please keep your <strong>Transaction ID</strong> safe. It serves as your order reference and will be required if you contact support or track your order.

</p>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>