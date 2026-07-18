<?php
include("includes/auth.php");
include("../config/db.php");

/* SEARCH */

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);
}

/* PAGINATION */

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1)
{
    $page = 1;
}

$start = ($page-1)*$limit;

/* TOTAL FOODS */

$total_result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM foods");

$total_row = mysqli_fetch_assoc($total_result);

$total_foods = $total_row['total'];

/* FETCH FOODS */

if($search=="")
{

$sql = "SELECT foods.*,categories.category_name
FROM foods

LEFT JOIN categories

ON foods.category_id=categories.id

ORDER BY foods.id DESC

LIMIT $start,$limit";

}
else
{

$sql = "SELECT foods.*,categories.category_name
FROM foods

LEFT JOIN categories

ON foods.category_id=categories.id

WHERE food_name LIKE '%$search%'

ORDER BY foods.id DESC

LIMIT $start,$limit";

}

$result = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Foods</title>

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

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

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2>Food Management</h2>

<p class="text-muted">

Manage all restaurant foods.

</p>

</div>

<a href="add_food.php"
class="btn btn-warning">

<i class="fas fa-plus"></i>

Add Food

</a>

</div>

<div class="row mb-4">

<div class="col-md-4">

<div class="card">

<div class="card-body">

<h6>Total Foods</h6>

<h2 class="text-primary">

<?php echo $total_foods; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-8">

<form method="GET">

<div class="input-group">

<input

type="text"

name="search"

value="<?php echo $search;?>"

class="form-control"

placeholder="Search Food">

<button
class="btn btn-dark">

<i class="fas fa-search"></i>

Search

</button>

</div>

</form>

</div>

</div>

<div class="card">

<div class="card-header">

<h5>

Food List

</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>ID</th>

<th>Image</th>

<th>Food</th>

<th>Category</th>

<th>Price</th>

<th>Featured</th>

<th>Status</th>

<th width="180">

Action

</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result)>0)
{

while($food=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $food['id']; ?></td>

<td>

<?php

if($food['image']=="")
{

?>

<img src="../assets/images/logo.png"

class="table-image">

<?php

}

else

{

?>

<img

src="../uploads/foods/<?php echo $food['image'];?>"

class="table-image">

<?php

}

?>

</td>

<td>

<strong>

<?php echo $food['food_name']; ?>

</strong>

</td>

<td>

<?php echo $food['category_name']; ?>

</td>

<td>

RWF

<?php echo number_format($food['price']); ?>

</td>

<td>

<?php

if($food['featured']=="Yes")
{

    echo "<span class='badge bg-primary'>Yes</span>";

}
else
{

    echo "<span class='badge bg-secondary'>No</span>";

}

?>

</td>

<td>

<?php

if($food['status']=="Available")
{

    echo "<span class='badge bg-success'>Available</span>";

}
else
{

    echo "<span class='badge bg-danger'>Unavailable</span>";

}

?>

</td>

<td>

<a
href="edit_food.php?id=<?php echo $food['id'];?>"
class="btn btn-sm btn-primary">

<i class="fas fa-edit"></i>

</a>

<a
href="delete_food.php?id=<?php echo $food['id'];?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Delete this food?')">

<i class="fas fa-trash"></i>

</a>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="8" class="text-center">

No Foods Found.

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

<?php

/* ===============================
   PAGINATION
================================ */

if($search=="")
{

$count=mysqli_query($conn,"SELECT COUNT(*) total FROM foods");

}
else
{

$count=mysqli_query($conn,"
SELECT COUNT(*) total
FROM foods
WHERE food_name LIKE '%$search%'
");

}

$row=mysqli_fetch_assoc($count);

$total_records=$row['total'];

$total_pages=ceil($total_records/$limit);

?>

<nav class="mt-4">

<ul class="pagination justify-content-center">

<?php

if($page>1)
{

?>

<li class="page-item">

<a class="page-link"

href="?page=<?php echo $page-1;?>&search=<?php echo $search;?>">

Previous

</a>

</li>

<?php

}

for($i=1;$i<=$total_pages;$i++)
{

?>

<li class="page-item <?php if($page==$i) echo "active";?>">

<a
class="page-link"

href="?page=<?php echo $i;?>&search=<?php echo $search;?>">

<?php echo $i;?>

</a>

</li>

<?php

}

if($page<$total_pages)
{

?>

<li class="page-item">

<a
class="page-link"

href="?page=<?php echo $page+1;?>&search=<?php echo $search;?>">

Next

</a>

</li>

<?php

}

?>

</ul>

</nav>

</div>

<div class="admin-footer">

<hr>

<p>

© <?php echo date("Y"); ?>

Taste Haven Restaurant Management System

</p>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>