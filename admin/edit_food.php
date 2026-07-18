<?php
include("includes/auth.php");
include("../config/db.php");

if(!isset($_GET['id']))
{
    header("Location: foods.php");
    exit();
}

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"
SELECT *
FROM foods
WHERE id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location: foods.php");
    exit();
}

$food = mysqli_fetch_assoc($query);

$message="";

if(isset($_POST['update']))
{

    $food_name = mysqli_real_escape_string($conn,$_POST['food_name']);

    $category_id = $_POST['category_id'];

    $description = mysqli_real_escape_string($conn,$_POST['description']);

    $price = $_POST['price'];

    $featured = $_POST['featured'];

    $status = $_POST['status'];

    $image = $food['image'];

    if(isset($_FILES['image']) && $_FILES['image']['name']!="")
    {

        if($image!="" && file_exists("../uploads/foods/".$image))
        {
            unlink("../uploads/foods/".$image);
        }

        $image = time()."_".$_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../uploads/foods/".$image
        );

    }

    $update = mysqli_query($conn,"
    UPDATE foods SET

    category_id='$category_id',

    food_name='$food_name',

    description='$description',

    price='$price',

    image='$image',

    featured='$featured',

    status='$status'

    WHERE id='$id'
    ");

    if($update)
    {
        header("Location: foods.php?updated=1");
        exit();
    }
    else
    {
        $message="<div class='alert alert-danger'>
        Failed to update food.
        </div>";
    }

}

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Edit Food</title>

<meta name="viewport"
content="width=device-width,initial-scale=1">

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

<h2>Edit Food</h2>

<p class="text-muted">

Update restaurant food information.

</p>

</div>

<a
href="foods.php"
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

value="<?php echo $food['food_name'];?>"

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

<?php

$cats=mysqli_query($conn,"
SELECT *
FROM categories
WHERE status='Active'
ORDER BY category_name");

while($cat=mysqli_fetch_assoc($cats))
{

?>

<option

value="<?php echo $cat['id'];?>"

<?php

if($food['category_id']==$cat['id'])
echo "selected";

?>

>

<?php echo $cat['category_name'];?>

</option>

<?php

}

?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>

Price

</label>

<input

type="number"

name="price"

value="<?php echo $food['price'];?>"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Replace Image

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

required><?php echo $food['description']; ?></textarea>

</div>

<div class="col-md-6 mb-3">

<label>

Featured

</label>

<select

name="featured"

class="form-select">

<option value="Yes"

<?php if($food['featured']=="Yes") echo "selected"; ?>>

Yes

</option>

<option value="No"

<?php if($food['featured']=="No") echo "selected"; ?>>

No

</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>

Status

</label>

<select

name="status"

class="form-select">

<option value="Available"

<?php if($food['status']=="Available") echo "selected"; ?>>

Available

</option>

<option value="Unavailable"

<?php if($food['status']=="Unavailable") echo "selected"; ?>>

Unavailable

</option>

</select>

</div>

<div class="col-12 mb-4">

<label>

Current Image

</label>

<br><br>

<?php

if($food['image']=="")
{

?>

<img

id="preview"

src="../assets/images/logo.png"

style="width:220px;
height:220px;
object-fit:cover;
border-radius:12px;
border:1px solid #ddd;">

<?php

}
else
{

?>

<img

id="preview"

src="../uploads/foods/<?php echo $food['image'];?>"

style="width:220px;
height:220px;
object-fit:cover;
border-radius:12px;
border:1px solid #ddd;">

<?php

}

?>

</div>

</div>

<hr>

<div class="text-end">

<a

href="foods.php"

class="btn btn-secondary">

<i class="fas fa-times"></i>

Cancel

</a>

<button

type="submit"

name="update"

class="btn btn-warning">

<i class="fas fa-save"></i>

Update Food

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

var reader=new FileReader();

reader.onload=function(e)
{

document.getElementById("preview").src=e.target.result;

}

reader.readAsDataURL(event.target.files[0]);

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>