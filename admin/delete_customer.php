<?php

include("includes/auth.php");
include("../config/db.php");

if(!isset($_GET['id']))
{
    header("Location: customers.php");
    exit();
}

$id=(int)$_GET['id'];

/* ==========================
GET CUSTOMER
========================== */

$customer=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$id'
"));

if(!$customer)
{
    header("Location: customers.php");
    exit();
}

/* ==========================
CHECK CUSTOMER ORDERS
========================== */

$orders=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM orders
WHERE customer_name='".$customer['fullname']."'
"));

/* ==========================
IF CUSTOMER HAS ORDERS
DO NOT DELETE
========================== */

if($orders>0)
{

echo "

<!DOCTYPE html>

<html>

<head>

<title>Delete Customer</title>

<link
href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'
rel='stylesheet'>

</head>

<body class='bg-light'>

<div class='container mt-5'>

<div class='alert alert-danger text-center shadow'>

<h3>

Customer Cannot Be Deleted

</h3>

<p>

This customer already has orders in the system.

Deleting this customer would affect your restaurant records.

</p>

<a
href='customers.php'
class='btn btn-dark'>

Back to Customers

</a>

</div>

</div>

</body>

</html>

";

exit();

}

/* ==========================
DELETE CUSTOMER
========================== */

mysqli_query($conn,"
DELETE FROM users
WHERE id='$id'
");

header("Location: customers.php");

exit();

?>