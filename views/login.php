<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #2c3e50, #3498db);
        }

        .container {
            background: rgba(255, 255, 255, 0.3); 
            backdrop-filter: blur(5px);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .form-box {
            text-align: center;
        }

        h2 {
            color: #fff;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            color: #fff;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            margin-top: 8px;
            border: none;
            border-radius: 10px;
            outline: none;
            background: rgba(255, 255, 255, 0.9); 
            color: #333; 
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
        }

        .form-group input:focus {
            background: rgba(255, 255, 255, 1); 
            border: 2px solid #3498db; 
        }

        .btn {
            width: 100%;
            padding: 12px 15px;
            border: none;
            border-radius: 10px;
            background-color: #3498db;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
        }

        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="../controllers/loginController.php" method="POST" class="form-box">
            <h2>Login Admin</h2>
            <div class="form-group">
                <label>Email:</label>
                <input name="email" type="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input name="password" type="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Sign In</button>
        </form>
    </div>
</body>
</html>
