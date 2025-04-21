<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminlogin.php");
    exit();
}

// Include the database connection file
include __DIR__ . '/adatabase.php';

// Check if the ID parameter is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $con->prepare("DELETE FROM customer WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Redirect back to the user management page with a success message
        header("Location: user_management.php?message=User+deleted+successfully");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: user_management.php?error=Failed+to+delete+user");
        exit();
    }

    $stmt->close();
} else {
    // Redirect back if no ID is provided
    header("Location: user_management.php?error=Invalid+request");
    exit();
}

$con->close();
?>