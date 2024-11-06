<?php
session_start();
include '../models/Database.php';

// Pastikan pengguna sudah login sebagai admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$db = new Database();
$conn = $db->connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM admins WHERE id = $id");
    $admin = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_admin'])) {
    $email = $_POST['email'];
    mysqli_query($conn, "UPDATE admins SET email = '$email' WHERE id = $id");
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

    input[type="email"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        transition: border-color 0.3s;
    }

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
    <h1>Edit Admin</h1>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
        <button type="submit" name="update_admin">Update Admin</button>
        <a href="admin.php" class="back-button">Kembali</a>
    </form>
</div>

</body>
</html>
