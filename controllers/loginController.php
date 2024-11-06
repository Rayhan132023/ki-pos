<?php
session_start();
include '../models/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = md5($_POST['password']); 

    $db = new Database();
    $conn = $db->connect();

    $query = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $email;
        header("Location: ../views/dashboard.php");
    } else {
        echo "Login failed. Invalid email or password.";
    }
}
?>
