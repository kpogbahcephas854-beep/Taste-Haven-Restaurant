<?php
include("includes/auth.php");
include("../config/db.php");
include("../config/config.php");

/* ==========================================
SEND REPLY
========================================== */

if(isset($_POST['send']))
{

    $user_id=(int)$_POST['user_id'];

    $message=mysqli_real_escape_string($conn,$_POST['message']);

    if(!empty($message))
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
        'admin',
        '$message'
        )
        ");

        mysqli_query($conn,"
        INSERT INTO notifications
        (
        user_id,
        title,
        message,
        type
        )
        VALUES
        (
        '$user_id',
        'New Reply',
        'Administrator replied to your message.',
        'message'
        )
        ");

    }

}

/* ==========================================
CURRENT CUSTOMER
========================================== */

$current_user=0;

if(isset($_GET['user']))
{
    $current_user=(int)$_GET['user'];
}

/* ==========================================
LOAD CUSTOMERS
========================================== */

$customers=mysqli_query($conn,"
SELECT *
FROM users
ORDER BY fullname ASC
");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>

Customer Messages

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

body{

background:#f4f6f9;

}

/* LEFT PANEL */

.chat-users{

height:720px;

overflow-y:auto;

border-radius:18px;

background:white;

box-shadow:0 10px 25px rgba(0,0,0,.08);

}

.chat-users::-webkit-scrollbar{

width:6px;

}

.chat-users::-webkit-scrollbar-thumb{

background:#f39c12;

border-radius:20px;

}

.customer{

display:block;

padding:18px;

border-bottom:1px solid #eee;

color:#333;

transition:.3s;

}

.customer:hover{

background:#fff8ef;

color:#000;

}

.customer.active{

background:#ffc107;

color:#000;

font-weight:bold;

}

/* RIGHT PANEL */

.chat-card{

background:white;

border-radius:18px;

box-shadow:0 10px 25px rgba(0,0,0,.08);

overflow:hidden;

}

.chat-header{

padding:20px;

background:#212529;

color:white;

font-size:20px;

font-weight:bold;

}

.chat-body{

height:520px;

overflow-y:auto;

padding:25px;

background:#f7f7f7;

}

.chat-body::-webkit-scrollbar{

width:6px;

}

.chat-body::-webkit-scrollbar-thumb{

background:#f39c12;

border-radius:20px;

}

/* MESSAGE BUBBLES */

.admin-message{

background:#ffc107;

padding:15px 20px;

border-radius:20px 20px 0 20px;

margin-bottom:20px;

max-width:70%;

margin-left:auto;

box-shadow:0 5px 12px rgba(0,0,0,.10);

}

.customer-message{

background:white;

padding:15px 20px;

border-radius:20px 20px 20px 0;

margin-bottom:20px;

max-width:70%;

box-shadow:0 5px 12px rgba(0,0,0,.08);

}

.message-time{

font-size:12px;

color:#666;

margin-top:8px;

}

/* REPLY BOX */

.reply-box{

padding:20px;

border-top:1px solid #eee;

background:white;

}

.reply-box textarea{

resize:none;

border-radius:12px;

}

.reply-box button{

border-radius:12px;

padding:12px;

font-weight:bold;

}

.empty-chat{

display:flex;

justify-content:center;

align-items:center;

height:520px;

font-size:24px;

color:#888;

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

<i class="fas fa-envelope"></i>

Customer Messages

</h2>

<p class="text-muted">

Chat with customers and provide support.

</p>

</div>

</div>

<div class="row">

<!-- CUSTOMER LIST -->

<div class="col-lg-3">

<div class="chat-users">
<?php

while($user=mysqli_fetch_assoc($customers))
{

$unread=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM messages
WHERE user_id='".$user['id']."'
AND sender='customer'
AND is_read='0'
"));

?>

<a

href="messages.php?user=<?php echo $user['id']; ?>"

class="customer <?php if($current_user==$user['id']) echo "active"; ?>">

<div class="d-flex justify-content-between">

<div>

<strong>

<?php echo $user['fullname']; ?>

</strong>

<br>

<small class="text-muted">

<?php echo $user['email']; ?>

</small>

</div>

<?php

if($unread['total']>0)
{

?>

<span class="badge bg-danger">

<?php echo $unread['total']; ?>

</span>

<?php

}

?>

</div>

</a>

<?php

}

?>

</div>

</div>

<!-- CHAT WINDOW -->

<div class="col-lg-9">

<div class="chat-card">

<?php

if($current_user>0)
{

$customer=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$current_user'
"));

mysqli_query($conn,"
UPDATE messages
SET is_read='1'
WHERE
user_id='$current_user'
AND sender='customer'
");

?>

<div class="chat-header">

<i class="fas fa-user-circle"></i>

<?php echo $customer['fullname']; ?>

</div>

<div class="chat-body" id="chatBox">

<?php

$messages=mysqli_query($conn,"
SELECT *
FROM messages
WHERE user_id='$current_user'
ORDER BY created_at ASC
");

if(mysqli_num_rows($messages)>0)
{

while($msg=mysqli_fetch_assoc($messages))
{

if($msg['sender']=="admin")
{

?>

<div class="admin-message">

<strong>

Administrator

</strong>

<br><br>

<?php echo nl2br($msg['message']); ?>

<div class="message-time">

<?php

echo date("d M Y h:i A",strtotime($msg['created_at']));

?>

</div>

</div>

<?php

}
else
{

?>

<div class="customer-message">

<strong>

<?php echo $customer['fullname']; ?>

</strong>

<br><br>

<?php echo nl2br($msg['message']); ?>

<div class="message-time">

<?php

echo date("d M Y h:i A",strtotime($msg['created_at']));

?>

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

<h4>

No Conversation Yet

</h4>

<p class="text-muted">

This customer hasn't sent any messages.

</p>

</div>

<?php

}

?>

</div>

<div class="reply-box">

<form method="POST">

<input

type="hidden"

name="user_id"

value="<?php echo $current_user; ?>">

<div class="row">

<div class="col-lg-10">

<textarea

name="message"

rows="3"

class="form-control"

placeholder="Type your reply here..."

required></textarea>

</div>

<div class="col-lg-2">

<div class="d-grid">

<button

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

<?php

}
else
{

?>

<div class="empty-chat">

<div class="text-center">

<i class="fas fa-comments fa-5x mb-4"></i>

<h3>

Select a customer

</h3>

<p>

Choose a customer from the left to start chatting.

</p>

</div>

</div>

<?php

}

?>

</div>

</div>
</div>

</div>

</div>

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

Customer Support Center

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