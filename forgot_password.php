<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Child Vaccination System</title>
    <link rel="stylesheet" href="commamcss.css">
    <style>
        button {
        background-color: #28a745;
        color: white;
        padding: 10px 18px;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 10px;
        }

        button:hover {
            background-color: #218838;
        }

        .button-link a {
            color: white;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            height: 100%;
        }

        .button-row {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .form-container {
            background: white;
            max-width: 500px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .error, .success {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }

        .error {
            background-color: #ffe6e6;
            color: #cc0000;
            border: 1px solid #cc0000;
        }

        .success {
            background-color: #e6ffea;
            color: #2d8a4f;
            border: 1px solid #2d8a4f;
        }

    </style>
</head>
<body>

    <header>
        Child Vaccination System
    </header>


    <div class="form-container">
        <h2>Forgot Password</h2>
        <p>Enter your registered email to receive a reset link.</p>

        <?php if (!empty($successMessage)) { ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php } ?>

        <?php if (!empty($emailError)) { ?>
            <div class="error"><?php echo $emailError; ?></div>
        <?php } ?>

        <form method="POST" action="forget_password.php">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="e.g. yourname@gmail.com" required>

        <div class="button-row">
        <button type="submit">Send Reset Link</button>
        <button class="button-link"><a href="home.php">Go to Home</a></button>
        </div>
        </form>

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
