<?php
session_start();
include __DIR__ . '/database.php';

// Fetch all menu items from the database
$result = mysqli_query($con, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="hmpg_style.css">
    <link rel="icon" type="image/jpg" href="img/logo.jpg">
    <title>Shawarma Corner Menu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <a href="#"><img src="img/logo.jpg" alt="logo" class="logo"></a>
        <nav>
            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#about_us">About Us</a></li>
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

    <section class="homepage-section">
        <div class="hero-content">
            <br>
            <h3>The First and the Cheapest<br>Original Buy 1 Take 1 <br>Shawarma in the <br>Philippines</h3>
            <p>Sa Shawarma Corner, "Buy One Take One Forever" <br>dahil "Mas Masaya ‘Pag Dalawa!"</p>
            <a href="#food"><button><span>Order Now</span><i class="fa-solid fa-arrow-right"></i></button></a>
        </div>
    </section>
    
    <section id="about_us" class="about-section">
        <div class="about-content">
            <h2>About Us</h2>
            <p>Welcome to <strong>Shawarma Corner</strong>, the home of the original Buy 1 Take 1 Shawarma in the Philippines! Since our inception, we've been committed to delivering delicious, affordable, and authentic shawarma to our customers. Our mission is simple: to make every meal a celebration with our flavorful and hearty dishes.</p>
            <p>At Shawarma Corner, we believe in quality, freshness, and value. Our ingredients are carefully selected, and our recipes are crafted to bring you the best taste in every bite. Whether you're craving a classic shawarma wrap or a hearty shawarma rice bowl, we've got you covered!</p>
        </div>
        <img src="img/Untitled design.png" alt="About Us Image">
    </section>

    <div class="menu" id="food">
        <div class="heading">
            <h1>Shawarma Corner</h1>
            <h3>&mdash; Menu &mdash;</h3>
        </div>

        <!-- Dynamic Menu Items -->
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="food-items">
                <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['item_name']; ?>">
                <div class="details">
                    <div class="details-sub">
                        <h5><?php echo $row['item_name']; ?></h5>
                        <h6 class="price">₱<?php echo $row['price']; ?></h6>
                    </div>
                    <p><?php echo $row['description']; ?></p>
                    <form action="order.php" method="POST">
                        <input type="hidden" name="item_name" value="<?php echo $row['item_name']; ?>">
                        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                        <input type="number" name="quantity" value="1" min="1">
                        <?php if (isset($_SESSION['valid'])): ?>
                            <button type="submit">Add to Cart</button>
                        <?php else: ?>
                            <button type="button" onclick="redirectToLogin()">Add to Cart</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

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
    </script>
</body>
</html>