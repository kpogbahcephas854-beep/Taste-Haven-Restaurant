<?php
include("includes/auth.php");
include("../config/db.php");

/* ===========================
   DASHBOARD STATISTICS
=========================== */

$product_count = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM foods"));

$category_count = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM categories"));

$customer_count = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users"));

$order_count = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM orders"));

$sales = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total_amount) AS total FROM orders"));

$total_sales = $sales['total'];

if($total_sales=="")
{
    $total_sales=0;
}

/* Pending Orders */

$pending = mysqli_num_rows(
mysqli_query($conn,"SELECT * FROM orders
WHERE order_status='Pending'")
);

/* Completed Orders */

$completed = mysqli_num_rows(
mysqli_query($conn,"SELECT * FROM orders
WHERE order_status='Completed'")
);

/* Cancelled Orders */

$cancelled = mysqli_num_rows(
mysqli_query($conn,"SELECT * FROM orders
WHERE order_status='Cancelled'")
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="../assets/css/admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="wrapper">

<?php include("includes/sidebar.php"); ?>

<div class="main">

<?php include("includes/header.php"); ?>

<div class="container-fluid p-4">

<div class="row g-4">

<div class="col">

<div class="card dashboard-card bg-primary">

<i class="fas fa-utensils"></i>

<h3><?php echo $product_count; ?></h3>

<p>Foods</p>

</div>

</div>

<div class="col">

<div class="card dashboard-card bg-success">

<i class="fas fa-list"></i>

<h3><?php echo $category_count; ?></h3>

<p>Categories</p>

</div>

</div>

<div class="col">

<div class="card dashboard-card bg-warning">

<i class="fas fa-shopping-cart"></i>

<h3><?php echo $order_count; ?></h3>

<p>Orders</p>

</div>

</div>

<div class="col">

<div class="card dashboard-card bg-danger">

<i class="fas fa-users"></i>

<h3><?php echo $customer_count; ?></h3>

<p>Customers</p>

</div>

</div>

<div class="col">

<div class="card dashboard-card bg-dark">

<i class="fas fa-wallet"></i>

<h3>

RWF <?php echo number_format($total_sales); ?>

</h3>

<p>Total Sales</p>

</div>

</div>

</div>

<br>

<div class="row">

<div class="col-lg-4">

<div class="card">

<div class="card-body">

<h5>

Pending Orders

</h5>

<hr>

<h1 class="text-warning">

<?php echo $pending; ?>

</h1>

</div>

</div>

</div>

<div class="col-lg-4">

<div class="card">

<div class="card-body">

<h5>

Completed Orders

</h5>

<hr>

<h1 class="text-success">

<?php echo $completed; ?>

</h1>

</div>

</div>

</div>

<div class="col-lg-4">

<div class="card">

<div class="card-body">

<h5>

Cancelled Orders

</h5>

<hr>

<h1 class="text-danger">

<?php echo $cancelled; ?>

</h1>

</div>

</div>

</div>

</div>

<br>

<div class="row">

<div class="col-lg-8">

<div class="card">

<div class="card-header">

<h5>

Recent Orders

</h5>

</div>

<div class="card-body">

<table class="table table-hover">

<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Amount</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$recent=mysqli_query($conn,
"SELECT * FROM orders
ORDER BY id DESC
LIMIT 5");

while($row=mysqli_fetch_assoc($recent))
{

?>

<tr>

<td>

#<?php echo $row['id']; ?>

</td>

<td>

User <?php echo $row['user_id']; ?>

</td>

<td>

RWF

<?php echo number_format($row['total_amount']); ?>

</td>

<td>

<?php

$status=$row['order_status'];

if($status=="Completed")

echo "<span class='badge bg-success'>$status</span>";

elseif($status=="Pending")

echo "<span class='badge bg-warning'>$status</span>";

else

echo "<span class='badge bg-danger'>$status</span>";

?>

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

<div class="col-lg-4">

<div class="card">

<div class="card-header">

<h5>

Quick Actions

</h5>

</div>

<div class="card-body">

<a href="add_category.php"
class="btn btn-warning w-100 mb-3">

<i class="fas fa-plus"></i>

Add Category

</a>

<a href="add_food.php"
class="btn btn-primary w-100 mb-3">

<i class="fas fa-hamburger"></i>

Add Food

</a>

<a href="orders.php"
class="btn btn-success w-100">

<i class="fas fa-shopping-bag"></i>

View Orders

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>