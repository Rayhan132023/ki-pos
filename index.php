<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: views/dashboard.php");
} else {
    header("Location: views/login.php");
}
?>
