<?php
include("includes/auth.php");
include("../config/db.php");

if(!isset($_GET['id']))
{
    header("Location:categories.php?updated=1");
    exit();
}

$id=(int)$_GET['id'];

/* ===========================
GET CATEGORY
=========================== */

$query=mysqli_query($conn,"
SELECT *
FROM categories
WHERE id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location:categories.php");
    exit();
}

$row=mysqli_fetch_assoc($query);

/* ===========================
UPDATE CATEGORY
=========================== */

if(isset($_POST['update']))
{

$category_name=mysqli_real_escape_string($conn,$_POST['category_name']);

$status=mysqli_real_escape_string($conn,$_POST['status']);

$image=$row['image'];

/* IMAGE UPLOAD */

if(isset($_FILES['image']) && $_FILES['image']['name']!="")
{

$new_image=time()."_".$_FILES['image']['name'];

move_uploaded_file(

$_FILES['image']['tmp_name'],

"../uploads/categories/".$new_image

);

/* DELETE OLD IMAGE */

if($row['image']!="")
{

$old="../uploads/categories/".$row['image'];

if(file_exists($old))
{

unlink($old);

}

}

$image=$new_image;

}

mysqli_query($conn,"
UPDATE categories
SET

category_name='$category_name',

image='$image',

status='$status'

WHERE id='$id'
");

header("Location:categories.php");

exit();

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

Edit Category

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../assets/css/admin.css">

<link
rel="stylesheet"
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

<h2>

Edit Category

</h2>

<p class="text-muted">

Update food category information.

</p>

</div>

<a
href="categories.php"
class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>

Back

</a>

</div>
<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow border-0">

<div class="card-header bg-warning">

<h4 class="mb-0">

<i class="fas fa-edit"></i>

Edit Food Category

</h4>

</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="text-center mb-4">

<?php

if($row['image']!="")
{

?>

<img

src="../uploads/categories/<?php echo $row['image']; ?>"

class="img-thumbnail"

style="width:180px;height:180px;object-fit:cover;">

<?php

}
else
{

?>

<img

src="../assets/images/no-image.png"

class="img-thumbnail"

style="width:180px;height:180px;object-fit:cover;">

<?php

}

?>

</div>

<div class="mb-3">

<label class="form-label fw-bold">

Category Name

</label>

<input

type="text"

name="category_name"

class="form-control"

value="<?php echo $row['category_name']; ?>"

required>

</div>

<div class="mb-3">

<label class="form-label fw-bold">

Change Category Image

</label>

<input

type="file"

name="image"

class="form-control"

accept="image/*">

<small class="text-muted">

Leave this empty if you don't want to change the image.

</small>

</div>

<div class="mb-4">

<label class="form-label fw-bold">

Status

</label>

<select

name="status"

class="form-select">

<option value="Active"

<?php if($row['status']=="Active") echo "selected"; ?>>

Active

</option>

<option value="Inactive"

<?php if($row['status']=="Inactive") echo "selected"; ?>>

Inactive

</option>

</select>

</div>

<div class="row">

<div class="col-md-6 mb-2">

<div class="d-grid">

<button

type="submit"

name="update"

class="btn btn-success btn-lg">

<i class="fas fa-save"></i>

Update Category

</button>

</div>

</div>

<div class="col-md-6 mb-2">

<div class="d-grid">

<a

href="categories.php"

class="btn btn-secondary btn-lg">

<i class="fas fa-times"></i>

Cancel

</a>

</div>

</div>

</div>

</form>

</div>

</div>

</div>

</div>
</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>