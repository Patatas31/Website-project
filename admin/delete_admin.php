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
    $admin_id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $con->prepare("DELETE FROM admin WHERE id = ?");
    $stmt->bind_param("i", $admin_id);

    if ($stmt->execute()) {
        // Redirect back to the admin management page with a success message
        header("Location: adminportal.php?message=Admin+deleted+successfully");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: adminportal.php?error=Failed+to+delete+admin");
        exit();
    }

    $stmt->close();
} else {
    // Redirect back if no ID is provided
    header("Location: adminportal.php?error=Invalid+request");
    exit();
}

$con->close();
?>