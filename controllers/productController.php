<?php
session_start();
include '../models/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    } else {
        echo "File is not an image.";
        exit();
    }


    $db = new Database();
    $conn = $db->connect();

    $query = "INSERT INTO products (name, category_id, price, stock, image, description) 
              VALUES ('$name', '$category_id', '$price', '$stock', '$image', '$description')";
    if ($conn->query($query) === TRUE) {
        header("Location: ../views/products.php");
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>
