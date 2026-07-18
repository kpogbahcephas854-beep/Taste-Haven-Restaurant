<?php
include("includes/auth.php");
include("../config/db.php");

$message = "";

if(isset($_POST['save']))
{

    $category_name = mysqli_real_escape_string($conn,$_POST['category_name']);

    $status = mysqli_real_escape_string($conn,$_POST['status']);

    $image = "";

    if(isset($_FILES['image']) && $_FILES['image']['name']!="")
    {

        $image = time()."_".$_FILES['image']['name'];

        $tmp = $_FILES['image']['tmp_name'];

        move_uploaded_file($tmp,"../uploads/categories/".$image);

    }

    $sql = "INSERT INTO categories
    (
        category_name,
        image,
        status
    )

    VALUES

    (
        '$category_name',
        '$image',
        '$status'
    )";

    if(mysqli_query($conn,$sql))
    {

        $message = "<div class='alert alert-success'>
        Category Added Successfully.
        </div>";

    }

    else
    {

        $message = "<div class='alert alert-danger'>
        Failed to Save Category.
        </div>";

    }

}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Add Category</title>

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

<div class="d-flex
justify-content-between
align-items-center
mb-4">

<div>

<h2>

Add New Category

</h2>

<p class="text-muted">

Create a new food category.

</p>

</div>

<a href="categories.php"
class="btn btn-dark">

<i class="fas fa-arrow-left"></i>

Back

</a>

</div>

<?php echo $message; ?>

<div class="card shadow">

<div class="card-body">

<form
method="POST"
enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label>

Category Name

</label>

<input

type="text"

name="category_name"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Status

</label>

<select

name="status"

class="form-select">

<option>

Active

</option>

<option>

Inactive

</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>

Category Image

</label>

<input

type="file"

name="image"

class="form-control"

accept="image/*"

onchange="previewImage(event)">

</div>

<div class="col-md-6 mb-3">

<label>

Image Preview

</label>

<br>

<img

id="preview"

src="../assets/images/no-image.png"

style="width:180px;
height:180px;
border-radius:12px;
object-fit:cover;
border:1px solid #ddd;">

</div>

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

                            Save Category

                        </button>

                    </div>

                </form>

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