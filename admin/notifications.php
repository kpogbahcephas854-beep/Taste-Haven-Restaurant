<?php
include("includes/auth.php");
include("../config/db.php");
include("../config/config.php");

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
");

header("Location: notifications.php");
exit();

}

/* ==========================================
MARK ALL AS READ
========================================== */

if(isset($_GET['readall']))
{

mysqli_query($conn,"
UPDATE notifications
SET is_read='1'
");

header("Location: notifications.php");
exit();

}

/* ==========================================
DELETE NOTIFICATION
========================================== */

if(isset($_GET['delete']))
{

$id=(int)$_GET['delete'];

mysqli_query($conn,"
DELETE FROM notifications
WHERE id='$id'
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
ORDER BY created_at DESC
");

$total=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM notifications
"));

$unread=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM notifications
WHERE is_read='0'
"));

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

Notifications

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../assets/css/admin.css">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

.notification-card{

border:none;

border-radius:18px;

box-shadow:0 10px 30px rgba(0,0,0,.08);

margin-bottom:20px;

transition:.3s;

}

.notification-card:hover{

transform:translateY(-3px);

}

.notification-new{

border-left:6px solid #ffc107;

background:#fffdf4;

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

<div class="wrapper">

<?php include("includes/sidebar.php"); ?>

<div class="main">

<?php include("includes/header.php"); ?>

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

<i class="fas fa-bell"></i>

Notifications

</h2>

<p class="text-muted">

Manage restaurant notifications.

</p>

</div>

<div>

<a

href="?readall=1"

class="btn btn-success me-2">

<i class="fas fa-check-double"></i>

Mark All Read

</a>

</div>

</div>

<div class="row mb-4">

<div class="col-lg-6">

<div class="card text-center shadow border-0">

<div class="card-body">

<h2>

<?php echo $total; ?>

</h2>

<p>

Total Notifications

</p>

</div>

</div>

</div>

<div class="col-lg-6">

<div class="card text-center shadow border-0 bg-warning">

<div class="card-body">

<h2>

<?php echo $unread; ?>

</h2>

<p>

Unread Notifications

</p>

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

/* Notification Icon */

$icon="fas fa-bell";
$color="bg-primary";

if($row['type']=="order")
{
    $icon="fas fa-shopping-cart";
    $color="bg-success";
}
elseif($row['type']=="message")
{
    $icon="fas fa-envelope";
    $color="bg-warning";
}
elseif($row['type']=="payment")
{
    $icon="fas fa-credit-card";
    $color="bg-info";
}
elseif($row['type']=="customer")
{
    $icon="fas fa-user";
    $color="bg-secondary";
}
elseif($row['type']=="delivery")
{
    $icon="fas fa-motorcycle";
    $color="bg-dark";
}

?>

<div class="card notification-card <?php echo $row['is_read']==0 ? 'notification-new' : 'notification-read'; ?>">

<div class="card-body">

<div class="row align-items-center">

<div class="col-lg-1 text-center">

<div class="notification-icon <?php echo $color; ?>">

<i class="<?php echo $icon; ?>"></i>

</div>

</div>

<div class="col-lg-8">

<h5 class="mb-2">

<?php echo htmlspecialchars($row['title']); ?>

<?php if($row['is_read']==0){ ?>

<span class="badge bg-danger ms-2">

NEW

</span>

<?php } ?>

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

<?php if($row['is_read']==0){ ?>

<a

href="?read=<?php echo $row['id']; ?>"

class="btn btn-success btn-sm mb-2">

<i class="fas fa-check"></i>

Mark Read

</a>

<br>

<?php } ?>

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

</p>

</div>

</div>

<?php

}

?>
<div class="admin-footer">

<hr>

<div class="row align-items-center">

<div class="col-md-6 text-start">

<p class="mb-0">

© <?php echo date("Y"); ?>

Taste Haven Restaurant Management System

</p>

</div>

<div class="col-md-6 text-end">

<small class="text-muted">

Notifications Center

</small>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

/* ==========================================
AUTO REFRESH EVERY 20 SECONDS
========================================== */

setInterval(function(){

location.reload();

},20000);

/* ==========================================
SMOOTH CARD ANIMATION
========================================== */

document.querySelectorAll(".notification-card").forEach(function(card){

card.addEventListener("mouseenter",function(){

this.style.transform="translateY(-5px)";

});

card.addEventListener("mouseleave",function(){

this.style.transform="translateY(0px)";

});

});

</script>

</body>

</html>