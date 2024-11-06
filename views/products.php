<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit(); 
}

include '../models/Database.php';
$db = new Database();
$conn = $db->connect();

$query = "SELECT * FROM products";
$result = $conn->query($query);
?>
<?php include "../templates/navbar.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Products</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0; /* Pastikan tidak ada padding di body */
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

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .product-item {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .product-item img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product-item h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .product-item p {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
        }

        .price {
            font-weight: bold;
            color: #e74c3c;
            font-size: 16px;
        }

        .stock {
            font-weight: bold;
            color: #27ae60;
            font-size: 16px;
        }

        .search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Products Tersedia</h1>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Cari produk..." onkeyup="searchProducts()" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
        </div>

        <a href="addProduct.php" class="btn">Tambah Product</a>
        <a href="categories.php" class="btn">Atur Kategori</a> 
        
        <div class="product-grid" id="productGrid">
            <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="product-item">
                <img src="../assets/images/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                <p class="price">Price: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                <p class="stock">Stock: <?php echo $row['stock']; ?></p>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <a href="editProduct.php?id=<?php echo $row['id']; ?>" class="btn">Edit Product</a>
            </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function searchProducts() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const productGrid = document.getElementById('productGrid');
            const products = productGrid.getElementsByClassName('product-item');

            for (let i = 0; i < products.length; i++) {
                const productName = products[i].getElementsByTagName('h2')[0].textContent.toLowerCase();
                if (productName.includes(input)) {
                    products[i].style.display = '';
                } else {
                    products[i].style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>
