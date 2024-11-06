<?php include "../templates/navbar.php"; ?>

<?php
include '../models/Database.php';
$db = new Database();
$conn = $db->connect();

$query = "SELECT * FROM categories";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../controllers/assets/style.css">
    <title>Add Product</title>
</head>
<body>
    <div class="container">
        <form action="../controllers/productController.php" method="POST" enctype="multipart/form-data" class="form-box">
            <h2>Add Product</h2>
            <div class="form-group">
                <label>Product Name:</label>
                <input name="name" type="text" required>
            </div>
            <div class="form-group">
                <label>Category:</label>
                <select name="category_id" required>
                    <?php while ($category = $result->fetch_assoc()) { ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Price:</label>
                <input name="price" type="number" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Stock:</label>
                <input name="stock" type="number" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="form-group">
                <label>Upload Image:</label>
                <input name="image" type="file" required>
            </div>
            <button type="submit" class="btn">Add Product</button>
        </form>
    </div>
</body>
</html>
