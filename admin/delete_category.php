<?php
include("includes/auth.php");
include("../config/db.php");

/* ==========================
CHECK CATEGORY ID
========================== */

if(!isset($_GET['id']))
{
    header("Location:categories.php");
    exit();
}

$id=(int)$_GET['id'];

/* ==========================
GET CATEGORY
========================== */

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

$category=mysqli_fetch_assoc($query);

/* ==========================
DELETE CATEGORY IMAGE
========================== */

if(!empty($category['image']))
{

$image="../uploads/categories/".$category['image'];

if(file_exists($image))
{
    unlink($image);
}

}

/* ==========================
DELETE CATEGORY
========================== */

mysqli_query($conn,"
DELETE FROM categories
WHERE id='$id'
");

/* ==========================
REDIRECT
========================== */

header("Location:categories.php?deleted=1");

exit();

?>