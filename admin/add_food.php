<?php
include("includes/auth.php");
include("../config/db.php");

$message="";

if(isset($_POST['save']))
{

    $food_name=mysqli_real_escape_string($conn,$_POST['food_name']);

    $category_id=$_POST['category_id'];

    $description=mysqli_real_escape_string($conn,$_POST['description']);

    $price=$_POST['price'];

    $featured=$_POST['featured'];

    $status=$_POST['status'];

    $image="";

    if(isset($_FILES['image']) && $_FILES['image']['name']!="")
    {

        $image=time()."_".$_FILES['image']['name'];

        $tmp=$_FILES['image']['tmp_name'];

        move_uploaded_file($tmp,"../uploads/foods/".$image);

    }

    $sql="INSERT INTO foods
    (
        category_id,
        food_name,
        description,
        price,
        image,
        featured,
        status
    )

    VALUES

    (
        '$category_id',
        '$food_name',
        '$description',
        '$price',
        '$image',
        '$featured',
        '$status'
    )";

    if(mysqli_query($conn,$sql))
    {

        $message="<div class='alert alert-success'>

        Food Added Successfully.

        </div>";

    }
    else
    {

        $message="<div class='alert alert-danger'>

        Error Adding Food.

        </div>";

    }

}

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Add Food</title>

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

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

Add Food

</h2>

<p class="text-muted">

Create a new restaurant menu item.

</p>

</div>

<a href="foods.php"

class="btn btn-dark">

<i class="fas fa-arrow-left"></i>

Back

</a>

</div>

<?php echo $message; ?>

<div class="card">

<div class="card-body">

<form

method="POST"

enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label>

Food Name

</label>

<input

type="text"

name="food_name"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Category

</label>

<select

name="category_id"

class="form-select"

required>

<option value="">

Select Category

</option>

<?php

$categories=mysqli_query($conn,"
SELECT *
FROM categories
WHERE status='Active'
ORDER BY category_name ASC");

while($cat=mysqli_fetch_assoc($categories))
{

?>

<option

value="<?php echo $cat['id'];?>">

<?php echo $cat['category_name'];?>

</option>

<?php

}

?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>

Price (RWF)

</label>

<input

type="number"

name="price"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Food Image

</label>

<input

type="file"

name="image"

class="form-control"

accept="image/*"

onchange="previewImage(event)">

</div>

<div class="col-12 mb-3">

<label>

Description

</label>

<textarea

name="description"

rows="5"

class="form-control"

required></textarea>

</div>

<div class="col-md-6">

<label>

Featured

</label>

<select

name="featured"

class="form-select">

<option>

Yes

</option>

<option selected>

No

</option>

</select>

</div>

<div class="col-md-6">

<label>

Status

</label>

<select

name="status"

class="form-select">

<option selected>

Available

</option>

<option>

Unavailable

</option>

</select>

</div>

<div class="col-12 mt-4">

<label>

Image Preview

</label>

<br>

<img

id="preview"

src="../assets/images/logo.png"

style="width:220px;
height:220px;
object-fit:cover;
border-radius:12px;
border:1px solid #ddd;">

                    </div>

                </div>

                <hr>

                <div class="text-end">

                    <button
                    type="reset"
                    class="btn btn-secondary">

                        <i class="fas fa-undo"></i>

                        Reset

                    </button>

                    <button
                    type="submit"
                    name="save"
                    class="btn btn-warning">

                        <i class="fas fa-save"></i>

                        Save Food

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</div>

</div>

<script>

function previewImage(event)
{

    var reader = new FileReader();

    reader.onload = function()
    {

        document.getElementById('preview').src = reader.result;

    }

    reader.readAsDataURL(event.target.files[0]);

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>