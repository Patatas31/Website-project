<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['valid']) || !isset($_SESSION['id'])) {
    // Redirect to the login page if the user is not logged in
    echo "<script>alert('You must be logged in to place an order.'); window.location.href='login-index.php';</script>";
    exit();
}

include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get customer details from the session
    $customer_id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $phone = $_SESSION['phone'];

    // Get order details from the form
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity']; // Get the user-specified quantity

    // Insert the order into the database
    $stmt = $con->prepare("INSERT INTO orders (customer_id, name, phone, item_name, price, quantity, order_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isssdi", $customer_id, $name, $phone, $item_name, $price, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Item added to cart!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error placing order.'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $con->close();
}
?>