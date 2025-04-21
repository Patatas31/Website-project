<?php
session_start(); // Start the session

include 'database.php';

// Initialize error and success messages
$error_message = '';
$success_message = '';

// Check if the form is submitted and prevent duplicate submissions
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_SESSION['form_submitted'])) {
    // Mark the form as submitted to prevent duplicate submissions
    $_SESSION['form_submitted'] = true;

    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $repeat_password = $_POST['Repeat-Password'];

    // Validate passwords
    if ($password !== $repeat_password) {
        $error_message = "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists
        $verify_query = mysqli_query($con, "SELECT email FROM customer WHERE email = '$email'");

        if (mysqli_num_rows($verify_query) != 0) {
            // If the email exists, show an error message
            $error_message = "This email is already used. Try another one!";
        } else {
            // Insert new user into the database using prepared statements
            $stmt = $con->prepare("INSERT INTO customer (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                // Redirect to a success page to prevent form resubmission
                header("Location: signup-success.php");
                exit();
            } else {
                $error_message = "Error Occurred!";
            }
        }
    }

    // Unset the form submission flag after processing
    unset($_SESSION['form_submitted']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="img/logo.jpg">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="dyn.js" type="text/javascript" defer></script>
</head>

<body>
    <div class="container">
        <header>Sign Up</header>
        <p id="error-message"></p>
        
        <!-- Display error or success messages -->
        <?php if (!empty($error_message)): ?>
            <div class="message error">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="message success">
                <p><?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>

        <form id="form" method="post">
            <div class="field input">
                <label for="Name">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                </label>
                <input type="text" name="name" id="Name" placeholder="Username" required>
            </div>

            <div class="field input">
                <label for="Email">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280 320-200v-80L480-520 160-720v80l320 200Z"/></svg>
                </label>
                <input type="email" name="email" id="Email" placeholder="Email" required>
            </div>

            <div class="field input">
                <label for="Phone">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M798-120q-125 0-247-54.5T329-329Q229-429 174.5-551T120-798q0-18 12-30t30-12h162q14 0 25 9.5t13 22.5l26 140q2 16-1 27t-11 19l-97 98q20 37 47.5 71.5T387-386q31 31 65 57.5t72 48.5l94-94q9-9 23.5-13.5T670-390l138 28q14 4 23 14.5t9 23.5v162q0 18-12 30t-30 12ZM241-600l66-66-17-94h-89q5 41 14 81t26 79Zm358 358q39 17 79.5 27t81.5 13v-88l-94-19-67 67ZM241-600Zm358 358Z"/></svg>
                </label>
                <input type="tel" name="phone" id="Phone" placeholder="0000 000 0000"
                    inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,11);" required>
            </div>

            <div class="field input">
                <label for="Password">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                </label>
                <input type="password" name="password" id="Password" placeholder="Password" required>
            </div>

            <div class="field input">
                <label for="Repeat-Password">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                </label>
                <input type="password" name="Repeat-Password" id="RepeatPassword" placeholder="Repeat Password" required>
            </div>

            <button type="submit" name="submit">Create an account</button>

            <div class="link">
                Already have an account? <a href="login-index.php">Log in</a>
            </div>
        </form>
    </div>
</body>
</html>