<?php
session_start();
include __DIR__ . '/adatabase.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminlogin.php");
    exit();
}

// Get user ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("User ID is required.");
}

$user_id = intval($_GET['id']);
$query = "SELECT * FROM customer WHERE id = $user_id";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);

    $updateQuery = "UPDATE customer SET 
                    name='$name', 
                    email='$email', 
                    phone='$phone' 
                    WHERE id=$user_id";

    if (mysqli_query($con, $updateQuery)) {
        echo "<script>alert('User updated successfully.'); window.location.href='user_management.php';</script>";
    } else {
        echo "Error updating user: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="img/logo.jpg">
    <title>Edit User</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap">
    <style>
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
        }

        .edit-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        header {
        padding: 10px;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 8px;
        background-color: #3D5300;
        transition: 0.6s;
        z-index: 100000;
        transition: top 1s;
        margin-bottom: 20px;
    }
    
    .hidden {
        top: -200px; /* Adjust as necessary to fully hide */
        background-color: whitesmoke;
    }
    
    header .logo {
        position: relative;
        width: 120px;
        cursor: pointer;
        transition: 0.6s;
        
    }

    .nav-links {
        list-style: none;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .nav-links li {
        position: relative;
        display: inline-block;
        padding: 0px 20px;
    }

    .nav-links li a {
        position: relative;
        text-decoration: none;
        color: #FFE31A;
        font-size: 18px;
        letter-spacing: 2px;
        margin: 0 15px;
        transition: 0.6s;
        font-weight: bold;
    }

    .nav-links li a:hover {
        color: #F09319;
        font-weight: bold;
    }

        .nav-links li a:hover {
            color: #fff;
        }

        form{
            display: flex;
            flex-direction: column;
        }

        input, button{
            padding: 10px;
        }
        button{
            font-size: 15px;
            color:#FFE31A;;
            background-color: #3D5300;
            transform: background-color 2.5s;
            border: none;
            border-radius: 5px;
        }
        button:hover{
            font-size: 15px;
            color: #3D5300;
            background-color:#FFE31A;
            border: none;
        }
        .footer{
            background-color: #3D5300;
            padding: 10px 0;
            border-radius: 8px;
            margin-bottom: 20px;
           
        }
        .footer .footers{
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins' ;
            
        }

        .footer .footers a{
            color:  #FFE31A;
        }

        .footer .footers .Follow-me i{
            
            object-fit: contain;
            border-radius: 2px;
            padding-left: 10px;
        }

        .foot > p{
            color: #FFE31A
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <header>
            <a href="adminportal.php"><img src="img/logo.jpg" alt="logo" class="logo"></a>
            <nav>
                <ul class="nav-links">
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="user_management.php">User</a></li>
                    <li><a href="menu_management.php">Menu</a></li>
                    <li><a href="order_management.php">Order</a></li>
                    <li><a href="adminlogout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
        <h2>Edit User</h2>
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required><br>

            <button type="submit">Update</button>
        </form>
        <footer>
            <br><br>
            <section class="footer" id = "others">
                <div class="footers">
                    <div class="foot">
                        <p>&copy;Website by Group 12 - Polytechnic University of the Philippines</p>
                    </div>
                </div>
            </section>  
        </footer>
    </div>
</body>
</html>