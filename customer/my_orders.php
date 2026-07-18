<?php
session_start();

include("../config/db.php");
include("../config/config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

$orders=mysqli_query($conn,"
SELECT *
FROM orders
WHERE user_id='$user_id'
ORDER BY created_at DESC
");
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

My Orders

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../assets/css/style.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{

background:#f5f6fa;

}

.card{

border:none;

border-radius:15px;

box-shadow:0 10px 30px rgba(0,0,0,.1);

}

.table th{

background:#212529;

color:white;

}

</style>

</head>

<body>

<div class="container mt-5 mb-5">

<div class="card">

<div class="card-header bg-warning">

<h3>

<i class="fas fa-shopping-bag"></i>

My Orders

</h3>

</div>

<div class="card-body">

<?php

if(mysqli_num_rows($orders)>0)
{

?>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>Transaction ID</th>

<th>Amount</th>

<th>Payment</th>

<th>Payment Status</th>

<th>Order Status</th>

<th>Date</th>

<th>Receipt</th>

</tr>

</thead>

<tbody>

<?php

while($order=mysqli_fetch_assoc($orders))
{

?>
<tr>

<td>

<strong class="text-primary">

<?php echo $order['transaction_id']; ?>

</strong>

</td>

<td>

<strong class="text-danger">

<?php echo CURRENCY." ".number_format($order['total_amount'],2); ?>

</strong>

</td>

<td>

<?php echo htmlspecialchars($order['payment_method']); ?>

</td>

<td>

<?php

if($order['payment_status']=="Pending")
{
    echo "<span class='badge bg-warning text-dark'>Pending</span>";
}
elseif($order['payment_status']=="Verified")
{
    echo "<span class='badge bg-success'>Verified</span>";
}
else
{
    echo "<span class='badge bg-danger'>Rejected</span>";
}

?>

</td>

<td>

<?php

switch($order['order_status'])
{
    case "Pending":
        echo "<span class='badge bg-warning text-dark'>Pending</span>";
        break;

    case "Preparing":
        echo "<span class='badge bg-primary'>Preparing</span>";
        break;

    case "Ready":
        echo "<span class='badge bg-info'>Ready</span>";
        break;

    case "Out For Delivery":
        echo "<span class='badge bg-secondary'>Out For Delivery</span>";
        break;

    case "Delivered":
        echo "<span class='badge bg-success'>Delivered</span>";
        break;

    default:
        echo "<span class='badge bg-danger'>Cancelled</span>";
}

?>

</td>

<td>

<?php echo date("d M Y",strtotime($order['created_at'])); ?>

<br>

<small>

<?php echo date("h:i A",strtotime($order['created_at'])); ?>

</small>

</td>

<td>

<a

href="../order_success.php?transaction_id=<?php echo urlencode($order['transaction_id']); ?>"

class="btn btn-success btn-sm mb-1">

<i class="fas fa-eye"></i>

View

</a>

<br>

<button

onclick="window.open('../order_success.php?transaction_id=<?php echo urlencode($order['transaction_id']); ?>','_blank').print();"

class="btn btn-dark btn-sm">

<i class="fas fa-print"></i>

Print

</button>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<?php

}
else
{

?>

<div class="text-center py-5">

<i class="fas fa-shopping-basket fa-5x text-muted mb-3"></i>

<h3>

No Orders Found

</h3>

<p class="text-muted">

You haven't placed any orders yet.

</p>

<a

href="../menu.php"

class="btn btn-warning btn-lg">

<i class="fas fa-utensils"></i>

Order Food Now

</a>

</div>

<?php

}

?>

<hr>

<div class="d-flex justify-content-between">

<a

href="dashboard.php"

class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>

Dashboard

</a>

<a

href="../menu.php"

class="btn btn-warning">

<i class="fas fa-utensils"></i>

Continue Shopping

</a>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>