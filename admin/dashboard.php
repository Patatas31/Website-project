<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminlogin.php");
    exit();
}

// Include the database connection file
include __DIR__ . '/adatabase.php';

// Fetch total orders, revenue, users, and menu items
$total_orders = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM orders"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($con, "
    SELECT SUM(total_amount) as total 
    FROM (
        SELECT MIN(total_amount) as total_amount 
        FROM orders 
        GROUP BY customer_id
    ) as unique_orders
"))['total'];

$total_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM customer"))['total'];
$total_menu_items = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM menu"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="img/logo.jpg">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
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
        }

        .admin-container {
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

        h2 {
            color: #3D5300;
            margin-bottom: 20px;
            font-size: 24px;
        }

        p {
            color: #555;
            margin-bottom: 20px;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .stat h3 {
            color: #3D5300;
            margin-bottom: 10px;
        }

        .stat p {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        .logout-link {
            display: inline-block;
            margin-top: 20px;
            color: #dc3545;
            font-weight: 500;
        }

        .logout-link:hover {
            color: #c82333;
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
<body>
    <div class="admin-container">
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

        <h2>Admin Dashboard</h2>
        <p>Logged in as: <?php echo $_SESSION['email']; ?></p>

        <div class="dashboard-stats">
            <div class="stat">
                <h3>Total Orders</h3>
                <p><?php echo $total_orders; ?></p>
            </div>
            <div class="stat">
                <h3>Total Revenue</h3>
                <p>₱<?php echo number_format($total_revenue, 2); ?></p>
            </div>
            <div class="stat">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="stat">
                <h3>Total Menu Items</h3>
                <p><?php echo $total_menu_items; ?></p>
            </div>
        </div>
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