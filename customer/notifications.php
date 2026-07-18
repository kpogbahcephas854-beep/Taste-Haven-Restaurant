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

/* ==========================================
MARK AS READ
========================================== */

if(isset($_GET['read']))
{

$id=(int)$_GET['read'];

mysqli_query($conn,"
UPDATE notifications
SET is_read='1'
WHERE id='$id'
AND user_id='$user_id'
");

header("Location: notifications.php");
exit();

}

/* ==========================================
MARK ALL READ
========================================== */

if(isset($_GET['readall']))
{

mysqli_query($conn,"
UPDATE notifications
SET is_read='1'
WHERE user_id='$user_id'
");

header("Location: notifications.php");
exit();

}

/* ==========================================
DELETE
========================================== */

if(isset($_GET['delete']))
{

$id=(int)$_GET['delete'];

mysqli_query($conn,"
DELETE FROM notifications
WHERE id='$id'
AND user_id='$user_id'
");

header("Location: notifications.php");
exit();

}

/* ==========================================
LOAD NOTIFICATIONS
========================================== */

$notifications=mysqli_query($conn,"
SELECT *
FROM notifications
WHERE user_id='$user_id'
ORDER BY created_at DESC
");

$total=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM notifications
WHERE user_id='$user_id'
"));

$unread=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM notifications
WHERE user_id='$user_id'
AND is_read='0'
"));

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">

<title>My Notifications</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

.notification-card{

border:none;

border-radius:18px;

box-shadow:0 10px 25px rgba(0,0,0,.08);

margin-bottom:20px;

}

.notification-new{

border-left:6px solid #ffc107;

background:#fffdf3;

}

.notification-read{

border-left:6px solid #198754;

}

.notification-icon{

width:60px;

height:60px;

border-radius:50%;

display:flex;

align-items:center;

justify-content:center;

font-size:26px;

color:white;

}

</style>

</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

<i class="fas fa-bell"></i>

My Notifications

</h2>

<p class="text-muted">

Stay updated with your orders and messages.

</p>

</div>

<a

href="?readall=1"

class="btn btn-success">

<i class="fas fa-check-double"></i>

Mark All Read

</a>

</div>

<div class="row mb-4">

<div class="col-md-6">

<div class="card text-center shadow border-0">

<div class="card-body">

<h2>

<?php echo $total; ?>

</h2>

<p>Total Notifications</p>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card text-center shadow border-0 bg-warning">

<div class="card-body">

<h2>

<?php echo $unread; ?>

</h2>

<p>Unread Notifications</p>

</div>

</div>

</div>

</div>
<!-- NOTIFICATION LIST -->

<?php

if(mysqli_num_rows($notifications)>0)
{

while($row=mysqli_fetch_assoc($notifications))
{

/* Notification Icons */

$icon="fas fa-bell";

$color="bg-primary";

if($row['type']=="order")
{

$icon="fas fa-shopping-cart";

$color="bg-success";

}
elseif($row['type']=="payment")
{

$icon="fas fa-credit-card";

$color="bg-info";

}
elseif($row['type']=="message")
{

$icon="fas fa-envelope";

$color="bg-warning";

}
elseif($row['type']=="delivery")
{

$icon="fas fa-motorcycle";

$color="bg-dark";

}
elseif($row['type']=="promotion")
{

$icon="fas fa-gift";

$color="bg-danger";

}

?>

<div class="card notification-card <?php echo ($row['is_read']==0) ? 'notification-new' : 'notification-read'; ?>">

<div class="card-body">

<div class="row align-items-center">

<div class="col-lg-1 text-center">

<div class="notification-icon <?php echo $color; ?>">

<i class="<?php echo $icon; ?>"></i>

</div>

</div>

<div class="col-lg-8">

<h5>

<?php echo htmlspecialchars($row['title']); ?>

<?php

if($row['is_read']==0)
{

?>

<span class="badge bg-danger ms-2">

NEW

</span>

<?php

}

?>

</h5>

<p class="mb-2">

<?php echo nl2br(htmlspecialchars($row['message'])); ?>

</p>

<small class="text-muted">

<i class="fas fa-clock"></i>

<?php echo date("d M Y h:i A",strtotime($row['created_at'])); ?>

</small>

</div>

<div class="col-lg-3 text-end">

<?php

if($row['is_read']==0)
{

?>

<a

href="?read=<?php echo $row['id']; ?>"

class="btn btn-success btn-sm mb-2">

<i class="fas fa-check"></i>

Mark Read

</a>

<br>

<?php

}

?>

<a

href="?delete=<?php echo $row['id']; ?>"

onclick="return confirm('Delete this notification?')"

class="btn btn-danger btn-sm">

<i class="fas fa-trash"></i>

Delete

</a>

</div>

</div>

</div>

</div>

<?php

}

}
else
{

?>

<div class="card shadow border-0">

<div class="card-body text-center py-5">

<i class="fas fa-bell-slash fa-5x text-muted mb-3"></i>

<h3>

No Notifications

</h3>

<p class="text-muted">

You don't have any notifications yet.

Once you place orders or receive replies from the restaurant, they will appear here.

</p>

</div>

</div>

<?php

}

?>
<div class="card border-0 shadow-sm mt-4">

<div class="card-body">

<div class="row text-center">

<div class="col-md-4">

<i class="fas fa-shopping-cart fa-2x text-success mb-3"></i>

<h6>

Order Updates

</h6>

<small class="text-muted">

Receive notifications when your order is accepted, prepared, dispatched and delivered.

</small>

</div>

<div class="col-md-4">

<i class="fas fa-envelope fa-2x text-warning mb-3"></i>

<h6>

Support Messages

</h6>

<small class="text-muted">

Instantly receive replies from the Taste Haven support team.

</small>

</div>

<div class="col-md-4">

<i class="fas fa-gift fa-2x text-danger mb-3"></i>

<h6>

Special Offers

</h6>

<small class="text-muted">

Stay informed about discounts, promotions and new meals.

</small>

</div>

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

/* ==========================================
AUTO REFRESH
========================================== */

setInterval(function(){

location.reload();

},20000);

/* ==========================================
CARD ANIMATION
========================================== */

document.querySelectorAll(".notification-card").forEach(function(card){

card.addEventListener("mouseenter",function(){

this.style.transform="translateY(-5px)";

this.style.transition=".3s";

});

card.addEventListener("mouseleave",function(){

this.style.transform="translateY(0px)";

});

});

</script>

</body>

</html>