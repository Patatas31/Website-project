<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminlogin.php");
    exit();
}

// Include the database connection file
include __DIR__ . '/adatabase.php';

// Handle order shipping option update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status']; // This is actually the shipping_option in DB
    mysqli_query($con, "UPDATE orders SET shipping_option = '$status' WHERE id = $order_id");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="img/logo.jpg">
    <title>Order Management</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            text-align: center;
            justify-content: space-between;
            padding: 12px;
            padding-left: 20px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3D5300;
            color: #FFE31A;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f1f1f1;
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

        <h2>Order Management</h2>
        <p>Logged in as: <?php echo $_SESSION['email']; ?></p>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>U-ID</th>
                    <th>Customer Name</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all orders from the database
                $result = mysqli_query($con, "SELECT id, name, customer_id,item_name, quantity, order_date, total_amount, shipping_option FROM orders");
                while ($row = mysqli_fetch_assoc($result)) {
                    $status = isset($row['shipping_option']) ? $row['shipping_option'] : 'N/A'; 
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['customer_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['item_name']}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['order_date']}</td>
                            <td>₱{$row['total_amount']}</td>
                            <td>{$status}</td>
                            <td>
                                <form method='POST'>
                                    <input type='hidden' name='order_id' value='{$row['id']}'>
                                    <select name='status'>
                                        <option value='ordered' " . ($status == 'ordered' ? 'selected' : '') . ">Ordered</option>
                                        <option value='on_delivery' " . ($status == 'on_delivery' ? 'selected' : '') . ">On Delivery</option>
                                        <option value='delivered' " . ($status == 'delivered' ? 'selected' : '') . ">Delivered</option>
                                        <option value='cancelled' " . ($status == 'cancelled' ? 'selected' : '') . ">Cancelled</option>
                                    </select>
                                    <button type='submit'>Update</button>
                                </form>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
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
