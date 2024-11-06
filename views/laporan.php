<?php
include '../models/Database.php';
include "../templates/navbar.php";

$db = new Database();
$conn = $db->connect(); 

$reports = mysqli_query($conn, "
    SELECT orders.*, admins.email AS admin_email, customers.name AS customer_name 
    FROM orders 
    JOIN admins ON orders.admin_id = admins.id 
    JOIN customers ON orders.customer_id = customers.id
");

$total_payment = 0; 
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #007BFF;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ccc;
    }

    th {
        background-color: #007BFF;
        color: white;
    }

    tr:hover {
        background-color: #f2f2f2;
    }

    .total-row {
        font-weight: bold;
        background-color: #e9ecef;
    }

    .print-button {
        margin-bottom: 20px;
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    @media print {
        body {
            color: black;
            background: white;
        }
    }

    @media (max-width: 600px) {
        table {
            font-size: 14px;
        }
    }
</style>

<div class="container">
    <h1>Laporan Transaksi</h1>
    <button class="print-button" onclick="window.print()">Cetak Laporan</button>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Admin</th>
                <th>Customer</th>
                <th>Total Pembayaran</th>
                <th>Total Produk</th>
                <th>Waktu</th>
                <th>Detail Produk</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($reports)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['admin_email']); ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_payment']); $total_payment += $row['total_payment']; ?></td>
                    <td><?php echo htmlspecialchars($row['total_product']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <?php
                        $order_id = $row['id'];
                        $product_details = mysqli_query($conn, "
                            SELECT products.name, order_products.quantity, order_products.total_price 
                            FROM order_products 
                            JOIN products ON order_products.product_id = products.id 
                            WHERE order_products.order_id = '$order_id'
                        ");
                        while ($product = mysqli_fetch_assoc($product_details)) {
                            echo htmlspecialchars($product['name']) . " (Qty: " . htmlspecialchars($product['quantity']) . ") - Rp " . htmlspecialchars($product['total_price']) . "<br>";
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3">Total Pembayaran</td>
                <td>Rp <?php echo number_format($total_payment, 0, ',', '.'); ?></td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</div>

</body>
</html>
