<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../models/Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryName = trim($_POST['category_name']);
    
    if (!empty($categoryName) && preg_match('/^[a-zA-Z0-9\s]+$/', $categoryName)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $categoryName);
        
        if ($stmt->execute()) {
            $successMessage = "Kategori berhasil ditambahkan!";
        } else {
            $errorMessage = "Gagal menambahkan kategori: " . $conn->error;
        }
        $stmt->close();
    } else {
        $errorMessage = "Nama kategori tidak valid!";
    }
}

if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$query = "SELECT * FROM categories";
$result = $conn->query($query);
?>

<?php include "../templates/navbar.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Atur Kategori</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0; 
        }

        .navbar {
            padding: 10px;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
            margin-right: 20px;
        }

        .navbar a:hover {
            background-color: #0056b3;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .category-list {
            margin-top: 20px;
            padding: 0;
            list-style-type: none;
        }

        .category-list li {
            padding: 10px;
            background: #f8f9fa;
            margin: 5px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .message {
            color: green;
            text-align: center;
        }

        .error-message {
            color: red;
            text-align: center;
        }

        .back-btn {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Atur Kategori</h1>

        <?php if (isset($successMessage)) { ?>
            <div class="message"><?php echo $successMessage; ?></div>
        <?php } ?>
        
        <?php if (isset($errorMessage)) { ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php } ?>

        <form action="" method="post">
            <input type="text" name="category_name" placeholder="Nama Kategori" required>
            <button type="submit" class="btn">Tambah Kategori</button>
        </form>

        <h2>Daftar Kategori</h2>
        <ul class="category-list">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li>
                    <?php echo htmlspecialchars($row['name']); ?>
                    <a href="?delete_id=<?php echo $row['id']; ?>" class="btn" style="background-color: #e74c3c;">Hapus</a>
                </li>
            <?php } ?>
        </ul>

        <div class="back-btn">
            <a href="products.php" class="btn">Kembali </a>
        </div>
    </div>
</body>
</html>
