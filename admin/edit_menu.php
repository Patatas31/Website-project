<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminlogin.php");
    exit();
}

// Include the database connection file
include __DIR__ . '/adatabase.php';

// Fetch the menu item to edit
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $query = "SELECT * FROM menu WHERE id = $edit_id";
    $result = mysqli_query($con, $query);
    $menu_item = mysqli_fetch_assoc($result);

    if (!$menu_item) {
        echo "Menu item not found.";
        exit();
    }
}

// Handle form submission for updating the menu item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle image upload (if a new image is provided)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'img/'; // Directory to store uploaded images
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create the directory if it doesn't exist
        }

        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Update the menu item with the new image path
            $query = "UPDATE menu SET item_name = '$item_name', price = '$price', description = '$description', image_path = '$image_path' WHERE id = $item_id";
        } else {
            echo "<div class='message error'>Failed to upload image.</div>";
        }
    } else {
        // Update the menu item without changing the image
        $query = "UPDATE menu SET item_name = '$item_name', price = '$price', description = '$description' WHERE id = $item_id";
    }

    if (mysqli_query($con, $query)) {
        header("Location: menu_management.php");
        exit();
    } else {
        echo "<div class='message error'>Failed to update menu item.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="img/logo.jpg">
    <title>Edit Menu Item</title>
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

        .admin-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #3D5300;
            color: #FFE31A;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2C3D00;
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
            padding: 12px;
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
</head>
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
        <h2>Edit Menu Item</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="item_id" value="<?php echo $menu_item['id']; ?>">
            
            <label for="item_name">Item Name:</label>
            <input type="text" name="item_name" value="<?php echo $menu_item['item_name']; ?>" required>
            
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" value="<?php echo $menu_item['price']; ?>" required>
            
            <label for="description">Description:</label>
            <textarea name="description" required><?php echo $menu_item['description']; ?></textarea>
            
            <label for="image">Image:</label>
            <input type="file" name="image" accept="image/*">
            <small>Current Image: <?php echo $menu_item['image_path']; ?></small>
            
            <button type="submit">Update Item</button>
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