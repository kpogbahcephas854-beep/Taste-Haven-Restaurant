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

$success = "";

if(isset($_POST['send']))
{

$name = mysqli_real_escape_string($conn,$_POST['name']);

$email_address = mysqli_real_escape_string($conn,$_POST['email']);

$subject = mysqli_real_escape_string($conn,$_POST['subject']);

$message = mysqli_real_escape_string($conn,$_POST['message']);

$success = "Thank you for contacting us. We will respond as soon as possible.";

}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

Contact Us | <?php echo $restaurant_name; ?>

</title>

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

Contact Taste Haven

</span>

<h2 class="fw-bold">

Get In Touch

</h2>

<p class="text-muted">

We'd love to hear from you. Send us a message or contact us using the information below.

</p>

</div>

.hero::before{

content:"";

position:absolute;

width:100%;

height:100%;

top:0;

left:0;

background:rgba(0,0,0,.35);

}

.hero-content{

position:relative;

z-index:10;

}

.hero h1{

font-size:60px;

font-weight:700;

}

.hero p{

font-size:22px;

}

.contact-card{

background:white;

border-radius:20px;

padding:35px;

box-shadow:0 15px 40px rgba(0,0,0,.08);

height:100%;

}

.info-box{

display:flex;

align-items:flex-start;

margin-bottom:30px;

}

.info-icon{

width:65px;

height:65px;

background:#ffc107;

border-radius:50%;

display:flex;

justify-content:center;

align-items:center;

font-size:24px;

margin-right:20px;

color:#000;

flex-shrink:0;

}

.section-title{

font-weight:bold;

margin-bottom:30px;

}

.form-control{

padding:12px;

border-radius:10px;

}

.btn-warning{

padding:12px;

font-weight:bold;

}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<section class="hero">
</section>

<section class="container py-5">

<div class="row g-5">

<div class="col-lg-5">

<div class="contact-card">

<h2 class="section-title">

Restaurant Information

</h2>

<div class="info-box">

<div class="info-icon">

<i class="fas fa-map-marker-alt"></i>

</div>

<div>

<h5>

Address

</h5>

<p>

<?php echo $address; ?>

</p>

</div>

</div>

<div class="info-box">

<div class="info-icon">

<i class="fas fa-phone"></i>

</div>

<div>

<h5>

Phone

</h5>

<p>

<?php echo $phone; ?>

</p>

</div>

</div>

<div class="info-box">

<div class="info-icon">

<i class="fas fa-envelope"></i>

</div>

<div>

<h5>

Email

</h5>

<p>

<?php echo $email; ?>

</p>

</div>

</div>

<div class="info-box">

<div class="info-icon">

<i class="fas fa-clock"></i>

</div>

<div>

<h5>

Opening Hours

</h5>

<p>

Monday - Sunday

<br>

8:00 AM - 10:00 PM

</p>

</div>

</div>

<hr>

<h4>

Follow Us

</h4>

<div class="mt-3">

<a href="#" class="btn btn-warning me-2">

<i class="fab fa-facebook-f"></i>

</a>

<a href="#" class="btn btn-warning me-2">

<i class="fab fa-instagram"></i>

</a>

<a href="#" class="btn btn-warning">

<i class="fab fa-x-twitter"></i>

</a>

</div>

</div>

</div>

<div class="col-lg-7">

<div class="contact-card">

<h2 class="section-title">

Send Us A Message

</h2>

<?php if($success!=""){ ?>

<div class="alert alert-success">

<?php echo $success; ?>

</div>

<?php } ?>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>

Full Name

</label>

<input

type="text"

name="name"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Email Address

</label>

<input

type="email"

name="email"

class="form-control"

required>

</div>

<div class="col-12 mb-3">

<label>

Subject

</label>

<input

type="text"

name="subject"

class="form-control"

required>

</div>

<div class="col-12 mb-4">

<label>

Message

</label>

<textarea

name="message"

rows="6"

class="form-control"

required></textarea>

</div>

<div class="d-grid">

<button

type="submit"

name="send"

class="btn btn-warning btn-lg">

<i class="fas fa-paper-plane"></i>

Send Message

</button>

</div>

</form>

</div>

</div>

</div>

</section>
<!-- Google Map -->

<section class="py-5 bg-white">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

Find Us On The Map

</h2>

<p class="text-muted">

Visit us and enjoy freshly prepared meals in a comfortable environment.

</p>

</div>

<div class="card border-0 shadow-lg rounded-4 overflow-hidden">

<div class="ratio ratio-16x9">

<iframe

src="https://www.google.com/maps?q=Kigali,Rwanda&output=embed"

style="border:0;"

allowfullscreen=""

loading="lazy"

referrerpolicy="no-referrer-when-downgrade">

</iframe>

</div>

</div>

</div>

</section>

<!-- Why Visit Us -->

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

Why Visit Taste Haven?

</h2>

<p class="text-muted">

Everything you need for an unforgettable dining experience.

</p>

</div>

<div class="row g-4">

<div class="col-lg-3 col-md-6">

<div class="contact-card text-center">

<i class="fas fa-utensils fa-3x text-warning mb-3"></i>

<h5>

Delicious Meals

</h5>

<p>

Fresh meals prepared by experienced chefs every day.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="contact-card text-center">

<i class="fas fa-motorcycle fa-3x text-warning mb-3"></i>

<h5>

Fast Delivery

</h5>

<p>

Quick delivery service to your home or office.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="contact-card text-center">

<i class="fas fa-users fa-3x text-warning mb-3"></i>

<h5>

Friendly Staff

</h5>

<p>

Our team is always ready to serve you with a smile.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="contact-card text-center">

<i class="fas fa-award fa-3x text-warning mb-3"></i>

<h5>

Quality Service

</h5>

<p>

We are committed to providing outstanding customer satisfaction.

</p>

</div>

</div>

</div>

</div>

</section>

<!-- Visit Restaurant -->

<section class="py-5">

<div class="container">

<div class="row align-items-center g-5">

<div class="col-lg-6">

<img

src="assets/images/menu-banner.png"

class="img-fluid rounded-4 shadow-lg"

alt="Taste Haven Restaurant">

</div>

<div class="col-lg-6">

<h2 class="fw-bold mb-4">

Visit Our Restaurant

</h2>

<p class="lead">

Whether you're dining in with family, ordering lunch for work, or enjoying dinner at home,

<strong><?php echo $restaurant_name; ?></strong>

is here to serve you with fresh, delicious meals.

</p>

<ul class="list-group list-group-flush">

<li class="list-group-item">

<i class="fas fa-check-circle text-success me-2"></i>

Fresh Ingredients

</li>

<li class="list-group-item">

<i class="fas fa-check-circle text-success me-2"></i>

Professional Chefs

</li>

<li class="list-group-item">

<i class="fas fa-check-circle text-success me-2"></i>

Fast Delivery

</li>

<li class="list-group-item">

<i class="fas fa-check-circle text-success me-2"></i>

Affordable Prices

</li>

</ul>

<div class="mt-4">

<a href="menu.php" class="btn btn-warning btn-lg me-2">

<i class="fas fa-utensils"></i>

Order Now

</a>

<a href="about.php" class="btn btn-dark btn-lg">

<i class="fas fa-info-circle"></i>

About Us

</a>

</div>

</div>

</div>

</div>

</section>

<!-- Call To Action -->

<section class="py-5 text-center text-white"

style="background:#212529;">

<div class="container">

<h1 class="fw-bold">

Ready To Enjoy Delicious Food?

</h1>

<p class="lead mb-4">

Place your order today and experience the Taste Haven difference.

</p>

<a

href="menu.php"

class="btn btn-warning btn-lg px-5">

<i class="fas fa-shopping-cart"></i>

Order Now

</a>

</div>

</section>

<?php include("includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>