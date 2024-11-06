<?php
session_start();
include '../models/Database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$db = new Database();
$conn = $db->connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    mysqli_query($conn, "DELETE FROM admins WHERE id = $id");
    header("Location: admin.php"); 
}
?>
