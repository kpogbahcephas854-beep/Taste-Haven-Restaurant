<?php
session_start();

include("config/db.php");

// Create cart session if it doesn't exist
if(!isset($_SESSION['cart']))
{
    $_SESSION['cart'] = [];
}

// Check if food id exists
if(!isset($_GET['id']))
{
    header("Location: menu.php");
    exit();
}

$food_id = (int)$_GET['id'];

// Verify the food exists
$check = mysqli_query($conn,"
SELECT id
FROM foods
WHERE id='$food_id'
AND status='Available'
");

if(mysqli_num_rows($check)==0)
{
    header("Location: menu.php");
    exit();
}

// Add to cart
if(isset($_SESSION['cart'][$food_id]))
{
    $_SESSION['cart'][$food_id]++;
}
else
{
    $_SESSION['cart'][$food_id]=1;
}

// Redirect to cart
header("Location: cart.php");
exit();
?>