<?php
session_start();

include("config/db.php");
include("config/config.php");

/* Check Food ID */

if(!isset($_GET['id']))
{
    header("Location: menu.php");
    exit();
}

$id = (int)$_GET['id'];

/* Get Food */

$query = mysqli_query($conn,"
SELECT foods.*, categories.category_name
FROM foods
LEFT JOIN categories
ON foods.category_id=categories.id
WHERE foods.id='$id'
AND foods.status='Available'
");

if(mysqli_num_rows($query)==0)
{
    header("Location: menu.php");
    exit();
}

$food=mysqli_fetch_assoc($query);

/* Food Image */

$image="assets/images/menu-banner.png";

if(!empty($food['image']) && file_exists("uploads/foods/".$food['image']))
{
    $image="uploads/foods/".$food['image'];
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

<?php echo $food['food_name']; ?>

</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

<link rel="stylesheet"
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

<div class="container text-center py-5">

<span class="badge bg-warning text-dark px-4 py-2 mb-3">

<i class="fas fa-utensils"></i>

Chef's Recommendation

</span>

<h2 class="fw-bold">

Food Details

</h2>

<p class="text-muted">

Discover everything about this delicious meal, including ingredients, price, and preparation before placing your order.

</p>

</div>

.hero h1{

font-size:55px;

font-weight:bold;

}

.food-card{

border:none;

border-radius:20px;

overflow:hidden;

box-shadow:0 15px 35px rgba(0,0,0,.12);

}

.food-image{

height:500px;

object-fit:cover;

width:100%;

}

.price{

font-size:36px;

font-weight:bold;

color:#dc3545;

}

.rating{

color:#ffc107;

font-size:22px;

}

.info-box{

background:#fff8e6;

padding:20px;

border-radius:15px;

margin-top:20px;

}

.quantity-box{

width:150px;

}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<!-- HERO -->

<section class="hero">
</section>

<!-- FOOD DETAILS -->

<section class="py-5">

<div class="container">

<div class="card food-card">

<div class="row g-0">

<div class="col-lg-6">

<img

src="<?php echo $image; ?>"

class="food-image">

</div>

<div class="col-lg-6">

<div class="p-5">

<span class="badge bg-warning text-dark mb-3">

🍽

<?php echo $food['category_name']; ?>

</span>

<h1 class="fw-bold">

<?php echo $food['food_name']; ?>

</h1>

<div class="rating mb-3">

★★★★★

<small class="text-muted fs-6">

(5.0 Rating)

</small>

</div>

<div class="price mb-3">

<?php echo CURRENCY." ".number_format($food['price'],2); ?>

</div>

<p class="text-muted">

<?php echo nl2br($food['description']); ?>

</p>

<hr>

<div class="row text-center">

<div class="col-4">

<i class="fas fa-clock fa-2x text-warning"></i>

<p class="mt-2">

20-30 mins

</p>

</div>

<div class="col-4">

<i class="fas fa-motorcycle fa-2x text-warning"></i>

<p class="mt-2">

Fast Delivery

</p>

</div>

<div class="col-4">

<i class="fas fa-award fa-2x text-warning"></i>

<p class="mt-2">

Premium Quality

</p>

</div>

</div>

<hr>

<form action="cart.php" method="POST">

<input

type="hidden"

name="food_id"

value="<?php echo $food['id']; ?>">
<div class="mb-4">

<label class="fw-bold mb-2">

Quantity

</label>

<div class="input-group quantity-box">

<button

type="button"

class="btn btn-outline-secondary"

onclick="decreaseQty()">

<i class="fas fa-minus"></i>

</button>

<input

type="number"

id="qty"

name="quantity"

class="form-control text-center"

value="1"

min="1">

<button

type="button"

class="btn btn-outline-secondary"

onclick="increaseQty()">

<i class="fas fa-plus"></i>

</button>

</div>

</div>

<div class="d-grid gap-3">

<button

type="submit"

name="add_to_cart"

class="btn btn-warning btn-lg">

<i class="fas fa-shopping-cart"></i>

Add To Cart

</button>

<a

href="menu.php"

class="btn btn-dark btn-lg">

<i class="fas fa-arrow-left"></i>

Back To Menu

</a>

<button

type="button"

class="btn btn-outline-danger btn-lg">

<i class="far fa-heart"></i>

Add To Favorites

</button>

</div>

</form>

<div class="info-box">

<h4 class="mb-3">

<i class="fas fa-truck text-warning"></i>

Delivery Information

</h4>

<p>

✔ Freshly prepared after your order.

</p>

<p>

✔ Estimated preparation time:

<strong>

20 - 30 Minutes

</strong>

</p>

<p>

✔ Estimated delivery:

<strong>

30 - 45 Minutes

</strong>

</p>

<p>

✔ Secure online ordering.

</p>

<p>

✔ Hot meals delivered to your doorstep.

</p>

</div>

<div class="alert alert-success mt-4">

<h5>

<i class="fas fa-star"></i>

Chef's Recommendation

</h5>

<p class="mb-0">

This is one of our most popular meals. Prepared with premium ingredients and loved by our customers.

</p>

</div>

</div>

</div>

</div>

</div>

</div>

</section>

<!-- RELATED FOODS -->

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

You May Also Like

</h2>

<p class="text-muted">

More delicious meals from the same category.

</p>

</div>

<div class="row">

<?php

$related=mysqli_query($conn,"
SELECT *
FROM foods
WHERE category_id='".$food['category_id']."'
AND id!='".$food['id']."'
AND status='Available'
LIMIT 3
");

if(mysqli_num_rows($related)>0)
{

while($item=mysqli_fetch_assoc($related))
{

$relatedImage="assets/images/logo.png";

if(!empty($item['image']) && file_exists("uploads/foods/".$item['image']))
{

$relatedImage="uploads/foods/".$item['image'];

}

?>

<div class="col-lg-4 mb-4">

<div class="card shadow-lg border-0 rounded-4 h-100">

<img

src="<?php echo $relatedImage; ?>"

class="card-img-top"

style="height:230px;object-fit:cover;">

<div class="card-body text-center">

<h4>

<?php echo $item['food_name']; ?>

</h4>

<div class="text-warning mb-2">

★★★★★

</div>

<h5 class="text-danger">

<?php echo CURRENCY." ".number_format($item['price'],2); ?>

</h5>

<a

href="food_details.php?id=<?php echo $item['id']; ?>"

class="btn btn-warning mt-2">

View Food

</a>

</div>

</div>

</div>

<?php

}

}

else

{

?>

<div class="col-12">

<div class="alert alert-warning text-center">

No related foods available.

</div>

</div>

<?php

}

?>

</div>

</div>

</section>

<!-- WHY CHOOSE US -->
 <section class="py-5">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

Why Choose Taste Haven?

</h2>

<p class="text-muted">

Every meal is carefully prepared to give you the best dining experience.

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

We prepare every meal using carefully selected fresh ingredients.

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

Experienced chefs prepare every dish with passion and care.

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

Your meals are delivered hot and fresh to your doorstep.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow h-100 text-center p-4">

<i class="fas fa-headset fa-3x text-danger mb-3"></i>

<h5>

24/7 Support

</h5>

<p>

Our customer support team is always ready to assist you.

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

Hungry? Order Your Favorite Meal Today!

</h2>

<p class="lead text-dark">

Browse our menu and enjoy delicious meals prepared by our professional chefs.

</p>

</div>

<div class="col-lg-4 text-lg-end">

<a

href="menu.php"

class="btn btn-dark btn-lg me-2">

<i class="fas fa-utensils"></i>

Browse Menu

</a>

<a

href="cart.php"

class="btn btn-outline-dark btn-lg">

<i class="fas fa-shopping-cart"></i>

View Cart

</a>

</div>

</div>

</div>

</section>

<?php include("includes/footer.php"); ?>

<script>

function increaseQty()
{
    let qty=document.getElementById("qty");
    qty.value=parseInt(qty.value)+1;
}

function decreaseQty()
{
    let qty=document.getElementById("qty");

    if(parseInt(qty.value)>1)
    {
        qty.value=parseInt(qty.value)-1;
    }
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>