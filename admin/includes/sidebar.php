<div class="sidebar">

    <!-- Logo -->
    <div class="logo-area">

        <img src="../assets/images/logo.png" alt="Logo">

        <h3>Taste Haven</h3>

        <p>Restaurant Management</p>

    </div>

    <!-- Administrator Profile -->
    <div class="admin-profile">

        <img src="../assets/images/logo.png" alt="Admin">

        <h5><?php echo ucfirst($_SESSION['admin']); ?></h5>

        <span>
            <i class="fas fa-circle"></i> Online
        </span>

    </div>

    <hr style="border-color:rgba(255,255,255,.15); margin:20px;">

    <!-- Navigation -->

    <ul>

        <li>
            <a href="dashboard.php">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="categories.php">
                <i class="fas fa-list"></i>
                <span>Categories</span>
            </a>
        </li>

        <li>
            <a href="foods.php">
                <i class="fas fa-utensils"></i>
                <span>Foods</span>
            </a>
        </li>

        <li>
            <a href="orders.php">
                <i class="fas fa-shopping-cart"></i>
                <span>Orders</span>
            </a>
        </li>

        <li>
            <a href="customers.php">
                <i class="fas fa-users"></i>
                <span>Customers</span>
            </a>
        </li>

        <li>
            <a href="reports.php">
                <i class="fas fa-chart-line"></i>
                <span>Reports</span>
            </a>
        </li>

        <li>
            <a href="settings.php">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>

        <li>
            <a href="messages.php">
    <i class="fas fa-envelope"></i>
    <span>Messages</span>
    </a>
        </li>

        <li>
            <a href="notifications.php">
    <i class="fas fa-bell"></i>
    <span>Notifications</span>
    </a>
        </li>

        <li>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>

</div>