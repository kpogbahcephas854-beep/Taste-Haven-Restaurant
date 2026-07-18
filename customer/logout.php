<?php
session_start();

/* Destroy Customer Session */

session_unset();

session_destroy();

/* Redirect To Login */

header("Location: login.php");

exit();
?>