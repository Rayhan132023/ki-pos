<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>POS Navbar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
        }

        .navbar {
            background-color: #2c3e50;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: 600;
            color: #fff;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .navbar ul li {
            position: relative;
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            padding: 8px 12px;
            transition: background-color 0.3s ease;
        }

        .navbar ul li a:hover {
            background-color: #3498db;
            border-radius: 5px;
        }

        .navbar ul li.active a {
            background-color: #2980b9;
            border-radius: 5px;
        }

        /* Responsive for smaller screens */
        @media (max-width: 768px) {
            .navbar ul {
                flex-direction: column;
                background-color: #2c3e50;
                position: absolute;
                top: 100%;
                right: 0;
                width: 100%;
                display: none;
                padding: 10px 0;
            }

            .navbar ul li {
                text-align: center;
            }

            .navbar ul li a {
                display: block;
                padding: 10px;
            }

            .navbar .toggle-menu {
                display: block;
                cursor: pointer;
                font-size: 24px;
                color: #fff;
            }
        }

        @media (min-width: 769px) {
            .navbar .toggle-menu {
                display: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">KEDAI SANG SURYA</div>
        <ul id="nav-menu">
            <li class="active"><a href="dashboard.php">Dashboard</a></li>
            <li><a href="transactions.php">Transactions</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="laporan.php">Laporan</a></li> 
            <li><a href="admin.php">Admin</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        <div class="toggle-menu" onclick="toggleMenu()">&#9776;</div>
    </nav>

    <script>
        function toggleMenu() {
            var menu = document.getElementById('nav-menu');
            if (menu.style.display === 'flex') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'flex';
            }
        }
    </script>
</body>
</html>
