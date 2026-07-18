<?php
include("config/db.php");
include("config/config.php");

$search="";

$where="WHERE foods.status='Available'";

if(isset($_GET['search']))
{
    $search=mysqli_real_escape_string($conn,$_GET['search']);

    if($search!="")
    {
        $where.=" AND foods.food_name LIKE '%$search%'";
    }
}

if(isset($_GET['category']))
{
    $category=(int)$_GET['category'];

    if($category>0)
    {
        $where.=" AND foods.category_id='$category'";
    }
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Restaurant Menu | <?php echo SITE_NAME; ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

.hero{

background:url('assets/images/menu-banner.png');

background-repeat:no-repeat;

background-position:center top;

background-size:100% auto;

height:700px;

position:relative;

overflow:hidden;

}
.hero::after{

content:"";

position:absolute;

left:0;

bottom:0;

width:100%;

height:10px;

background:#fff;

clip-path:ellipse(75% 100% at 50% 100%);

}

.hero .container{
    margin-top:-40px;
}

.hero h1{

font-size:70px;

font-weight:800;

letter-spacing:1px;

margin-bottom:15px;

text-shadow:0 5px 20px rgba(0,0,0,.45);

}

.hero p{

font-size:22px;

text-shadow:0 3px 12px rgba(0,0,0,.4);

}

.food-card{

border:none;

border-radius:20px;

overflow:hidden;

box-shadow:0 15px 35px rgba(0,0,0,.10);

transition:.35s;

height:100%;

}

.food-card:hover{

transform:translateY(-10px);

}

.food-card img{

height:250px;

object-fit:cover;

}

.price{

font-size:24px;

font-weight:bold;

color:#dc3545;

}

.badge-chef{

background:#ffc107;

color:#000;

font-size:14px;

padding:8px 15px;

border-radius:30px;

}

.search-box{

background:#fff;

padding:25px;

border-radius:20px;

box-shadow:0 15px 40px rgba(0,0,0,.12);

margin-top:-5px;

position:relative;

z-index:10;

}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<section class="hero">
</section>

<div class="container">

<div class="search-box">

<form method="GET">

<div class="row">

<div class="col-lg-5 mb-3">

<input

type="text"

name="search"

class="form-control form-control-lg"

placeholder="Search food..."

value="<?php echo $search; ?>">

</div>

<div class="col-lg-5 mb-3">

<select

name="category"

class="form-select form-select-lg">

<option value="">

All Categories

</option>

<?php

$cats=mysqli_query($conn,"
SELECT *
FROM categories
WHERE status='Active'
ORDER BY category_name
");

while($cat=mysqli_fetch_assoc($cats))
{

?>

<option

value="<?php echo $cat['id']; ?>"

<?php
if(isset($_GET['category']) && $_GET['category']==$cat['id'])
echo "selected";
?>>

<?php echo $cat['category_name']; ?>

</option>

<?php

}

?>

</select>

</div>

<div class="col-lg-2">

<div class="d-grid">

<button class="btn btn-warning btn-lg">

<i class="fas fa-search"></i>

Search

</button>

</div>

</div>

</div>

</form>

</div>

</div>

<section class="py-5">

<div class="container">

<div class="text-center mb-5">

<span class="badge bg-danger">

Chef's Recommendation

</span>

<h2 class="fw-bold mt-3">

Popular Foods

</h2>

<p class="text-muted">

Discover our freshly prepared meals.

</p>

</div>

<div class="row">
<?php

$sql = "
SELECT foods.*, categories.category_name
FROM foods
LEFT JOIN categories
ON foods.category_id = categories.id
$where
ORDER BY foods.id DESC
";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0)
{

while($food=mysqli_fetch_assoc($result))
{

?>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card food-card">

<img

src="uploads/foods/<?php echo $food['image']; ?>"

class="card-img-top"

alt="<?php echo $food['food_name']; ?>"

onerror="this.src='assets/images/hero.png';">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-2">

<span class="badge-chef">

Chef's Choice

</span>

<span class="badge bg-dark">

<?php echo $food['category_name']; ?>

</span>

</div>

<h4 class="fw-bold">

<?php echo $food['food_name']; ?>

</h4>

<p class="text-muted">

<?php

echo substr($food['description'],0,90);

?>

...

</p>

<div class="mb-3 text-warning">

<i class="fas fa-star"></i>

<i class="fas fa-star"></i>

<i class="fas fa-star"></i>

<i class="fas fa-star"></i>

<i class="fas fa-star"></i>

</div>

<div class="price mb-3">

<?php

echo CURRENCY." ".number_format($food['price'],2);

?>

</div>

<div class="row">

<div class="col-6">

<div class="d-grid">

<a

href="food_details.php?id=<?php echo $food['id']; ?>"

class="btn btn-outline-dark">

<i class="fas fa-eye"></i>

View

</a>

</div>

</div>

<div class="col-6">

<div class="d-grid">

<a

href="add_to_cart.php?id=<?php echo $food['id']; ?>"

class="btn btn-warning">

<i class="fas fa-shopping-cart"></i>

Order

</a>

</div>

</div>

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

<i class="fas fa-utensils fa-4x mb-3"></i>

<h3>

No Foods Found

</h3>

<p>

There are currently no meals matching your search.

</p>

<a

href="menu.php"

class="btn btn-dark">

View Full Menu

</a>

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

<h2 class="fw-bold">

Why Customers Love Taste Haven

</h2>

<p class="text-muted">

Quality food prepared with fresh ingredients every day.

</p>

</div>

<div class="row">

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow text-center p-4 h-100">

<i class="fas fa-leaf fa-3x text-success mb-3"></i>

<h5>

Fresh Ingredients

</h5>

<p>

Only premium fresh ingredients are used.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow text-center p-4 h-100">

<i class="fas fa-user-chef fa-3x text-warning mb-3"></i>

<h5>

Expert Chefs

</h5>

<p>

Prepared by experienced chefs.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow text-center p-4 h-100">

<i class="fas fa-motorcycle fa-3x text-primary mb-3"></i>

<h5>

Fast Delivery

</h5>

<p>

Hot meals delivered quickly.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow text-center p-4 h-100">

<i class="fas fa-award fa-3x text-danger mb-3"></i>

<h5>

Best Quality

</h5>

<p>

Excellent food and outstanding service.

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

Ready To Enjoy Delicious Food?

</h2>

<p class="lead text-dark">

Browse our full menu and place your order today. We prepare every meal with fresh ingredients and deliver it hot to your doorstep.

</p>

</div>

<div class="col-lg-4 text-lg-end">

<a

href="cart.php"

class="btn btn-dark btn-lg me-2">

<i class="fas fa-shopping-cart"></i>

View Cart

</a>

<a

href="contact.php"

class="btn btn-outline-dark btn-lg">

<i class="fas fa-phone"></i>

Contact Us

</a>

</div>

</div>

</div>

</section>

<!-- NEWSLETTER -->

<section class="py-5">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card border-0 shadow-lg rounded-4">

<div class="card-body p-5 text-center">

<h2 class="fw-bold">

Stay Updated

</h2>

<p class="text-muted mb-4">

Subscribe to receive our latest offers, new meals and exclusive discounts.

</p>

<form>

<div class="row">

<div class="col-md-9 mb-3">

<input

type="email"

class="form-control form-control-lg"

placeholder="Enter your email">

</div>

<div class="col-md-3 mb-3">

<div class="d-grid">

<button

type="button"

class="btn btn-warning btn-lg">

Subscribe

</button>

</div>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</section>

<!-- FOOTER -->

<?php include("includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>