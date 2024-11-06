<?php
include '../models/Database.php';
session_start();

$db = new Database();
$conn = $db->connect(); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO admins (email, password) VALUES ('$email', '$password')");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_customer'])) {
    $name = $_POST['customer_name'];
    $email = $_POST['customer_email'];
    mysqli_query($conn, "INSERT INTO customers (name, email) VALUES ('$name', '$email')");
}

$admins = mysqli_query($conn, "SELECT * FROM admins");
$customers = mysqli_query($conn, "SELECT * FROM customers");
?>

<?php include '../templates/navbar.php'; ?>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f0f4f8;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: auto;
        padding: 30px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #343a40;
        margin-bottom: 20px;
        font-size: 2.5em;
        font-weight: 700;
    }

    h2 {
        color: #007BFF;
        margin-top: 30px;
        font-size: 1.8em;
        font-weight: 600;
    }

    form {
        margin-bottom: 30px;
        background: #e9ecef;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="email"],
    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        transition: border-color 0.3s;
    }

    input[type="email"]:focus,
    input[type="text"]:focus,
    input[type="password"]:focus {
        border-color: #007BFF;
        outline: none;
    }

    button {
        background-color: #007BFF;
        color: #fff;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #007BFF;
        color: white;
    }

    tr:hover {
        background-color: #f2f2f2;
    }

    .action-links a {
        margin-right: 15px;
        color: #007BFF;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s;
    }

    .action-links a:hover {
        text-decoration: underline;
        color: #0056b3;
    }

    @media (max-width: 768px) {
        h1 {
            font-size: 2em;
        }

        h2 {
            font-size: 1.5em;
        }
    }
</style>

<div class="container">
    <h1>Halaman Admin</h1>

    <h2>Tambah Admin</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="add_admin">Tambah Admin</button>
    </form>

    <h2>Daftar Admin</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($admin = mysqli_fetch_assoc($admins)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($admin['id']); ?></td>
                    <td><?php echo htmlspecialchars($admin['email']); ?></td>
                    <td class="action-links">
                        <a href="edit_admin.php?id=<?php echo $admin['id']; ?>">Edit</a>
                        <a href="delete_admin.php?id=<?php echo $admin['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus admin ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Tambah Customer</h2>
    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="customer_name" required>
        <label>Email:</label>
        <input type="email" name="customer_email" required>
        <button type="submit" name="add_customer">Tambah Customer</button>
    </form>

    <h2>Daftar Customer</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($customer = mysqli_fetch_assoc($customers)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($customer['id']); ?></td>
                    <td><?php echo htmlspecialchars($customer['name']); ?></td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                    <td class="action-links">
                        <a href="edit_customer.php?id=<?php echo $customer['id']; ?>">Edit</a>
                        <a href="delete_customer.php?id=<?php echo $customer['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
