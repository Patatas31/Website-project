<?php
    session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="img/logo.jpg">
    <title>Log in</title>

    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="dyn.js" type="text/javascript" defer></script>
</head>

<body>
    <div class="container">
        <header>Log in</header>
        <p id="error-message"></p>

        <?php
            // Start session only if it's not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            include("database.php");

            if (isset($_POST['submit'])) {
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $password = mysqli_real_escape_string($con, $_POST['password']);

                $result = mysqli_query($con, "SELECT * FROM customer WHERE email = '$email'") or die("Select Error");
                $stmt = $con->prepare("SELECT * FROM customer WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);

                    if (password_verify($password, $row['password'])) {
                        $_SESSION['name'] = $row['name'];
                        $_SESSION['valid'] = $row['email'];
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['phone'] = $row['phone']; // Add this line
                        header("Location: index.php");
                        exit();
                    }
            
                        if ($row['role'] === 'admin') {
                            header("Location: index.php"); // Redirect to admin page
                        } else {
                            header("Location: index.php"); // Redirect to normal user homepage
                        }
                        exit();
                    } else {
                        echo "<div class='message'><p>Wrong email or password</p></div>";
                    }
                    
                
                } 
            
            ?>


       <form id="form" method="post">
            
            <div class="field input">
                <label for="Email">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280 320-200v-80L480-520 160-720v80l320 200Z"/></svg>
                </label>
                <input type="email" name="email" id="Email"  placeholder="Email">
            </div>

            <div class="field input">
                <label for="Password">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                </label>
                <input type="password" name="password" id="Password"  placeholder="Password">
            </div>

            <button type="submit" name="submit">Log in</button>

            <div class="link">
                Don't have an acount? <a href="signup-index.php"> Sign up</a>
            </div>
        </form>    
    </div>
</body>
</html>