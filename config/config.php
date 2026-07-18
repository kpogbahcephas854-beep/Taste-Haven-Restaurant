<?php

// Start session only once
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Website Configuration
define("SITE_NAME", "Taste Haven Restaurant");
define("SITE_URL", "http://localhost:8082/");
define("CURRENCY", "RWF");

// Default Time Zone
date_default_timezone_set("Africa/Kigali");

?>