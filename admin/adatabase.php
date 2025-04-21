<?php
// Database credentials
$db_server = 'localhost';
$db_user = 'root';
$db_pass = 'mysqlsobrevilla';
$db_name = 'loginsig';

try {
    // Enable mysqli to throw exceptions for better error handling
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Establish database connection
    
    $con = new mysqli($db_server, $db_user, $db_pass, $db_name);

    // If connection is successful
    
} catch (mysqli_sql_exception $e) {
    // Handle connection failure
    echo "Could not connect to the database. Error: " . $e->getMessage();
}
?>
