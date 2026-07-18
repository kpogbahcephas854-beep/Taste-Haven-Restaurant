<?php
include("includes/auth.php");
include("../config/db.php");

/* ==============================
   SEARCH
============================== */

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);
}

/* ==============================
   PAGINATION
============================== */

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1)
{
    $page = 1;
}

$start = ($page-1)*$limit;

/* ==============================
   TOTAL CATEGORIES
============================== */

$total_result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM categories");

$total_row = mysqli_fetch_assoc($total_result);

$total_categories = $total_row['total'];

/* ==============================
   FETCH DATA
============================== */

if($search=="")
{

$sql="SELECT *
FROM categories
ORDER BY id DESC
LIMIT $start,$limit";

}
else
{

$sql="SELECT *
FROM categories
WHERE category_name LIKE '%$search%'
ORDER BY id DESC
LIMIT $start,$limit";

}

$result=mysqli_query($conn,$sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Categories</title>

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
<?php

if(isset($_GET['deleted']))
{

?>

<div class="alert alert-success alert-dismissible fade show">

<i class="fas fa-check-circle"></i>

Category deleted successfully.

<button

type="button"

class="btn-close"

data-bs-dismiss="alert">

</button>

</div>

<?php

}

if(isset($_GET['updated']))
{

?>

<div class="alert alert-success alert-dismissible fade show">

<i class="fas fa-check-circle"></i>

Category updated successfully.

<button

type="button"

class="btn-close"

data-bs-dismiss="alert">

</button>

</div>

<?php

}

?>
<div class="d-flex
justify-content-between
align-items-center
mb-4">

<div>

<h2>

Food Categories

</h2>

<p class="text-muted">

Manage all restaurant food categories.

</p>

</div>

<div>

<a href="add_category.php"
class="btn btn-warning">

<i class="fas fa-plus-circle"></i>

Add Category

</a>

</div>

</div>

<div class="row mb-4">

<div class="col-lg-4">

<div class="card">

<div class="card-body">

<h6>Total Categories</h6>

<h2 class="text-primary">

<?php echo $total_categories; ?>

</h2>

</div>

</div>

</div>

<div class="col-lg-8">

<form method="GET">

<div class="input-group">

<input
type="text"
name="search"
value="<?php echo $search;?>"
class="form-control"
placeholder="Search Category...">

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

Category List

</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>ID</th>

<th>Image</th>

<th>Category</th>

<th>Status</th>

<th>Date Added</th>

<th width="170">

Action

</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<?php echo $row['id']; ?>

</td>

<td>

<?php

if($row['image']=="")
{

?>

<img
src="../assets/images/no-image.png"
class="table-image">

<?php

}
else
{

?>

<img
src="../uploads/categories/<?php echo $row['image'];?>"
class="table-image">

<?php

}

?>

</td>

<td>

<strong>

<?php echo $row['category_name']; ?>

</strong>

</td>

<td>

<?php

if($row['status']=="Active")
{

echo "<span class='badge bg-success'>Active</span>";

}
else
{

echo "<span class='badge bg-danger'>Inactive</span>";

}

?>

</td>

<td>

<?php

echo date(
"d M Y",
strtotime($row['created_at'])
);

?>

</td>

<td>

<a
href="edit_category.php?id=<?php echo $row['id'];?>"
class="btn btn-sm btn-primary">

<i class="fas fa-edit"></i>

</a>

<a
href="delete_category.php?id=<?php echo $row['id'];?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Delete this category?')">

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

<td colspan="6"
class="text-center">

No Categories Found.

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

/* ==============================
   PAGINATION
============================== */

if($search=="")
{

$count_query=mysqli_query($conn,"SELECT COUNT(*) AS total FROM categories");

}
else
{

$count_query=mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM categories
WHERE category_name LIKE '%$search%'
");

}

$count=mysqli_fetch_assoc($count_query);

$total_records=$count['total'];

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

<a class="page-link"

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

<a class="page-link"

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