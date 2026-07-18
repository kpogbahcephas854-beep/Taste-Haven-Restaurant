<?php
include("includes/auth.php");
include("../config/db.php");

if(!isset($_GET['id']))
{
    header("Location: foods.php");
    exit();
}

$id = (int)$_GET['id'];

/* Get Food Image */

$query = mysqli_query($conn,"
SELECT image
FROM foods
WHERE id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location: foods.php");
    exit();
}

$food = mysqli_fetch_assoc($query);

/* Delete Image */

if($food['image']!="")
{

    $path = "../uploads/foods/".$food['image'];

    if(file_exists($path))
    {
        unlink($path);
    }

}

/* Delete Food */

mysqli_query($conn,"
DELETE FROM foods
WHERE id='$id'
");

header("Location: foods.php?deleted=1");
exit();

?>