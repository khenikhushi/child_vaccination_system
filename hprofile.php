<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

$sql = "SELECT  * FROM users WHERE id = ? AND role = 'hospital'";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$hospital = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Profile</title>
    <link rel="stylesheet" href="commamcss.css" />
    <!-- <link rel="stylesheet" href="footer.css"/> -->
    <style>
       
        h2 {
            color: #007bff;
            text-align: center;
        }
        .profile-info {
            margin: 10px 0;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header class="header">
        Hospital profile
    </header>
    <nav>
        <a href="hospital_dashboard.php">Home</a>
        <a href="vaccine.php">Add Vaccine Details</a>
        <a href="hospital_record.php">Appointment Details</a>
        <a href="hprofile.php">Profile</a>
        <a href="contactus.php">Contact Us</a>
        <a href="logout.php">Logout</a>
</nav>
    </body>
    <div class="container">
        
        <div class="profile-info">
            <label>Hospital Name:</label>
            <span id="hospitalName"></span>
             
            <?php echo isset($hospital['hospital_name']) ? htmlspecialchars($hospital['hospital_name']) : 'Not provided'; ?>
        </div>
        <div class="profile-info">
            <label>Emial id:</label>
            <span id="hospitalEmail"></span>
             
            <?php echo isset($hospital['email']) ? htmlspecialchars($hospital['email']) : 'Not provided'; ?>
        </div>
        
        <div class="profile-info">
            <label>Street:</label>
            <span id="hospitalStreet"></span>
            <?php echo isset($hospital['street']) ? htmlspecialchars($hospital['street']) : 'Not provided'; ?>
 
        </div>
        <div class="profile-info">
            <label>City:</label>
            <span id="hospitalCity"></span>
            <?php echo isset($hospital['city']) ? htmlspecialchars($hospital['city']) : 'Not provided'; ?>
 
        </div>
        <div class="profile-info">
            <label>State:</label>
            <span id="hospitalState"></span>
            <?php echo isset($hospital['state']) ? htmlspecialchars($hospital['state']) : 'Not provided'; ?>

        </div>
        <div class="profile-info">
            <label>Pincode:</label>
            <span id="hospitalPincode"></span>
            <?php echo isset($hospital['pincode']) ? htmlspecialchars($hospital['pincode']) : 'Not provided'; ?>
 
        </div>
                <div class="profile-info">
            <label>Opening Time:</label>
            <span>
                <?php echo isset($hospital['openingTime']) && !empty($hospital['openingTime']) 
                    ? htmlspecialchars($hospital['openingTime']) 
                    : 'Not provided'; ?>
            </span>
        </div>

        <div class="profile-info">
            <label>Closing Time:</label>
            <span>
                <?php echo isset($hospital['closingTime']) && !empty($hospital['closingTime']) 
                    ? htmlspecialchars($hospital['closingTime']) 
                    : 'Not provided'; ?>
            </span>
        </div>


        </div>
        </div>
        
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
