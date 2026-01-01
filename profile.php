<?php
session_start();
include('connection.php'); 

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$query = "SELECT * FROM users WHERE id = ? and role='parent'";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

$stmt->close();
$connection->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Profile</title>
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
    <header class="header">Child Profile</header>
    <nav>
    
        <a href="anganwadi-details.php">Anganwadi</a>
        <a href="showvaccine.php">Vaccine Details</a>
        <a href="appointment.php">Appointment</a>
        <a href="schedule.php">Schedule</a>
        <a href="profile.php">profile</a>
        <a href="contactus.php">Contact Us</a>
        <a href="logout.php">logout</a>
    
      
    </nav>
    <div class="container">
        
        <div class="profile-info">
            <label>Child Name:</label>
            <span id="firstName"></span>
            <?php
            echo $user['child_name'];?>   
        </div>
        <div class="profile-info">
            <label>Gender:</label>
            <span id="lastName"></span>
            <?php
            echo $user['gender'];?> 
        </div>
        <div class="profile-info">
            <label>D.O.B</label>
            <span id="bod"></span>
            <?php
            echo $user['child_dob'];?> 
        </div>
        <div class="profile-info">
            <label>Parent Name:</label>
            <span id="parentName"></span>
            <?php
            echo $user['name'];?> 
        </div>
        <div class="profile-info">
            <label>Address:</label>
            <span id="address"></span>
            <?php
            echo $user['parent_address'];?> 
        </div>
        <div class="profile-info">
            <label>Contact Number:</label>
            <span id="contactNumber"></span>
            <?php
            echo $user['parent_contact'];?> 
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
