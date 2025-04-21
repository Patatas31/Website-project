<?php
session_start();

// Database connection
$db_server = 'localhost';
$db_user = 'root';
$db_pass = 'mysqlsobrevilla';
$db_name = 'loginsig';

$con = new mysqli($db_server, $db_user, $db_pass, $db_name);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from database
    $stmt = $con->prepare("SELECT id, username, email, password, role FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $email, $hashed_password, $role);

    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        // Login successful
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;

        // Redirect based on role
        if ($role === 'admin') {
            header("Location: adminportal.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        // Login failed
        $error_message = "Invalid email or password.";
    }

    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="img/logo.jpg">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap">
    <style>
        /* General Styles */
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #3D5300;
            margin-bottom: 20px;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .message.error {
            background-color: #ffebee;
            color: #c62828;
        }

        .message.success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #3D5300;
            color: #FFE31A;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2a3a00;
        }

        a {
            color: #3D5300;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #2a3a00;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>

        <!-- Display error message if login fails -->
        <?php if (isset($error_message)): ?>
            <div class="message error">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <form action="adminlogin.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit" name="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="adminregister.php">Register here</a>.</p>
    </div>
</body>
</html>