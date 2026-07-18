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
    die("Receipt not found.");
}

$order=mysqli_fetch_assoc($query);

$settings=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM settings
WHERE id='1'
"));

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Receipt

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{

background:#ececec;

font-family:Arial,sans-serif;

}

.receipt{

background:#fff;

max-width:850px;

margin:40px auto;

padding:40px;

box-shadow:0 10px 35px rgba(0,0,0,.15);

border-radius:12px;

}

.logo{

width:90px;

height:90px;

border-radius:50%;

object-fit:cover;

}

.title{

font-size:30px;

font-weight:bold;

}

hr{

border-top:2px dashed #999;

}

@media print{

.no-print{

display:none;

}

body{

background:white;

}

.receipt{

box-shadow:none;

margin:0;

max-width:100%;

}

}

</style>

</head>

<body>

<div class="receipt">

<div class="text-center">

<img

src="assets/images/logo.png"

class="logo">

<h2 class="mt-3">

<?php echo $settings['restaurant_name']; ?>

</h2>

<p>

<?php echo $settings['address']; ?>

<br>

Phone:

<?php echo $settings['phone']; ?>

<br>

Email:

<?php echo $settings['email']; ?>

</p>

</div>

<hr>

<div class="row">

<div class="col-md-6">

<h5>

Customer Information

</h5>

<p>

<strong>Name:</strong>

<?php echo $order['customer_name']; ?>

<br>

<strong>Email:</strong>

<?php echo $order['email']; ?>

<br>

<strong>Phone:</strong>

<?php echo $order['phone']; ?>

<br>

<strong>Address:</strong>

<?php echo $order['address']; ?>

</p>

</div>

<div class="col-md-6 text-md-end">

<h5>

Receipt Information

</h5>

<p>

<strong>

Transaction ID

</strong>

<br>

<?php echo $order['transaction_id']; ?>

<br><br>

<strong>

Date

</strong>

<br>

<?php echo date("d M Y h:i A",strtotime($order['created_at'])); ?>

</p>

</div>

</div>

<hr>

<h4>

Order Items

</h4>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>

Food

</th>

<th>

Price

</th>

<th>

Qty

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

$subtotal=0;

while($item=mysqli_fetch_assoc($items))
{

$subtotal += $item['subtotal'];

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

<?php echo CURRENCY." ".number_format($item['subtotal'],2); ?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<?php

$delivery=0;

if($subtotal<30000)
{

$delivery=3000;

}

$tax=$subtotal*0.05;

$grand_total=$subtotal+$delivery+$tax;

?>

<div class="row mt-5">

<div class="col-md-6">

<h5>

Payment Information

</h5>

<table class="table table-borderless">

<tr>

<th>

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

echo "<span class='badge bg-warning text-dark'>Pending Verification</span>";

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

</table>

</div>

<div class="col-md-6">

<h5>

Payment Summary

</h5>

<table class="table">

<tr>

<th>

Subtotal

</th>

<td class="text-end">

<?php echo CURRENCY." ".number_format($subtotal,2); ?>

</td>

</tr>

<tr>

<th>

Delivery Fee

</th>

<td class="text-end">

<?php

if($delivery==0)
{

echo "<span class='text-success'>FREE</span>";

}
else
{

echo CURRENCY." ".number_format($delivery,2);

}

?>

</td>

</tr>

<tr>

<th>

Tax (5%)

</th>

<td class="text-end">

<?php echo CURRENCY." ".number_format($tax,2); ?>

</td>

</tr>

<tr class="table-warning">

<th>

Grand Total

</th>

<td class="text-end fw-bold">

<?php echo CURRENCY." ".number_format($grand_total,2); ?>

</td>

</tr>

</table>

</div>

</div>

<hr>

<div class="text-center">

<h4>

Thank You For Dining With Us!

</h4>

<p class="text-muted">

We appreciate your order and look forward to serving you again.

</p>

</div>

<!-- ACTION BUTTONS -->
 <hr>

<div class="row mt-4 no-print">

<div class="col-lg-3 col-md-6 mb-3">

<div class="d-grid">

<button

type="button"

onclick="window.print();"

class="btn btn-primary btn-lg">

<i class="fas fa-print"></i>

Print Receipt

</button>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="d-grid">

<a

href="track_order.php?transaction=<?php echo $order['transaction_id']; ?>"

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

<hr>

<div class="text-center mt-5">

<h5 class="fw-bold">

🍽 Taste Haven Restaurant

</h5>

<p class="text-muted mb-1">

Serving Fresh, Delicious Meals Every Day

</p>

<p class="text-muted">

Thank you for choosing us. We look forward to serving you again!

</p>

</div>

</div>

<script>

function autoPrint(){

window.print();

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>