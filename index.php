<?php
include("config/db.php");
include("config/config.php");

/* Restaurant Settings */

$settings_query = mysqli_query($conn,"SELECT * FROM settings LIMIT 1");
$settings = mysqli_fetch_assoc($settings_query);

$restaurant_name = !empty($settings['restaurant_name']) ? $settings['restaurant_name'] : "Taste Haven Restaurant";
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo SITE_NAME; ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

.hero{

padding:90px 0;

background:linear-gradient(135deg,#fff8ef,#fff3dc);

overflow:hidden;

}

.hero h1{

font-size:58px;

font-weight:700;

color:#222;

line-height:1.2;

}

.hero p{

font-size:20px;

color:#555;

margin:25px 0;

}

.hero-image{

border-radius:25px;

box-shadow:0 25px 50px rgba(0,0,0,.18);

transition:.4s;

}

.hero-image:hover{

transform:scale(1.03);

}

.hero-btn{

padding:14px 32px;

font-weight:bold;

border-radius:50px;

}

.feature-card{

background:#fff;

padding:35px;

border-radius:20px;

text-align:center;

box-shadow:0 15px 35px rgba(0,0,0,.08);

transition:.3s;

height:100%;

}

.feature-card:hover{

transform:translateY(-10px);

}

.feature-icon{

width:85px;

height:85px;

background:#ffc107;

border-radius:50%;

display:flex;

align-items:center;

justify-content:center;

margin:auto;

margin-bottom:20px;

font-size:34px;

color:#212529;

}

.section-title{

font-weight:700;

margin-bottom:15px;

}

.section-subtitle{

color:#666;

margin-bottom:50px;

}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<!-- HERO -->

<section class="hero">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-6">

<span class="badge bg-warning text-dark fs-6 mb-3">

🍽 Welcome To <?php echo $restaurant_name; ?>

</span>

<h1>

Fresh Food

Delivered

To Your Door

</h1>

<p>

Experience delicious meals prepared with fresh ingredients by professional chefs and delivered quickly to your doorstep.

</p>

<div class="mt-4">

<a href="menu.php"

class="btn btn-warning hero-btn me-3">

<i class="fas fa-utensils"></i>

Order Now

</a>

<a href="menu.php"

class="btn btn-dark hero-btn">

<i class="fas fa-book-open"></i>

View Menu

</a>

</div>

<div class="row mt-5">

<div class="col-4">

<h3 class="text-warning fw-bold">

1500+

</h3>

<small>

Happy Customers

</small>

</div>

<div class="col-4">

<h3 class="text-warning fw-bold">

45+

</h3>

<small>

Food Items

</small>

</div>

<div class="col-4">

<h3 class="text-warning fw-bold">

12

</h3>

<small>

Expert Chefs

</small>

</div>

</div>

</div>

<div class="col-lg-6 text-center">

<img src="assets/images/menu-banner.png"

class="img-fluid hero-image">

</div>

</div>

</div>

</section>

<!-- WHY CHOOSE US -->

<section class="py-5">

<div class="container">

<div class="text-center mb-5">

<h2 class="section-title">

Why Choose Taste Haven?

</h2>

<p class="section-subtitle">

We serve more than food—we deliver an unforgettable dining experience.

</p>

</div>

<div class="row g-4">

<div class="col-lg-3 col-md-6">

<div class="feature-card">

<div class="feature-icon">

<i class="fas fa-leaf"></i>

</div>

<h5>

Fresh Ingredients

</h5>

<p>

Only carefully selected fresh ingredients are used in every meal.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="feature-card">

<div class="feature-icon">

<i class="fas fa-user-chef"></i>

</div>

<h5>

Expert Chefs

</h5>

<p>

Professional chefs prepare every meal with passion and care.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="feature-card">

<div class="feature-icon">

<i class="fas fa-motorcycle"></i>

</div>

<h5>

Fast Delivery

</h5>

<p>

Quick and reliable delivery directly to your home or office.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="feature-card">

<div class="feature-icon">

<i class="fas fa-award"></i>

</div>

<h5>

Best Quality

</h5>

<p>

Every order is prepared to the highest quality standards.

</p>

</div>

</div>

</div>

</div>

</section>

<!-- FOOD CATEGORIES START HERE -->
 <!-- FOOD CATEGORIES -->

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<span class="badge bg-warning text-dark mb-3">

Browse Categories

</span>

<h2 class="section-title">

Choose Your Favorite Category

</h2>

<p class="section-subtitle">

Explore our delicious menu prepared fresh every day.

</p>

</div>

<div class="row g-4">

<?php

$sql="SELECT * FROM categories WHERE status='Active' LIMIT 9";

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<div class="col-lg-4 col-md-6">

<div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">

<img

src="uploads/categories/<?php echo $row['image']; ?>"

class="card-img-top"

style="height:230px;object-fit:cover;"

onerror="this.src='assets/images/menu-banner.png';">

<div class="card-body text-center">

<h4 class="fw-bold">

<?php echo $row['category_name']; ?>

</h4>

<p class="text-muted">

Fresh meals prepared with quality ingredients.

</p>

<a

href="menu.php?category=<?php echo $row['id']; ?>"

class="btn btn-warning rounded-pill">

View Foods

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

No food categories available.

</div>

</div>

<?php

}

?>

</div>

</div>

</section>

<!-- POPULAR FOODS -->

<section class="py-5">

<div class="container">

<div class="text-center mb-5">

<span class="badge bg-danger">

Chef's Recommendation

</span>

<h2 class="section-title mt-3">

Popular Foods

</h2>

<p class="section-subtitle">

Our customers' favorite meals.

</p>

</div>

<div class="row g-4">

<?php

$sql="SELECT * FROM foods WHERE status='Available' LIMIT 9";

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0)
{

while($food=mysqli_fetch_assoc($result))
{

?>

<div class="col-lg-4 col-md-6">

<div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">

<div class="position-relative">

<img

src="uploads/foods/<?php echo $food['image']; ?>"

class="card-img-top"

style="height:250px;object-fit:cover;"

onerror="this.src='assets/images/menu-banner.png';">

<span

class="badge bg-warning text-dark position-absolute top-0 end-0 m-3">

Popular

</span>

</div>

<div class="card-body">

<h4 class="fw-bold">

<?php echo $food['food_name']; ?>

</h4>

<p class="text-muted">

<?php echo substr($food['description'],0,90); ?>...

</p>

<div class="mb-3 text-warning">

★★★★★

</div>

<div class="d-flex justify-content-between align-items-center">

<h4 class="text-danger mb-0">

<?php echo CURRENCY." ".number_format($food['price'],2); ?>

</h4>

</div>

<div class="d-grid mt-3">

<a

href="food_details.php?id=<?php echo $food['id']; ?>"

class="btn btn-dark">

<i class="fas fa-eye"></i>

View Details

</a>

</div>

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

No food items available.

</div>

</div>

<?php

}

?>

</div>

</div>

</section>

<!-- STATISTICS START HERE -->
 <!-- RESTAURANT STATISTICS -->

<section class="py-5 bg-dark text-white">

<div class="container">

<div class="text-center mb-5">

<span class="badge bg-warning text-dark">

Taste Haven

</span>

<h2 class="mt-3 fw-bold">

Serving Thousands Of Happy Customers

</h2>

<p class="text-light">

Fresh meals prepared every day with quality ingredients.

</p>

</div>

<div class="row text-center">

<div class="col-lg-3 col-md-6 mb-4">

<i class="fas fa-users fa-3x text-warning mb-3"></i>

<h1 class="fw-bold">

1500+

</h1>

<p>

Happy Customers

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="fas fa-utensils fa-3x text-warning mb-3"></i>

<h1 class="fw-bold">

45+

</h1>

<p>

Delicious Meals

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="fas fa-motorcycle fa-3x text-warning mb-3"></i>

<h1 class="fw-bold">

300+

</h1>

<p>

Orders Delivered

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="fas fa-user-chef fa-3x text-warning mb-3"></i>

<h1 class="fw-bold">

12

</h1>

<p>

Professional Chefs

</p>

</div>

</div>

</div>

</section>

<!-- TESTIMONIALS -->

<section class="py-5">

<div class="container">

<div class="text-center mb-5">

<span class="badge bg-warning text-dark">

Testimonials

</span>

<h2 class="fw-bold mt-3">

What Our Customers Say

</h2>

</div>

<div class="row">

<div class="col-lg-4 mb-4">

<div class="card shadow border-0 rounded-4 h-100">

<div class="card-body">

<div class="text-warning mb-3">

★★★★★

</div>

<p>

"The food is always fresh and delicious. Delivery is incredibly fast."

</p>

<hr>

<strong>

James K.

</strong>

</div>

</div>

</div>

<div class="col-lg-4 mb-4">

<div class="card shadow border-0 rounded-4 h-100">

<div class="card-body">

<div class="text-warning mb-3">

★★★★★

</div>

<p>

"Excellent customer service and affordable prices. Highly recommended."

</p>

<hr>

<strong>

Grace M.

</strong>

</div>

</div>

</div>

<div class="col-lg-4 mb-4">

<div class="card shadow border-0 rounded-4 h-100">

<div class="card-body">

<div class="text-warning mb-3">

★★★★★

</div>

<p>

"The best restaurant in Kigali. Every meal tastes amazing."

</p>

<hr>

<strong>

Daniel T.

</strong>

</div>

</div>

</div>

</div>

</div>

</section>

<!-- CALL TO ACTION -->

<section class="py-5 bg-warning">

<div class="container text-center">

<h2 class="fw-bold">

Ready To Enjoy Delicious Food?

</h2>

<p class="lead">

Browse our menu and place your order today.

</p>

<div class="mt-4">

<a href="menu.php"

class="btn btn-dark btn-lg me-3">

<i class="fas fa-utensils"></i>

Order Now

</a>

<a href="contact.php"

class="btn btn-outline-dark btn-lg">

<i class="fas fa-phone"></i>

Contact Us

</a>

</div>

</div>

</section>

<?php include("includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>