<?php
date_default_timezone_set("Africa/Kigali");
?>

<div class="topbar">

    <div class="top-left">

        <h2>
            <i class="fas fa-utensils text-warning"></i>
            Restaurant Dashboard
        </h2>

        <small>

            Welcome back,

            <strong>

                <?php echo ucfirst($_SESSION['admin']); ?>

            </strong>

        </small>

    </div>

    <div class="top-center">

        <form>

            <div class="search-box">

                <input
                type="text"
                class="form-control"
                placeholder="Search anything...">

            </div>

        </form>

    </div>

    <div class="top-right">

        <div class="date">

            <i class="fas fa-calendar-alt text-warning"></i>

            <?php echo date("l, d F Y"); ?>

        </div>

        <div class="notification">

            <i class="fas fa-bell"></i>

            <span class="notify">3</span>

        </div>

        <div class="notification">

            <i class="fas fa-envelope"></i>

            <span class="notify">5</span>

        </div>

        <img src="../assets/images/logo.png">

    </div>

</div>