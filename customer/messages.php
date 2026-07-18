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
SEND MESSAGE
========================================== */

if(isset($_POST['send']))
{

$message=mysqli_real_escape_string($conn,$_POST['message']);

if($message!="")
{

mysqli_query($conn,"
INSERT INTO messages
(
user_id,
sender,
message
)
VALUES
(
'$user_id',
'customer',
'$message'
)
");

/* Notify Admin */

mysqli_query($conn,"
INSERT INTO notifications
(
title,
message,
type
)
VALUES
(
'New Customer Message',
'".$_SESSION['fullname']." sent a new message.',
'message'
)
");

}

}

/* ==========================================
LOAD CHAT
========================================== */

$messages=mysqli_query($conn,"
SELECT *
FROM messages
WHERE user_id='$user_id'
ORDER BY created_at ASC
");

/* Mark Admin Replies as Read */

mysqli_query($conn,"
UPDATE messages
SET is_read='1'
WHERE
user_id='$user_id'
AND sender='admin'
");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

Support Messages

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

background:#f4f6f9;

}

.chat-card{

background:white;

border-radius:20px;

box-shadow:0 10px 30px rgba(0,0,0,.08);

overflow:hidden;

}

.chat-header{

background:#212529;

color:white;

padding:20px;

font-size:22px;

font-weight:bold;

}

.chat-body{

height:600px;

overflow-y:auto;

background:#f8f9fa;

padding:25px;

}

.chat-body::-webkit-scrollbar{

width:6px;

}

.chat-body::-webkit-scrollbar-thumb{

background:#ffc107;

border-radius:20px;

}

.customer-message{

background:#ffc107;

padding:15px 20px;

border-radius:20px 20px 0 20px;

margin-bottom:20px;

max-width:70%;

margin-left:auto;

}

.admin-message{

background:white;

padding:15px 20px;

border-radius:20px 20px 20px 0;

margin-bottom:20px;

max-width:70%;

box-shadow:0 5px 12px rgba(0,0,0,.08);

}

.message-time{

font-size:12px;

margin-top:8px;

color:#666;

}

.reply-box{

padding:20px;

background:white;

border-top:1px solid #eee;

}

.reply-box textarea{

resize:none;

border-radius:12px;

}

</style>

</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container py-5">

<div class="chat-card">

<div class="chat-header">

<i class="fas fa-headset"></i>

Customer Support

</div>

<div class="chat-body" id="chatBox">
<?php

if(mysqli_num_rows($messages)>0)
{

while($msg=mysqli_fetch_assoc($messages))
{

if($msg['sender']=="customer")
{

?>

<div class="customer-message">

<strong>

You

</strong>

<br><br>

<?php echo nl2br(htmlspecialchars($msg['message'])); ?>

<div class="message-time">

<?php echo date("d M Y h:i A",strtotime($msg['created_at'])); ?>

</div>

</div>

<?php

}
else
{

?>

<div class="admin-message">

<strong>

Taste Haven Support

</strong>

<br><br>

<?php echo nl2br(htmlspecialchars($msg['message'])); ?>

<div class="message-time">

<?php echo date("d M Y h:i A",strtotime($msg['created_at'])); ?>

</div>

</div>

<?php

}

}

}
else
{

?>

<div class="text-center mt-5">

<i class="fas fa-comments fa-5x text-muted mb-3"></i>

<h3>

No Messages Yet

</h3>

<p class="text-muted">

Welcome to Taste Haven Support.

If you have any questions about your order, payment, or delivery, send us a message below and our team will respond as soon as possible.

</p>

</div>

<?php

}

?>

</div>

<!-- MESSAGE BOX -->

<div class="reply-box">

<form method="POST">

<div class="row">

<div class="col-lg-10">

<textarea

name="message"

rows="3"

class="form-control"

placeholder="Type your message here..."

required></textarea>

</div>

<div class="col-lg-2">

<div class="d-grid">

<button

type="submit"

name="send"

class="btn btn-warning h-100">

<i class="fas fa-paper-plane"></i>

<br>

Send

</button>

</div>

</div>

</div>

</form>

</div>

</div>

</div>
<div class="container mt-4">

<div class="row">

<div class="col-lg-12">

<div class="card border-0 shadow-sm">

<div class="card-body">

<div class="row text-center">

<div class="col-md-4">

<i class="fas fa-clock fa-2x text-warning mb-2"></i>

<h6>Fast Response</h6>

<small class="text-muted">

Our support team normally replies within a few minutes during business hours.

</small>

</div>

<div class="col-md-4">

<i class="fas fa-shield-alt fa-2x text-success mb-2"></i>

<h6>Secure Conversation</h6>

<small class="text-muted">

All conversations between you and Taste Haven are private and secure.

</small>

</div>

<div class="col-md-4">

<i class="fas fa-headset fa-2x text-primary mb-2"></i>

<h6>24/7 Support</h6>

<small class="text-muted">

Contact us anytime regarding orders, payments, or delivery assistance.

</small>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

/* ==========================================
AUTO SCROLL TO LATEST MESSAGE
========================================== */

var chat=document.getElementById("chatBox");

if(chat)
{

chat.scrollTop=chat.scrollHeight;

}

/* ==========================================
AUTO REFRESH EVERY 15 SECONDS
========================================== */

setInterval(function(){

location.reload();

},15000);

</script>

</body>

</html>