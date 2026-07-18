<?php
include("config/db.php");
include("config/config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Food Categories | <?php echo SITE_NAME; ?></title>

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

.hero h1{

font-size:60px;

font-weight:bold;

}

.hero p{

font-size:20px;

}

.category-card{

border:none;

border-radius:20px;

overflow:hidden;

box-shadow:0 15px 35px rgba(0,0,0,.10);

transition:.35s;

height:100%;

}

.category-card:hover{

transform:translateY(-10px);

}

.category-card img{

height:250px;

object-fit:cover;

}

.food-count{

background:#ffc107;

padding:6px 15px;

border-radius:30px;

font-weight:bold;

display:inline-block;

margin-bottom:15px;

}

.section-title{

font-weight:bold;

}

.feature-box{

background:#fff;

padding:30px;

border-radius:20px;

box-shadow:0 15px 30px rgba(0,0,0,.08);

text-align:center;

height:100%;

transition:.3s;

}

.feature-box:hover{

transform:translateY(-8px);

}

.feature-box i{

font-size:45px;

color:#ffc107;

margin-bottom:20px;

}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<!-- HERO -->

<section class="hero">
</section>

<!-- CATEGORY SECTION -->

<section class="py-5">

<div class="container">

<div class="text-center mb-5">

<h2 class="section-title">

Browse Categories

</h2>

<p class="text-muted">

Choose your favorite category and discover delicious meals.

</p>

</div>

<div class="row g-4">
    <?php

$sql = "SELECT * FROM categories WHERE status='Active' ORDER BY category_name ASC";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

$category_id = $row['id'];

$count = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM foods
WHERE category_id='$category_id'
AND status='Available'
"));

?>

<div class="col-lg-4 col-md-6">

<div class="card category-card">

<img

src="uploads/categories/<?php echo $row['image']; ?>"

class="card-img-top"

onerror="this.src='assets/images/hero.png';">

<div class="card-body text-center">

<div class="food-count">

<?php echo $count['total']; ?>

Meals Available

</div>

<h3 class="mb-3">

<?php echo $row['category_name']; ?>

</h3>

<p class="text-muted">

Freshly prepared meals made with quality ingredients.

</p>

<div class="d-grid">

<a

href="menu.php?category=<?php echo $category_id; ?>"

class="btn btn-warning">

<i class="fas fa-utensils"></i>

View Foods

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

<div class="alert alert-warning text-center p-5">

<h3>

No Categories Found

</h3>

<p>

The restaurant has not added any categories yet.

</p>

</div>

</div>

<?php

}

?>

</div>

</div>

</section>

<!-- WHY CHOOSE US -->

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<h2>

Why Choose Taste Haven?

</h2>

<p class="text-muted">

We serve delicious meals prepared with love and delivered with care.

</p>

</div>

<div class="row">

<div class="col-md-3 mb-4">

<div class="feature-box">

<i class="fas fa-leaf"></i>

<h4>

Fresh Ingredients

</h4>

<p>

Only premium fresh ingredients are used.

</p>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="feature-box">

<i class="fas fa-user-chef"></i>

<h4>

Expert Chefs

</h4>

<p>

Prepared by experienced professional chefs.

</p>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="feature-box">

<i class="fas fa-motorcycle"></i>

<h4>

Fast Delivery

</h4>

<p>

Hot meals delivered quickly to your doorstep.

</p>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="feature-box">

<i class="fas fa-award"></i>

<h4>

Best Quality

</h4>

<p>

Excellent taste and outstanding customer service.

</p>

</div>

</div>

</div>

</div>

</section>

<!-- CALL TO ACTION -->
 <section class="py-5 bg-warning">

<div class="container text-center">

<h2 class="fw-bold mb-3">

Ready To Enjoy Delicious Food?

</h2>

<p class="lead mb-4">

Browse our menu and order your favorite meals today. Freshly prepared and delivered with care.

</p>

<a

href="menu.php"

class="btn btn-dark btn-lg me-3">

<i class="fas fa-utensils"></i>

Browse Menu

</a>

<a

href="contact.php"

class="btn btn-outline-dark btn-lg">

<i class="fas fa-phone"></i>

Contact Us

</a>

</div>

</section>

<!-- FOOTER -->

<?php include("includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>