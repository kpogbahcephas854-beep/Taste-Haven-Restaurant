<?php
session_start();

include("config/db.php");
include("config/config.php");

/* ==========================
CHECK LOGIN
========================== */

if(!isset($_SESSION['user_id']))
{
    header("Location: customer/login.php");
    exit();
}

/* ==========================
CHECK CART
========================== */

if(!isset($_SESSION['cart']) || count($_SESSION['cart'])==0)
{
    header("Location: cart.php");
    exit();
}

/* ==========================
GET CUSTOMER DETAILS
========================== */

$user_id=$_SESSION['user_id'];

$customer_name=mysqli_real_escape_string($conn,$_POST['full_name']);

$email=mysqli_real_escape_string($conn,$_POST['email']);

$phone=mysqli_real_escape_string($conn,$_POST['phone']);

$address=mysqli_real_escape_string($conn,$_POST['address']);

$notes=mysqli_real_escape_string($conn,$_POST['notes']);

$payment_method=mysqli_real_escape_string($conn,$_POST['payment_method']);

$payment_phone=mysqli_real_escape_string($conn,$_POST['payment_phone']);

/* ==========================
CALCULATE TOTAL
========================== */

$subtotal=0;

foreach($_SESSION['cart'] as $food_id=>$qty)
{

$food=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM foods
WHERE id='$food_id'
"));

if($food)
{

$subtotal += $food['price']*$qty;

}

}

$delivery=0;

if($subtotal<30000)
{

$delivery=3000;

}

$tax=$subtotal*0.05;

$total_amount=$subtotal+$delivery+$tax;
/* ==========================
GENERATE TRANSACTION ID
========================== */

$date=date("Ymd");

$check=mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM orders
WHERE DATE(created_at)=CURDATE()
");

$row=mysqli_fetch_assoc($check);

$number=str_pad($row['total']+1,4,"0",STR_PAD_LEFT);

$transaction_id="THR".$date.$number;

/* ==========================
SAVE ORDER
========================== */

mysqli_query($conn,"
INSERT INTO orders
(

user_id,

customer_name,

email,

phone,

address,

notes,

total_amount,

payment_method,

payment_phone,

transaction_id,

order_status,

payment_status

)

VALUES

(

'$user_id',

'$customer_name',

'$email',

'$phone',

'$address',

'$notes',

'$total_amount',

'$payment_method',

'$payment_phone',

'$transaction_id',

'Pending',

'Pending'

)

");

/* ==========================
GET ORDER ID
========================== */

$order_id=mysqli_insert_id($conn);

if(!$order_id)
{

die("Unable to save order.");

}
/* ==========================
SAVE ORDER ITEMS
========================== */

foreach($_SESSION['cart'] as $food_id=>$quantity)
{

$food=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM foods
WHERE id='$food_id'
"));

if(!$food)
{
    continue;
}

$price=$food['price'];

$subtotal=$price*$quantity;

mysqli_query($conn,"
INSERT INTO order_items
(

order_id,

food_id,

quantity,

price,

subtotal

)

VALUES
(

'$order_id',

'$food_id',

'$quantity',

'$price',

'$subtotal'

)

");

}

/* ==========================
CLEAR CART
========================== */

unset($_SESSION['cart']);

/* ==========================
REDIRECT TO SUCCESS PAGE
========================== */

header("Location: order_success.php?transaction=".$transaction_id);

exit();

?>