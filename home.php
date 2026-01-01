<?php
session_start();
$loggedIn = isset($_SESSION['email']);
$role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Child Vaccination System</title>
    <link rel="stylesheet" href="commamcss.css" />
    <!-- <link rel="stylesheet" href="footer.css"/> -->

   
</head>
<body>

    <header class="header">
        Child Vaccination System
    </header>

    <nav>
        <a href="home.php">Home</a>
        
        <?php if ($loggedIn): ?>
            <?php if ($role === 'parent'): ?>
                <a href="appointment.php">Appointment</a>
            <?php elseif ($role === 'hospital'): ?>
                <a href="hospital/dashboard.php">Dashboard</a>
            <?php elseif ($role === 'admin'): ?>
                <a href="admin/anganwadi.php">Admin Panel</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
            
            <a href="aboutus.html">About us</a>
        <?php endif; ?>
    </nav>

    <div class="container">
        <h2>Welcome to the Child Vaccination System</h2>
        <p>Our system helps parents track and schedule vaccinations for their children.</p>
    </div>
    <footer class="footer">

    <div class="footer-container">
        <div class="footer-section contact-info">
            <h3>Contact Us</h3>
            <p><strong>Phone:</strong> +91-9876543210</p>
            <p><strong>Email:</strong> support@childvaccine.gov.in</p>
            <p><strong>Address:</strong> Ministry of Health & Family Welfare,<br>
                Government of India,<br>
                New Delhi - 110001</p>
        </div>

        <div class="footer-section quick-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="privacy.html">Privacy Policy</a></li>
                <li><a href="terms.html">Terms & Conditions</a></li>
            </ul>
        </div>

        <div class="footer-section follow-us">
            <h3>Follow Us</h3>
            <p>
                <a href="#">Facebook</a> | 
                <a href="#">Twitter</a> | 
                <a href="#">Instagram</a> | 
                <a href="#">YouTube</a>
            </p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 Child Vaccination System. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
