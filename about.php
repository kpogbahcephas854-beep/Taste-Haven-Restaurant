<?php
include("config/db.php");
include("config/config.php");

/* Restaurant Settings */

$settings_query = mysqli_query($conn,"SELECT * FROM settings LIMIT 1");

$settings = mysqli_fetch_assoc($settings_query);

/* Default Values */

$restaurant_name = !empty($settings['restaurant_name']) ? $settings['restaurant_name'] : "Taste Haven Restaurant";
$phone = !empty($settings['phone']) ? $settings['phone'] : "+250 788 123 456";
$email = !empty($settings['email']) ? $settings['email'] : "info@tastehaven.com";
$address = !empty($settings['address']) ? $settings['address'] : "Kigali, Rwanda";
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>About Us | <?php echo $restaurant_name; ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{

background:#f8f9fa;

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

<div class="container text-center py-5">

<span class="badge bg-warning text-dark px-4 py-2 mb-3">

About Taste Haven

</span>

<h2 class="fw-bold">

Our Story

</h2>

<p class="text-muted">

Discover who we are, our passion for food, and our commitment to serving every customer with excellence.

</p>

</div>

.hero::before{

content:"";

position:absolute;

top:0;

left:0;

width:100%;

height:100%;

background:rgba(0,0,0,.35);

}

.hero-content{

position:relative;

z-index:10;

max-width:900px;

padding:20px;

}

.hero h1{

font-size:60px;

font-weight:700;

}

.hero p{

font-size:22px;

margin-top:15px;

}

.section-title{

font-weight:700;

margin-bottom:20px;

color:#212529;

}

.about-image{

border-radius:20px;

overflow:hidden;

box-shadow:0 15px 35px rgba(0,0,0,.15);

}

.about-image img{

width:100%;

}

.info-card{

background:#fff;

padding:30px;

border-radius:18px;

box-shadow:0 10px 30px rgba(0,0,0,.08);

height:100%;

transition:.3s;

}

.info-card:hover{

transform:translateY(-8px);

}

.icon-circle{

width:80px;

height:80px;

margin:auto;

border-radius:50%;

background:#ffc107;

display:flex;

align-items:center;

justify-content:center;

font-size:35px;

color:#212529;

margin-bottom:20px;

}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<section class="hero">
</section>

<section class="container py-5">

<div class="row align-items-center g-5">

<div class="col-lg-6">

<div class="about-image">

<img src="assets/images/menu-banner.png">

</div>

</div>

<div class="col-lg-6">

<h2 class="section-title">

Our Story

</h2>

<p class="lead">

Welcome to

<strong><?php echo $restaurant_name; ?></strong>,

where every meal is prepared with passion, quality ingredients, and exceptional customer care.

</p>

<p>

Our restaurant was established to provide delicious meals, excellent customer service, and reliable food delivery. Whether you're enjoying breakfast, lunch, dinner, or ordering online, we are committed to making every experience memorable.

</p>

<div class="row mt-4">

<div class="col-md-6 mb-3">

<div class="info-card text-center">

<div class="icon-circle">

<i class="fas fa-bullseye"></i>

</div>

<h4>

Our Mission

</h4>

<p>

To serve fresh, healthy and delicious meals while delivering outstanding customer satisfaction every day.

</p>

</div>

</div>

<div class="col-md-6 mb-3">

<div class="info-card text-center">

<div class="icon-circle">

<i class="fas fa-eye"></i>

</div>

<h4>

Our Vision

</h4>

<p>

To become one of Africa's most trusted restaurants through innovation, quality food and excellent service.

</p>

</div>

</div>

</div>

</div>

</div>

</section>

<section class="container py-5">

<div class="text-center mb-5">

<h2 class="section-title">

Why Customers Love Us

</h2>

<p class="text-muted">

We don't just serve food—we create unforgettable dining experiences.

</p>

</div>

<div class="row g-4">

<div class="col-lg-3 col-md-6">

<div class="info-card text-center">

<div class="icon-circle">

<i class="fas fa-hamburger"></i>

</div>

<h5>Fresh Ingredients</h5>

<p>

Prepared daily using fresh, high-quality ingredients.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="info-card text-center">

<div class="icon-circle">

<i class="fas fa-motorcycle"></i>

</div>

<h5>Fast Delivery</h5>

<p>

Quick delivery to your home or office.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="info-card text-center">

<div class="icon-circle">

<i class="fas fa-user-tie"></i>

</div>

<h5>Professional Chefs</h5>

<p>

Experienced chefs preparing meals with passion.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="info-card text-center">

<div class="icon-circle">

<i class="fas fa-shield-alt"></i>

</div>

<h5>Secure Ordering</h5>

<p>

Safe online ordering with trusted payment methods.

</p>

</div>

</div>

</div>

</section>
<!-- Statistics -->

<section class="py-5 bg-dark text-white">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

Our Achievements

</h2>

<p>

Serving our customers with excellence every single day.

</p>

</div>

<div class="row text-center">

<div class="col-lg-3 col-md-6 mb-4">

<h1 class="display-4 text-warning fw-bold">

1500+

</h1>

<h5>

Happy Customers

</h5>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<h1 class="display-4 text-warning fw-bold">

350+

</h1>

<h5>

Orders Per Day

</h5>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<h1 class="display-4 text-warning fw-bold">

45+

</h1>

<h5>

Delicious Meals

</h5>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<h1 class="display-4 text-warning fw-bold">

12

</h1>

<h5>

Professional Chefs

</h5>

</div>

</div>

</div>

</section>

<!-- Meet Our Team -->

<section class="container py-5">

<div class="text-center mb-5">

<h2 class="section-title">

Meet Our Professional Chefs

</h2>

<p class="text-muted">

Our experienced chefs prepare every meal with passion and love.

</p>

</div>

<div class="row">

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow-lg rounded-4 overflow-hidden">

<img src="assets/images/chef1.jpg"

class="card-img-top"

style="height:330px;object-fit:cover;"

onerror="this.src='assets/images/hero.png';">

<div class="card-body text-center">

<h4>

Chef Michael

</h4>

<p class="text-warning">

Executive Chef

</p>

<p>

Over 15 years of experience preparing world-class dishes.

</p>

</div>

</div>

</div>

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow-lg rounded-4 overflow-hidden">

<img src="assets/images/chef2.jpg"

class="card-img-top"

style="height:330px;object-fit:cover;"

onerror="this.src='assets/images/hero.png';">

<div class="card-body text-center">

<h4>

Chef Sarah

</h4>

<p class="text-warning">

Pastry Chef

</p>

<p>

Creating delicious desserts and bakery products every day.

</p>

</div>

</div>

</div>

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow-lg rounded-4 overflow-hidden">

<img src="assets/images/chef3.jpg"

class="card-img-top"

style="height:330px;object-fit:cover;"

onerror="this.src='assets/images/hero.png';">

<div class="card-body text-center">

<h4>

Chef David

</h4>

<p class="text-warning">

Head Chef

</p>

<p>

Dedicated to providing unforgettable dining experiences.

</p>

</div>

</div>

</div>

</div>

</section>

<!-- Testimonials -->

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<h2 class="section-title">

What Our Customers Say

</h2>

</div>

<div class="row">

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow h-100 rounded-4">

<div class="card-body">

<h2 class="text-warning">

★★★★★

</h2>

<p>

"The meals are always fresh and delicious. Delivery is always on time."

</p>

<hr>

<strong>

James K.

</strong>

</div>

</div>

</div>

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow h-100 rounded-4">

<div class="card-body">

<h2 class="text-warning">

★★★★★

</h2>

<p>

"The customer service is amazing. This is my favorite restaurant."

</p>

<hr>

<strong>

Grace M.

</strong>

</div>

</div>

</div>

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow h-100 rounded-4">

<div class="card-body">

<h2 class="text-warning">

★★★★★

</h2>

<p>

"Affordable prices, delicious meals and professional staff."

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

<!-- Call To Action -->

<section class="py-5 bg-warning">

<div class="container text-center">

<h1 class="fw-bold mb-3">

Ready To Enjoy Great Food?

</h1>

<p class="lead">

Order delicious meals today and experience the Taste Haven difference.

</p>

<a

href="menu.php"

class="btn btn-dark btn-lg px-5">

<i class="fas fa-utensils"></i>

Order Now

</a>

</div>

</section>

<!-- Contact Information -->

<section class="container py-5">

<div class="row">

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow text-center p-4 h-100">

<i class="fas fa-map-marker-alt fa-3x text-warning mb-3"></i>

<h4>

Visit Us

</h4>

<p>

<?php echo $address; ?>

</p>

</div>

</div>

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow text-center p-4 h-100">

<i class="fas fa-phone fa-3x text-warning mb-3"></i>

<h4>

Call Us

</h4>

<p>

<?php echo $phone; ?>

</p>

</div>

</div>

<div class="col-lg-4 mb-4">

<div class="card border-0 shadow text-center p-4 h-100">

<i class="fas fa-envelope fa-3x text-warning mb-3"></i>

<h4>

Email

</h4>

<p>

<?php echo $email; ?>

</p>

</div>

</div>

</div>

</section>

<?php include("includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>