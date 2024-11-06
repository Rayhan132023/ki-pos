<?php
// dashboard.php
include '../models/Database.php';
include "../templates/navbar.php";

// Membuat koneksi database
$db = new Database();
$conn = $db->connect(); // Pastikan $conn terinisialisasi di sini

// Mengambil total penjualan hari ini
$result_today = mysqli_query($conn, "SELECT SUM(total_payment) AS total_today FROM orders WHERE DATE(created_at) = CURDATE()");
$total_today = mysqli_fetch_assoc($result_today)['total_today'] ?? 0;

// Mengambil total penjualan bulan ini
$result_month = mysqli_query($conn, "SELECT SUM(total_payment) AS total_month FROM orders WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
$total_month = mysqli_fetch_assoc($result_month)['total_month'] ?? 0;

// Mengambil total penjualan minggu ini
$result_week = mysqli_query($conn, "SELECT SUM(total_payment) AS total_week FROM orders WHERE WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
$total_week = mysqli_fetch_assoc($result_week)['total_week'] ?? 0;

$best_sellers = mysqli_query($conn, "
    SELECT p.id, p.name, SUM(op.quantity) AS total_quantity 
    FROM products p
    JOIN order_products op ON p.id = op.product_id
    GROUP BY p.id
    ORDER BY total_quantity DESC
    LIMIT 5
");
?>

<div class="container">
    <h1>Dashboard Penjualan</h1>
    
    <div class="card-container">
        <div class="card card-today">
            <h2>Hasil Penjualan Hari Ini</h2>
            <p>Rp <?php echo number_format($total_today, 0, ',', '.'); ?></p>
        </div>
        <div class="card card-month">
            <h2>Hasil Penjualan Bulan Ini</h2>
            <p>Rp <?php echo number_format($total_month, 0, ',', '.'); ?></p>
        </div>
        <div class="card card-week">
            <h2>Hasil Penjualan Minggu Ini</h2>
            <p>Rp <?php echo number_format($total_week, 0, ',', '.'); ?></p>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="salesChart"></canvas>
    </div>

    <div class="customer-list">
        <h2>Daftar Customer Baru</h2>
        <input type="text" id="customerSearch" placeholder="Cari Customer...">
        <table id="customer-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY id DESC LIMIT 5");
                while ($row = mysqli_fetch_assoc($customers)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div><br>

    <div class="best-sellers">
        <h2>Produk Terlaris</h2>
        <table id="best-sellers-table">
            <thead>
                <tr>
                    <th>ID Produk</th>
                    <th>Nama Produk</th>
                    <th>Total Terjual</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($best_sellers)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_quantity']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    body {
        background-color: #f4f4f4;
        font-family: 'Poppins', sans-serif;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .card-container {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        margin: 10px;
        flex: 1;
        min-width: 250px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .chart-container {
        margin: 20px 0;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .customer-list {
        margin-top: 20px;
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #dee2e6;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f1f1f1;
        color: #333;
    }

    @media (max-width: 768px) {
        .card-container {
            flex-direction: column;
        }
    }

    #customerSearch {
        margin-bottom: 10px;
        padding: 8px;
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const salesData = {
        labels: ['Hari Ini', 'Minggu Ini', 'Bulan Ini'],
        datasets: [{
            label: 'Total Penjualan',
            data: [<?php echo $total_today; ?>, <?php echo $total_week; ?>, <?php echo $total_month; ?>],
            backgroundColor: ['#28a745', '#dc3545', '#6f42c1'],
        }]
    };

    const config = {
        type: 'bar',
        data: salesData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    };

    new Chart(document.getElementById('salesChart'), config);

    document.getElementById('customerSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let table = document.getElementById('customer-table');
        let tr = table.getElementsByTagName('tr');
        
        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName('td')[1];
            if (td) {
                let txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
            }
        }
    });
</script>
</body>
</html>
