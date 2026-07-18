<?php
session_start();

include("config/db.php");
include("config/config.php");

/* ==================================
CHECK IF CART IS EMPTY
================================== */

if(!isset($_SESSION['cart']) || count($_SESSION['cart'])==0)
{
    header("Location: cart.php");
    exit();
}

/* ==================================
CHECK CUSTOMER LOGIN
================================== */

if(!isset($_SESSION['user_id']))
{
    header("Location: customer/login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

$user=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$user_id'
"));

/* ==================================
CALCULATE TOTAL
================================== */

$subtotal=0;

foreach($_SESSION['cart'] as $food_id=>$qty)
{

$food=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM foods
WHERE id='$food_id'
"));

if($food)
{

$subtotal += $food['price']*$qty;

}

}

$delivery=0;

if($subtotal<30000)
{
    $delivery=3000;
}

$tax=$subtotal*0.05;

$grand_total=$subtotal+$delivery+$tax;

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Checkout

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

.hero h1{

font-size:55px;

font-weight:bold;

}

.checkout-card{

background:#fff;

border:none;

border-radius:20px;

padding:35px;

box-shadow:0 20px 40px rgba(0,0,0,.10);

margin-bottom:30px;

}

.summary-card{

background:#fff8e6;

border-radius:20px;

padding:30px;

box-shadow:0 15px 35px rgba(0,0,0,.10);

position:sticky;

top:100px;

}

.payment-box{

background:#f8f9fa;

border:2px solid #dee2e6;

border-radius:15px;

padding:20px;

margin-bottom:15px;

cursor:pointer;

transition:.3s;

}

.payment-box:hover{

border-color:#ffc107;

background:#fff9e6;

}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<!-- HERO -->

<section class="hero">
</section>

<!-- CHECKOUT -->

<section class="py-5">

<div class="container">

<form action="place_order.php" method="POST">

<div class="row">

<div class="col-lg-8">

<div class="checkout-card">

<h3 class="mb-4">

Customer Information

</h3>

<div class="row">

<div class="col-md-6 mb-3">

<label class="fw-bold">

Full Name

</label>

<input

type="text"

name="full_name"

class="form-control"

value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Email Address

</label>

<input

type="email"

name="email"

class="form-control"

value="<?php echo $user['email']; ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Phone Number

</label>

<input

type="text"

name="phone"

class="form-control"

value="<?php echo $user['phone']; ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Delivery Address

</label>

<input

type="text"

name="address"

class="form-control"

placeholder="Enter delivery address"

required>

</div>

<div class="col-12">

<label class="fw-bold">

Order Notes

</label>

<textarea

name="notes"

rows="4"

class="form-control"

placeholder="Example: No onions, extra spicy, deliver after 6 PM (optional)"></textarea>

</div>

</div>

<hr class="my-5">

<h3 class="mb-4">

Choose Payment Method

</h3>
<!-- MTN MOBILE MONEY -->

<label class="payment-box d-block">

<div class="form-check">

<input

class="form-check-input"

type="radio"

name="payment_method"

value="MTN Mobile Money"

checked>

<label class="form-check-label w-100">

<div class="d-flex align-items-center">

<img

src="assets/images/mtn.png"

style="width:55px;height:55px;object-fit:contain;"

class="me-3">

<div>

<h5 class="mb-1">

MTN Mobile Money

</h5>

<small class="text-muted">

Pay securely using MTN MoMo.

</small>

</div>

</div>

</label>

</div>

</label>

<!-- AIRTEL MONEY -->

<label class="payment-box d-block">

<div class="form-check">

<input

class="form-check-input"

type="radio"

name="payment_method"

value="Airtel Money">

<label class="form-check-label w-100">

<div class="d-flex align-items-center">

<img

src="assets/images/airtel.png"

style="width:55px;height:55px;object-fit:contain;"

class="me-3">

<div>

<h5 class="mb-1">

Airtel Money

</h5>

<small class="text-muted">

Pay securely using Airtel Money.

</small>

</div>

</div>

</label>

</div>

</label>

<div class="mt-4">

<label class="fw-bold">

Mobile Money Phone Number

</label>

<input

type="text"

name="payment_phone"

class="form-control"

placeholder="07XXXXXXXX"

required>

<small class="text-muted">

Enter the phone number that will be used to make the payment.

</small>

</div>

</div>

</div>

<!-- ORDER SUMMARY -->

<div class="col-lg-4">

<div class="summary-card">

<h3 class="mb-4">

Order Summary

</h3>

<?php

foreach($_SESSION['cart'] as $food_id=>$qty)
{

$item=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM foods
WHERE id='$food_id'
"));

if(!$item)
{
continue;
}

?>

<div class="d-flex justify-content-between mb-3">

<div>

<strong>

<?php echo $item['food_name']; ?>

</strong>

<br>

<small class="text-muted">

Qty:

<?php echo $qty; ?>

</small>

</div>

<div>

<?php

echo CURRENCY." ".number_format($item['price']*$qty,2);

?>

</div>

</div>

<?php

}

?>

<hr>

<div class="d-flex justify-content-between mb-2">

<span>

Subtotal

</span>

<strong>

<?php echo CURRENCY." ".number_format($subtotal,2); ?>

</strong>

</div>

<div class="d-flex justify-content-between mb-2">

<span>

Delivery

</span>

<strong>

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

</strong>

</div>

<div class="d-flex justify-content-between mb-2">

<span>

Tax (5%)

</span>

<strong>

<?php echo CURRENCY." ".number_format($tax,2); ?>

</strong>

</div>

<hr>

<div class="d-flex justify-content-between">

<h4>

Grand Total

</h4>

<h4 class="text-danger">

<?php echo CURRENCY." ".number_format($grand_total,2); ?>

</h4>

</div>

<div class="alert alert-info mt-4">

<h6>

<i class="fas fa-circle-info"></i>

Payment Information

</h6>

<p class="mb-2">

After clicking <strong>Place Order</strong>, the system will generate a unique <strong>Transaction ID</strong>.

</p>

<p class="mb-0">

Please keep that Transaction ID as your proof of payment and for tracking your order.

</p>

</div>
<div class="d-grid mt-4">

<button

type="submit"

class="btn btn-success btn-lg">

<i class="fas fa-check-circle"></i>

Place Order

</button>

</div>

<hr class="my-4">

<div class="text-center">

<i class="fas fa-lock fa-2x text-success mb-3"></i>

<h5>

100% Secure Checkout

</h5>

<p class="text-muted">

Your personal information is protected, and your order details are securely processed.

</p>

</div>

</div>

</div>

</form>

</div>

</section>

<!-- WHY CHOOSE US -->

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

Why Order From Taste Haven?

</h2>

<p class="text-muted">

We are committed to providing delicious meals and excellent customer service.

</p>

</div>

<div class="row">

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow h-100 text-center p-4">

<i class="fas fa-leaf fa-3x text-success mb-3"></i>

<h5>

Fresh Ingredients

</h5>

<p>

Every meal is prepared using fresh and carefully selected ingredients.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow h-100 text-center p-4">

<i class="fas fa-user-chef fa-3x text-warning mb-3"></i>

<h5>

Professional Chefs

</h5>

<p>

Experienced chefs prepare every order with care and passion.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow h-100 text-center p-4">

<i class="fas fa-motorcycle fa-3x text-primary mb-3"></i>

<h5>

Fast Delivery

</h5>

<p>

Hot and delicious meals delivered quickly to your location.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow h-100 text-center p-4">

<i class="fas fa-headset fa-3x text-danger mb-3"></i>

<h5>

24/7 Customer Support

</h5>

<p>

Our support team is always available to assist you.

</p>

</div>

</div>

</div>

</div>

</section>

<?php include("includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>