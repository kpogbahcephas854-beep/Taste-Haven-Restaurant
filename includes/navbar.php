<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">

<div class="container">

<a class="navbar-brand d-flex align-items-center" href="index.php">

<img
src="assets/images/logo.png"
width="50"
height="50"
class="rounded-circle me-2">

<strong>Taste Haven</strong>

</a>

<button
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#menu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="menu">

<ul class="navbar-nav ms-auto align-items-lg-center">

<li class="nav-item">

<a href="index.php" class="nav-link">

Home

</a>

</li>

<li class="nav-item">

<a href="menu.php" class="nav-link">

Menu

</a>

</li>

<li class="nav-item">

<a href="categories.php" class="nav-link">

Categories

</a>

</li>

<li class="nav-item">

<a href="track_order.php" class="nav-link">

Track Order

</a>

</li>

<li class="nav-item">

<a href="cart.php" class="nav-link">

<i class="fas fa-shopping-cart"></i>

Cart

</a>

</li>

<li class="nav-item">

<a href="about.php" class="nav-link">

About

</a>

</li>

<li class="nav-item">

<a href="contact.php" class="nav-link">

Contact

</a>

</li>

<?php if(isset($_SESSION['user_id'])){ ?>

<li class="nav-item dropdown">

<a
class="nav-link dropdown-toggle"
href="#"
role="button"
data-bs-toggle="dropdown">

<i class="fas fa-user-circle"></i>

<?php echo $_SESSION['fullname']; ?>

</a>

<ul class="dropdown-menu dropdown-menu-end">

<li>

<a
class="dropdown-item"
href="customer/dashboard.php">

<i class="fas fa-home"></i>

Dashboard

</a>

</li>

<li>

<a
class="dropdown-item"
href="customer/my_orders.php">

<i class="fas fa-shopping-bag"></i>

My Orders

</a>

</li>

<li>

<a
class="dropdown-item"
href="customer/profile.php">

<i class="fas fa-user"></i>

My Profile

</a>

</li>

<li><hr class="dropdown-divider"></li>

<li>

<a
class="dropdown-item text-danger"
href="customer/logout.php">

<i class="fas fa-sign-out-alt"></i>

Logout

</a>

</li>

</ul>

</li>

<?php } else { ?>

<li class="nav-item">

<a
href="customer/register.php"
class="nav-link">

Register

</a>

</li>

<li class="nav-item">

<a
href="customer/login.php"
class="btn btn-warning ms-lg-2 me-lg-2">

Login

</a>

</li>

<?php } ?>

<li class="nav-item">

<a
href="admin/login.php"
class="btn btn-outline-light">

Admin Login

</a>

</li>

</ul>

</div>

</div>

</nav>