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
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'admin'; // Set role to 'admin' for admin registration

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $stmt = $con->prepare("SELECT id FROM admin WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Username or email already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin into the database
            $stmt = $con->prepare("INSERT INTO admin (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $success_message = "Admin registered successfully!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="img/logo.jpg">
    <title>Admin Registration</title>
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

        .register-container {
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
    <div class="register-container">
        <h2>Admin Registration</h2>

        <!-- Display success or error messages -->
        <?php if (isset($success_message)): ?>
            <div class="message success">
                <p><?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="message error">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <form action="adminregister.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <button type="submit">Register</button>
        </form>

        <p><a href="adminlogin.php">Back to Login</a></p>
    </div>
</body>
</html>