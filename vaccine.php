<?php
session_start();
include("connection.php");

// Ensure only logged-in hospitals can access
// if (!isset($_SESSION['user']['id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hospital') {
//     echo "<script>alert('Unauthorized access. Please login as a hospital.'); window.location.href='login.php';</script>";
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_vaccine'])) {
    $h_id = $_SESSION['user']['id']; // Hospital's ID
    $name = trim($_POST['name']);
    $recommended_age = trim($_POST['recommended_age']);
    $dose_stock = intval($_POST['stock']);
    $doses = intval($_POST['doses']); // NEW field
    $purpose = trim($_POST['purpose']);

    // Insert into vaccines table
    $stmt = $connection->prepare("INSERT INTO vaccines (name, recommended_age, purpose, stock, doses, h_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiii", $name, $recommended_age, $purpose, $dose_stock, $doses, $h_id);

    if ($stmt->execute()) {
        echo "<script>alert('Vaccine added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding vaccine: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vaccine Details - Child Vaccination System</title>
    <link rel="stylesheet" href="commamcss.css" />
    <style>
        #addVaccine {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            max-width: 600px;
            margin: 40px auto;
        }

        .form-row {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }

        .form-row label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .form-row input {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .add-btn {
            background-color: #28a745;
            color: white;
            padding: 12px;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }

        .add-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<header class="header">Vaccine Details</header>

<nav>
    <a href="hospital_dashboard.php">Home</a>
    <a href="vaccine.php">Add Vaccine Details</a>
    <a href="hospital_record.php">Appointment Details</a>
    <a href="hprofile.php">Profile</a>
    <a href="contactus.php">Contact Us</a>
    <a href="logout.php">Logout</a>
</nav>

<div id="content">
    <div id="addVaccine">
        <h3>Add New Vaccine</h3>
        <form method="POST" action="vaccine.php">
            <div class="form-row">
                <label for="name">Vaccine Name</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-row">
                <label for="recommended_age">Recommended Age</label>
                <input type="text" name="recommended_age" id="recommended_age" required>
            </div>

            <div class="form-row">
                <label for="doses">Doses:</label>
                <input type="number" name="doses" id="doses" min="1" required>
            </div>


            <div class="form-row">
                <label for="stock">Stock Available</label>
                <input type="number" name="stock" id="stock" min="0" required>
            </div>

            
            <div class="form-row">
                <label for="purpose">Purpose</label>
                <input type="text" name="purpose" id="purpose" required>
            </div>

            <button type="submit" name="add_vaccine" class="add-btn">Add Vaccine</button>
        </form>
    </div>
</div>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-section contact-info">
            <h3>Contact Us</h3>
            <p><strong>Phone:</strong> +91-9876543210</p>
            <p><strong>Email:</strong> support@childvaccine.gov.in</p>
            <p><strong>Address:</strong> Ministry of Health & Family Welfare,<br> Government of India,<br> New Delhi - 110001</p>
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
