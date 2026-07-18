<?php
session_start();

include("config/db.php");
include("config/config.php");

/* ==============================
   CREATE CART SESSION
============================== */

if(!isset($_SESSION['cart']))
{
    $_SESSION['cart'] = [];
}

/* ==============================
   ADD TO CART
============================== */

if(isset($_POST['add_to_cart']))
{

    $food_id = (int)$_POST['food_id'];

    $quantity = (int)$_POST['quantity'];

    if($quantity < 1)
    {
        $quantity = 1;
    }

    if(isset($_SESSION['cart'][$food_id]))
    {
        $_SESSION['cart'][$food_id] += $quantity;
    }
    else
    {
        $_SESSION['cart'][$food_id] = $quantity;
    }

    header("Location: cart.php");
    exit();

}

/* ==============================
   REMOVE ITEM
============================== */

if(isset($_GET['remove']))
{

    $id = (int)$_GET['remove'];

    unset($_SESSION['cart'][$id]);

    header("Location: cart.php");
    exit();

}

/* ==============================
   UPDATE CART
============================== */

if(isset($_POST['update_cart']))
{

    foreach($_POST['qty'] as $id=>$qty)
    {

        $qty = (int)$qty;

        if($qty <= 0)
        {
            unset($_SESSION['cart'][$id]);
        }
        else
        {
            $_SESSION['cart'][$id] = $qty;
        }

    }

}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

Shopping Cart

</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

height:5px;

background:#fff;

border-top-left-radius:50% 100%;

border-top-right-radius:50% 100%;

}

<div class="container text-center py-5">

<span class="badge bg-warning text-dark px-4 py-2 mb-3">

Shopping Cart

</span>

<h2 class="fw-bold">

Your Cart

</h2>

<p class="text-muted">

Review your selected meals before proceeding to checkout.

</p>

</div>

.hero h1{

font-size:55px;

font-weight:bold;

}

.cart-card{

background:#fff;

border:none;

border-radius:20px;

box-shadow:0 20px 45px rgba(0,0,0,.12);

padding:30px;

}

.food-image{

width:90px;

height:90px;

object-fit:cover;

border-radius:15px;

}

.summary-card{

background:#fff8e6;

border-radius:20px;

padding:30px;

box-shadow:0 15px 35px rgba(0,0,0,.10);

position:sticky;

top:100px;

}

.quantity{

width:80px;

text-align:center;

}

.table th{

vertical-align:middle;

}

.table td{

vertical-align:middle;

}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<!-- HERO -->

<section class="hero">
</section>

<!-- CART -->

<section class="py-5">

<div class="container">

<div class="row">

<div class="col-lg-8">

<div class="cart-card">

<h3 class="mb-4">

<i class="fas fa-shopping-cart text-warning"></i>

Your Cart

</h3>

<form method="POST">

<div class="table-responsive">

<table class="table align-middle">

<thead class="table-dark">

<tr>

<th>Image</th>

<th>Food</th>

<th>Price</th>

<th width="120">

Quantity

</th>

<th>Total</th>

<th>

Remove

</th>

</tr>

</thead>

<tbody>
<?php

$grand_total = 0;

if(count($_SESSION['cart']) > 0)
{

foreach($_SESSION['cart'] as $food_id=>$qty)
{

$query=mysqli_query($conn,"
SELECT foods.*,categories.category_name
FROM foods
LEFT JOIN categories
ON foods.category_id=categories.id
WHERE foods.id='$food_id'
");

if(mysqli_num_rows($query)==0)
{
    continue;
}

$food=mysqli_fetch_assoc($query);

$image="assets/images/logo.png";

if(!empty($food['image']) && file_exists("uploads/foods/".$food['image']))
{
    $image="uploads/foods/".$food['image'];
}

$total=$food['price']*$qty;

$grand_total+=$total;

?>

<tr>

<td>

<img

src="<?php echo $image; ?>"

class="food-image">

</td>

<td>

<h5 class="mb-1">

<?php echo $food['food_name']; ?>

</h5>

<span class="badge bg-warning text-dark">

<?php echo $food['category_name']; ?>

</span>

</td>

<td>

<strong class="text-danger">

<?php echo CURRENCY." ".number_format($food['price'],2); ?>

</strong>

</td>

<td>

<input

type="number"

name="qty[<?php echo $food['id']; ?>]"

class="form-control quantity"

value="<?php echo $qty; ?>"

min="1">

</td>

<td>

<strong>

<?php echo CURRENCY." ".number_format($total,2); ?>

</strong>

</td>

<td>

<a

href="cart.php?remove=<?php echo $food['id']; ?>"

class="btn btn-danger"

onclick="return confirm('Remove this item from your cart?')">

<i class="fas fa-trash"></i>

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<div class="mt-4 d-flex justify-content-between flex-wrap">

<a

href="menu.php"

class="btn btn-secondary btn-lg mb-2">

<i class="fas fa-arrow-left"></i>

Continue Shopping

</a>

<button

type="submit"

name="update_cart"

class="btn btn-primary btn-lg mb-2">

<i class="fas fa-sync"></i>

Update Cart

</button>

</div>

</form>

</div>

</div>

<!-- ORDER SUMMARY -->

<div class="col-lg-4">

<div class="summary-card">

<h3 class="mb-4">

Order Summary

</h3>

<?php

$delivery=0;

if($grand_total<30000 && $grand_total>0)
{
    $delivery=3000;
}

$tax=$grand_total*0.05;

$discount=0;

$final_total=$grand_total+$delivery+$tax-$discount;

?>

<div class="d-flex justify-content-between mb-3">

<span>

Subtotal

</span>

<strong>

<?php echo CURRENCY." ".number_format($grand_total,2); ?>

</strong>

</div>

<div class="d-flex justify-content-between mb-3">

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

<div class="d-flex justify-content-between mb-3">

<span>

Tax (5%)

</span>

<strong>

<?php echo CURRENCY." ".number_format($tax,2); ?>

</strong>

</div>

<div class="d-flex justify-content-between mb-3">

<span>

Discount

</span>

<strong>

<?php echo CURRENCY." ".number_format($discount,2); ?>

</strong>

</div>

<hr>

<div class="d-flex justify-content-between">

<h4>

Grand Total

</h4>

<h4 class="text-danger">

<?php echo CURRENCY." ".number_format($final_total,2); ?>

</h4>

</div>

<div class="d-grid mt-4">

<a

href="checkout.php"

class="btn btn-success btn-lg">

<i class="fas fa-credit-card"></i>

Proceed To Checkout

</a>

</div>

</div>

</div>

</div>

<?php

}

else

{

?>

<div class="text-center py-5">

<i class="fas fa-shopping-cart fa-5x text-warning mb-4"></i>

<h2>

Your Cart Is Empty

</h2>

<p class="text-muted">

Looks like you haven't added any delicious meals yet.

</p>

<a

href="menu.php"

class="btn btn-warning btn-lg mt-3">

<i class="fas fa-utensils"></i>

Browse Menu

</a>

</div>

<?php

}

?>

</div>

</section>

<!-- DELIVERY INFORMATION -->
 <section class="py-5 bg-light">

<div class="container">

<div class="row">

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow-lg h-100">

<div class="card-body text-center">

<i class="fas fa-motorcycle fa-3x text-warning mb-3"></i>

<h4>

Fast Delivery

</h4>

<p>

Your meals are prepared fresh and delivered within

<strong>30 - 45 Minutes</strong>.

</p>

</div>

</div>

</div>

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow-lg h-100">

<div class="card-body text-center">

<i class="fas fa-shield-alt fa-3x text-success mb-3"></i>

<h4>

Secure Payment

</h4>

<p>

Your payment information is protected with secure checkout.

</p>

</div>

</div>

</div>

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow-lg h-100">

<div class="card-body text-center">

<i class="fas fa-headset fa-3x text-primary mb-3"></i>

<h4>

Customer Support

</h4>

<p>

Need help? Our support team is available whenever you need us.

</p>

</div>

</div>

</div>

</div>

</div>

</section>

<!-- PROMO CODE -->

<section class="py-5">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-6">

<div class="card border-0 shadow-lg rounded-4">

<div class="card-body p-4">

<h3 class="text-center mb-4">

<i class="fas fa-tags text-warning"></i>

Promo Code

</h3>

<form>

<div class="input-group">

<input

type="text"

class="form-control form-control-lg"

placeholder="Enter promo code">

<button

class="btn btn-warning btn-lg"

type="button">

Apply

</button>

</div>

<small class="text-muted">

Promo code functionality can be added later.

</small>

</form>

</div>

</div>

</div>

</div>

</div>

</section>

<!-- WHY CHOOSE US -->

<section class="py-5">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

Why Order From Taste Haven?

</h2>

<p class="text-muted">

Fresh meals, fast delivery, and excellent customer service.

</p>

</div>

<div class="row">

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow text-center h-100 p-4">

<i class="fas fa-leaf fa-3x text-success mb-3"></i>

<h5>

Fresh Ingredients

</h5>

<p>

Prepared daily using fresh, premium ingredients.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow text-center h-100 p-4">

<i class="fas fa-user-chef fa-3x text-warning mb-3"></i>

<h5>

Professional Chefs

</h5>

<p>

Every meal is prepared by experienced chefs.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow text-center h-100 p-4">

<i class="fas fa-clock fa-3x text-danger mb-3"></i>

<h5>

Quick Service

</h5>

<p>

Fast preparation and timely delivery.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow text-center h-100 p-4">

<i class="fas fa-award fa-3x text-primary mb-3"></i>

<h5>

Quality Guaranteed

</h5>

<p>

We are committed to serving meals of the highest quality.

</p>

</div>

</div>

</div>

</div>

</section>

<!-- CALL TO ACTION -->

<section class="py-5 bg-warning">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-8">

<h2 class="fw-bold text-dark">

Still Hungry?

</h2>

<p class="lead text-dark">

Browse our menu and discover more delicious meals prepared just for you.

</p>

</div>

<div class="col-lg-4 text-lg-end">

<a

href="menu.php"

class="btn btn-dark btn-lg">

<i class="fas fa-utensils"></i>

Browse Menu

</a>

</div>

</div>

</div>

</section>

<?php include("includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>