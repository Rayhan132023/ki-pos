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
    $result = mysqli_query($conn, "SELECT * FROM customers WHERE id = $id");
    $customer = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_customer'])) {
    $name = $_POST['customer_name'];
    $email = $_POST['customer_email'];
    mysqli_query($conn, "UPDATE customers SET name = '$name', email = '$email' WHERE id = $id");
    header("Location: admin.php"); 
}
?>


<style>
    body {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        background-color: #e9ecef;
        color: #343a40;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 500px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .container:hover {
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
    }

    h1 {
        text-align: center;
        color: #007BFF;
        margin-bottom: 20px;
        font-size: 24px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #495057;
    }

    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        transition: border-color 0.3s;
    }

    input[type="text"]:focus,
    input[type="email"]:focus {
        border-color: #007BFF;
        outline: none;
    }

    button {
        background-color: #007BFF;
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }

    .back-button {
        display: block;
        text-align: center;
        background-color: #6c757d;
        color: #fff;
        padding: 12px;
        border-radius: 8px;
        text-decoration: none;
        margin-top: 15px;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .back-button:hover {
        background-color: #5a6268;
    }
</style>

<div class="container">
    <h1>Edit Customer</h1>
    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="customer_name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
        <label>Email:</label>
        <input type="email" name="customer_email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
        <button type="submit" name="update_customer">Update Customer</button>
        <a href="admin.php" class="back-button">Kembali</a>
    </form>
</div>

</body>
</html>
