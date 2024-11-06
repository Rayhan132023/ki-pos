<?php
include '../models/Database.php';

session_start();

$db = new Database();
$conn = $db->connect(); 

include '../templates/navbar.php';

$categories = mysqli_query($conn, "SELECT * FROM categories");
$products = mysqli_query($conn, "SELECT * FROM products WHERE stock > 0");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_order'])) {
    $customer_id = $_POST['customer_id'];
    $total_payment = 0;
    $total_product = 0;

    $query = "INSERT INTO orders (admin_id, customer_id, total_payment, total_product) VALUES (1, '$customer_id', '$total_payment', '$total_product')";
    mysqli_query($conn, $query);
    $order_id = mysqli_insert_id($conn);

    $products_exist = false; 

    foreach ($_POST['products'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $products_exist = true;
            $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id'"));
            if ($product['stock'] >= $quantity) {
                $total_price = $product['price'] * $quantity;
                $total_payment += $total_price;
                $total_product += $quantity;

                $new_stock = $product['stock'] - $quantity;
                mysqli_query($conn, "UPDATE products SET stock='$new_stock' WHERE id='$product_id'");

                mysqli_query($conn, "INSERT INTO order_products (order_id, product_id, quantity, total_price) VALUES ('$order_id', '$product_id', '$quantity', '$total_price')");
            } else {
                echo "Stok produk tidak cukup untuk: " . htmlspecialchars($product['name']);
            }
        }
    }

    if ($products_exist) {
        mysqli_query($conn, "UPDATE orders SET total_payment='$total_payment', total_product='$total_product' WHERE id='$order_id'");
        header('Location: laporan.php');
        exit;
    } else {
        echo "Tidak ada produk yang dipilih untuk transaksi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Transaksi</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
        }
        .container {
            width: 90%;
            margin: auto;
            display: flex;
            flex-direction: row;
            gap: 30px;
        }
        .products-section {
            flex: 3;
        }
        .products-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .product-item {
            width: calc(33.333% - 20px);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .product-item:hover {
            transform: translateY(-10px);
        }
        .product-item img {
            max-width: 100px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .product-item h4 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .product-item p {
            font-size: 14px;
            color: #888;
        }
        .product-item button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .product-item button:hover {
            background-color: #0056b3;
        }

        .receipt-section {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: relative;
            margin-top: 50px; 
        }
        .receipt {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 350px;
            overflow-y: auto;
        }
        .receipt-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .total {
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            text-align: right;
        }
        .order-btn {
            background-color: #28a745;
            color: #fff;
            padding: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        .order-btn:hover {
            background-color: #218838;
        }
        .minus-btn {
            background-color: #e74c3c;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .minus-btn:hover {
            background-color: #c0392b;
        }

        @media (max-width: 768px) {
            .product-item {
                width: calc(50% - 20px);
            }
        }
        @media (max-width: 480px) {
            .product-item {
                width: calc(100% - 20px);
            }
        }
        .customer-select {
            margin-bottom: 20px;
        }
        .customer-select select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="products-section">
        <h1>Transaksi</h1>
        <div class="customer-select">
            <label>Customer:</label>
            <select name="customer_id" required form="order-form">
                <?php
                $customers = mysqli_query($conn, "SELECT * FROM customers");
                while ($customer = mysqli_fetch_assoc($customers)) {
                    echo "<option value='{$customer['id']}'>{$customer['name']}</option>";
                }
                ?>
            </select>
        </div>

        <form method="POST" id="order-form">
            <div class="products-grid">
                <?php while ($product = mysqli_fetch_assoc($products)): ?>
                    <div class="product-item">
                        <img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                        <p>Stok: <?php echo $product['stock']; ?></p>
                        <p>Harga: Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                        <button type="button" class="add-to-receipt" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>" data-price="<?php echo $product['price']; ?>">Beli</button>
                        <input type="hidden" name="products[<?php echo $product['id']; ?>]" value="0" id="quantity-<?php echo $product['id']; ?>">
                    </div>
                <?php endwhile; ?>
            </div>
        </form>
    </div>

    <div class="receipt-section">
        <h2>Struk Order</h2>
        <ul class="receipt" id="receipt"></ul>
        <div class="total" id="total-amount">Total: Rp 0</div>
        <button type="submit" name="submit_order" form="order-form" class="order-btn">Pesan</button>
    </div>
</div>

<script>
    const receipt = document.getElementById('receipt');
    const totalAmount = document.getElementById('total-amount');
    let total = 0;

    document.querySelectorAll('.add-to-receipt').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.id;
            const name = this.dataset.name;
            const price = parseInt(this.dataset.price);

            const quantityInput = document.getElementById('quantity-' + productId);
            let quantity = parseInt(quantityInput.value) + 1;
            quantityInput.value = quantity;

            total += price;
            totalAmount.textContent = 'Total: Rp ' + total.toLocaleString('id-ID');

            const existingItem = document.querySelector(`[data-receipt-id="${productId}"]`);
            if (existingItem) {
                existingItem.querySelector('.quantity').textContent = quantity;
            } else {
                const listItem = document.createElement('li');
                listItem.classList.add('receipt-item');
                listItem.setAttribute('data-receipt-id', productId);
                listItem.innerHTML = `
                    <span>${name}</span>
                    <span>Qty: <span class="quantity">${quantity}</span></span>
                    <button type="button" class="minus-btn" onclick="decreaseQuantity('${productId}', ${price})">-</button>
                `;
                receipt.appendChild(listItem);
            }
        });
    });

    function decreaseQuantity(productId, price) {
        const quantityInput = document.getElementById('quantity-' + productId);
        let quantity = parseInt(quantityInput.value) - 1;
        
        if (quantity >= 0) {
            quantityInput.value = quantity;
            total -= price;
            totalAmount.textContent = 'Total: Rp ' + total.toLocaleString('id-ID');

            const item = document.querySelector(`[data-receipt-id="${productId}"]`);
            if (quantity > 0) {
                item.querySelector('.quantity').textContent = quantity;
            } else {
                item.remove();
            }
        }
    }
</script>

</body>
</html>
