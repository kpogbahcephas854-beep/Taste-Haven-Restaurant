<?php
session_start();

include("config/db.php");
include("config/config.php");

$order=NULL;

if(isset($_GET['transaction']))
{

$transaction=mysqli_real_escape_string($conn,$_GET['transaction']);

$query=mysqli_query($conn,"
SELECT *
FROM orders
WHERE transaction_id='$transaction'
");

if(mysqli_num_rows($query)>0)
{

$order=mysqli_fetch_assoc($query);

}

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<title>

Track Order

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="assets/css/style.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

.track-card{

background:#fff;

padding:40px;

border-radius:20px;

box-shadow:0 20px 45px rgba(0,0,0,.10);

margin-top:-5px;

position:relative;

z-index:100;

}

.status-circle{

width:80px;

height:80px;

border-radius:50%;

background:#dee2e6;

display:flex;

align-items:center;

justify-content:center;

font-size:30px;

margin:auto;

color:#fff;

}

.active{

background:#198754;

}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<section class="hero">
</section>

<div class="container mb-5">

<div class="track-card">

<form method="GET">

<div class="row">

<div class="col-lg-10 mb-3">

<input

type="text"

name="transaction"

class="form-control form-control-lg"

placeholder="Enter Transaction ID"

value="<?php

if(isset($_GET['transaction']))
echo $_GET['transaction'];

?>"

required>

</div>

<div class="col-lg-2">

<div class="d-grid">

<button

class="btn btn-warning btn-lg">

Search

</button>

</div>

</div>

</div>

</form>

<hr>
<?php

if($order)
{

?>

<div class="row mt-5">

<div class="col-lg-6 mb-4">

<div class="card border-0 shadow-lg h-100">

<div class="card-header bg-warning">

<h4 class="mb-0">

<i class="fas fa-user"></i>

Customer Information

</h4>

</div>

<div class="card-body">

<table class="table table-borderless">

<tr>

<th width="40%">

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

<div class="col-lg-6 mb-4">

<div class="card border-0 shadow-lg h-100">

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

<span class="badge bg-primary">

<?php echo $order['order_status']; ?>

</span>

</td>

</tr>

<tr>

<th>

Total Amount

</th>

<td>

<strong class="text-danger">

<?php echo CURRENCY." ".number_format($order['total_amount'],2); ?>

</strong>

</td>

</tr>

</table>

</div>

</div>

</div>

</div>

<!-- ORDER ITEMS -->

<div class="card border-0 shadow-lg mt-4">

<div class="card-header bg-success text-white">

<h4 class="mb-0">

<i class="fas fa-utensils"></i>

Ordered Foods

</h4>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover">

<thead>

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

</div>

</div>

<hr class="my-5">

<h3 class="text-center mb-5">

Order Progress

</h3>

<div class="row text-center">
<?php

$status = $order['order_status'];

$steps = [
    "Pending" => 1,
    "Preparing" => 2,
    "Ready" => 3,
    "Out For Delivery" => 4,
    "Delivered" => 5
];

$current = isset($steps[$status]) ? $steps[$status] : 1;

?>

<div class="col">

<div class="status-circle <?php if($current>=1) echo 'active'; ?>">

<i class="fas fa-receipt"></i>

</div>

<h5 class="mt-3">

Pending

</h5>

</div>

<div class="col">

<div class="status-circle <?php if($current>=2) echo 'active'; ?>">

<i class="fas fa-user-chef"></i>

</div>

<h5 class="mt-3">

Preparing

</h5>

</div>

<div class="col">

<div class="status-circle <?php if($current>=3) echo 'active'; ?>">

<i class="fas fa-check"></i>

</div>

<h5 class="mt-3">

Ready

</h5>

</div>

<div class="col">

<div class="status-circle <?php if($current>=4) echo 'active'; ?>">

<i class="fas fa-motorcycle"></i>

</div>

<h5 class="mt-3">

Out For Delivery

</h5>

</div>

<div class="col">

<div class="status-circle <?php if($current>=5) echo 'active'; ?>">

<i class="fas fa-house"></i>

</div>

<h5 class="mt-3">

Delivered

</h5>

</div>

</div>

<hr class="my-5">

<div class="alert alert-info">

<h5>

<i class="fas fa-circle-info"></i>

Current Order Status

</h5>

<p class="mb-0">

Your order is currently

<strong>

<?php echo $order['order_status']; ?>

</strong>.

You will see the progress update here as our restaurant processes your order.

</p>

</div>

<div class="row mt-4">

<div class="col-lg-4 mb-3">

<div class="d-grid">

<a

href="receipt.php?transaction=<?php echo $order['transaction_id']; ?>"

class="btn btn-primary btn-lg">

<i class="fas fa-print"></i>

Print Receipt

</a>

</div>

</div>

<div class="col-lg-4 mb-3">

<div class="d-grid">

<a

href="menu.php"

class="btn btn-success btn-lg">

<i class="fas fa-utensils"></i>

Order Again

</a>

</div>

</div>

<div class="col-lg-4 mb-3">

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

<?php

}

else

{

?>

<div class="alert alert-danger text-center mt-5">

<i class="fas fa-circle-xmark fa-4x mb-4"></i>

<h3>

Order Not Found

</h3>

<p>

No order was found with the Transaction ID you entered.

Please check the Transaction ID and try again.

</p>

<a

href="track_order.php"

class="btn btn-warning">

Try Again

</a>

</div>

<?php

}

?>

</div>

</div>

<?php include("includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>