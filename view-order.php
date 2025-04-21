<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['valid']) || !isset($_SESSION['id'])) {
    // Redirect to the login page if the user is not logged in
    echo "<script>alert('You must be logged in to view orders.'); window.location.href='login-index.php';</script>";
    exit();
}

include 'database.php';

// Handle form submission to update quantities or cancel orders
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_quantities'])) {
        foreach ($_POST['quantity'] as $order_id => $quantity) {
            // Validate quantity (must be a positive integer)
            if ($quantity > 0) {
                // Update the order quantity
                $stmt = $con->prepare("UPDATE orders SET quantity = ? WHERE id = ?");
                $stmt->bind_param("ii", $quantity, $order_id);
                $stmt->execute();
                $stmt->close();
            }
        }
        echo "<script>alert('Order quantities updated successfully!'); window.location.href='view-order.php';</script>";
    } elseif (isset($_POST['cancel_order'])) {
        $order_id = $_POST['order_id'];

        // Delete the order from the orders table
        $stmt = $con->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        if ($stmt->execute()) {
            echo "<script>alert('Order canceled successfully!'); window.location.href='view-order.php';</script>";
        } else {
            echo "<script>alert('Error canceling order.'); window.location.href='view-order.php';</script>";
        }
        $stmt->close();
    } elseif (isset($_POST['submit_order'])) {
        // Handle order submission with delivery details
        $customer_id = $_SESSION['id'];
        $shipping_option = $_POST['delivery'];
        $delivery_address = $_POST['deliveryaddress'];
        $delivery_notes = $_POST['deliverynotes'];
        $total_amount = 0;

        // Calculate total amount from orders
        $sql = "SELECT * FROM orders WHERE customer_id = ? AND shipping_option IS NULL";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $order_count = $result->num_rows; // Number of items in the order
        $delivery_fee_per_item = 0;

        if ($shipping_option == 'delivery' && $order_count > 0) {
            $delivery_fee_per_item = 30 / $order_count; // Distribute the delivery fee
        }

        while ($row = $result->fetch_assoc()) {
            $item_total = $row['price'] * $row['quantity'];
            $total_amount += $item_total + $delivery_fee_per_item;
        }

        // Update delivery details and total amount in the orders table
        $stmt = $con->prepare("UPDATE orders SET shipping_option = ?, delivery_address = ?, delivery_notes = ?, total_amount = ? WHERE customer_id = ? AND shipping_option IS NULL");
        $stmt->bind_param("sssdi", $shipping_option, $delivery_address, $delivery_notes, $total_amount, $customer_id);
        if ($stmt->execute()) {
            echo "<script>alert('Order submitted successfully!'); window.location.href='view-order.php';</script>";
        } else {
            echo "<script>alert('Error submitting order.'); window.location.href='view-order.php';</script>";
        }
        $stmt->close();
    }
}

// Fetch orders for the logged-in user
$customer_id = $_SESSION['id'];
$sql = "SELECT * FROM orders WHERE customer_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total amount
$total_amount = 0; // Reset total amount to 0
while ($row = $result->fetch_assoc()) {
    $total_amount += $row['price'] * $row['quantity'];
}

// Reset the result pointer to loop through the orders again
$result->data_seek(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="img/logo.jpg">
    <title>View Orders</title>
    <link rel="stylesheet" href="hmpg_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        
        /* Mobile-First Styling */
.orders-section {
    padding: 150px 20px 20px; 
    max-width: 100%;
    margin: 0 auto;
    overflow-x: auto;
}


.orders-section h2 {
    text-align: center;
    font-size: 24px; /* Smaller font for mobile */
    color: #3D5300;
    margin-bottom: 15px;
}

/* Scrollable table for small screens */
.orders-section table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: block;
    overflow-x: auto; /* Allow scrolling on small screens */
    white-space: nowrap; /* Prevent text wrapping */
    border-radius: 10px;
}

.orders-section th,
.orders-section td {
    padding: 10px;
    text-align: center;
    font-size: 14px;
}

.orders-section th {
    background-color: #3D5300;
    color: #FFE31A;
    font-weight: 600;
}

.orders-section tr:nth-child(even) {
    background-color: #f9f9f9;
}

.orders-section input[type="number"] {
    width: 50px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
}

/* Buttons */
.orders-section button {
    background-color: #F09319;
    border: none;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    border-radius: 5px;
    padding: 8px 12px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%; /* Full-width button for mobile */
}

.orders-section button:hover {
    background-color: #d87f12;
}

/* Delivery Section */
.delivery-section {
    background-color: #fff;
    padding: 15px;
    border-radius: 10px;
    margin-top: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.delivery-section textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: vertical;
}

/* Summary Section */
.summary {
    background-color: #fff;
    padding: 15px;
    border-radius: 10px;
    margin-top: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Larger Screens */
@media (min-width: 768px) {
    .orders-section {
        padding: 50px 20px;
    }

    .orders-section h2 {
        font-size: 28px;
    }

    .orders-section table {
        display: table; /* Show as normal table on larger screens */
        white-space: normal; /* Allow text wrapping */
    }

    .orders-section th,
    .orders-section td {
        padding: 12px 15px;
    }

    .orders-section input[type="number"] {
        width: 60px;
    }

    .orders-section button {
        width: auto;
    }

    .delivery-section,
    .summary {
        padding: 20px;
    }
}
/* Center buttons on mobile */
@media (max-width: 395px) {
    .orders-section {
        margin-top: 70px;
    }

    .submit-reset {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        max-width: 200px; /* Keeping it consistent */
        margin: 0 auto; /* Center alignment */
    }
}

.submit-reset {
    margin-top: 10px;
    max-width: 200px;
    display: flex;
    justify-content: end;
    gap: 10px; /* Adds spacing between buttons */
}

.submit-reset button {
    background-color: #F09319;
    border: none;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    border-radius: 5px;
    padding: 10px 20px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

.submit-reset button:hover {
    background-color: #d87f12;
}




    </style>

</head>
<body>
    <header>
        <img src="img/logo.jpg" alt="logo" class="logo">
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php">About Us</a></li>
                <li><a href="view-order.php">View Order</a></li>
                <?php if (isset($_SESSION['valid'])): ?>
                    <li><a href="logout.php">Logout</a></li>
                    <li class="user-info">Welcome, <?php echo $_SESSION['name']; ?></li>
                <?php else: ?>
                    <li><a href="login-index.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="orders-section">
        <h2>Your Orders</h2>
        <form method="POST" action="view-order.php">
        <table border="1">
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
                
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['item_name']; ?></td>
                    <td>₱<?php echo $row['price']; ?></td>
                    <td>
                        <input type="number" name="quantity[<?php echo $row['id']; ?>]" value="<?php echo $row['quantity']; ?>" min="1">
                    </td>
                    <td>₱<?php echo $row['price'] * $row['quantity']; ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                    <td><?php echo $row['shipping_option']; ?></td>
                    <td>
                        <form method="POST" action="view-order.php" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="cancel_order">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
            <p class="total-amount" style="text-align: center;">Total Amount: ₱<?php echo $total_amount; ?></p>
            <button type="submit" name="update_quantities">Update Quantities</button>
            
        </form>

        <!-- Delivery Details Section -->
        <section class="delivery-section" id="delivery">
            <span>Delivery Details</span> 
            <hr>
            <br>
            <div class="shippingFee"> 
                <h4>Shipping Options</h4>
                <input type="radio" name="delivery" onclick="updateSummary()" id="delivery1" value="delivery">
                <label for="delivery1">Delivery (+₱30)</label> <br>
                <input type="radio" name="delivery" onclick="updateSummary()" id="delivery2" value="pickup">
                <label for="delivery2">Third-party Pickup</label>
            </div>
            <br>
            <div class="delivery">
                <label for="addressDelivery">Delivery Address</label><br>
                <textarea name="deliveryaddress" rows="6" cols="105" id="addressDelivery" placeholder="Address here..." required></textarea><br>
            </div>
            <br>
            <div class="delivery">
                <label for="deliverynotes">Notes for Delivery Driver or Staff</label><br>
                <textarea name="deliverynotes" rows="6" cols="105" id="deliverynotes" placeholder="Notes here..."></textarea><br>
            </div>

            <section class="submit-reset">
                <br>
                <div class="submit">
                    <button type="submit" name="submit_order" id="submitbtn"> Submit </button>
                </div>
                <br>
                <div class="submit">
                    <button type="reset"> Reset </button>
                </div>
            </section>
        </section>

        <!-- Order Summary Section -->
        <section class="summary" id="content">
            <span>Summary of Your Order</span> 
            <p id="summaryContent"></p>
        </section>
    </section>

    <footer>
        <section class="footer" id="others">
            <div class="footers">
                <div class="phone">
                    <h3>Phone</h3>
                    <p>0977 843 0908</p>
                </div>
                <div class="Email">
                    <h3>Email</h3>
                    <a href="emailto:dion@gmail.com"><span>shawarmacorner@gmail.com</span></a>
                </div>
                <div class="Follow-me">
                    <h3>Follow me</h3>
                    <h3>in <a href="#"><i class="fa-brands fa-twitter"></i></a></h3>
                </div>
                <div class="Copyright">
                    <p>&copy; Personal website by Group 12</p>
                    <p>Polytechnic University of the Philippines</p>
                </div>
            </div>
        </section>
    </footer>

    <script>
        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > lastScrollY) {
                // Scrolling down
                header.classList.add('hidden');
            } else {
                // Scrolling up
                header.classList.remove('hidden');
            }
            lastScrollY = window.scrollY;
        });

        function redirectToLogin() {
            alert("You must be logged in to place an order.");
            window.location.href = "login-index.php";
        }
        // JavaScript for updating the order summary
        function updateSummary() {
        const delivery1 = document.getElementById('delivery1');
        const delivery2 = document.getElementById('delivery2');
        const summaryContent = document.getElementById('summaryContent');
        const totalAmount = <?php echo $total_amount; ?>;

        if (delivery1.checked) {
            summaryContent.innerHTML = `Shipping Option: Delivery (+₱30)<br>Total Amount: ₱${totalAmount + 30}`;
        } else if (delivery2.checked) {
            summaryContent.innerHTML = `Shipping Option: Third-party Pickup<br>Total Amount: ₱${totalAmount}`;
        }
}
    </script>
</body>
</html>

<?php
$stmt->close();
$con->close();
?>