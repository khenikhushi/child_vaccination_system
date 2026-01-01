<?php
include('connection.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['centerName'];
    $street = $_POST['centerStreet'];
    $city = $_POST['centerCity'];
    $state = $_POST['centerState'];
    $pincode = $_POST['centerPincode'];
    $openingTime = $_POST['centerTime'];
    $closingTime = $_POST['centerClosingTime'];

    if (!preg_match("/^[1-9][0-9]{5}$/", $pincode)) {
        echo "<script>alert('Invalid Pincode!');</script>";
    } else {
   
        $stmt = $connection->prepare("INSERT INTO anganwadi_centers (name, street, city, state, pincode, openingTime, closingTime, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'not registered')");
        $stmt->bind_param("sssssss", $name, $street, $city, $state, $pincode, $openingTime, $closingTime);

        if ($stmt->execute()) {
            echo "<script>alert('Anganwadi Center added successfully.');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $connection->query("DELETE FROM anganwadi_centers WHERE id = $id");
    echo "<script>window.location.href='aanganwadi.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title> Add Anganwadi Centers</title>
    <link rel="stylesheet" href="commamcss.css" />
    <!-- <link rel="stylesheet" href="footer.css"/> -->
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .delete-btn {
            background: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background: darkred;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
            max-width: 500px;
            margin: auto;
        }
        .input-group label {
            font-weight: bold;
        }
        .input-group input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        .add-btn {
            background: green;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .add-btn:hover {
            background: darkgreen;
        }
        .home-btn { /* Style for the home button */
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .home-btn:hover {
            background: #0056b3;
        }
        .button-container { /* Container for buttons to align them */
            display: flex;
            justify-content: center;
            gap: 10px; /* Space between buttons */
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header class="header"> Add Anganwadi Centers</header>
    <nav>
            <a href="adashboard.php">Dashboard</a>
            <a href="aanganwadi.php">Add Anganwadi Center</a>
            <a href="display.php">Complains</a>
            <a href="logout.php">Logout</a>

    </nav>
    <div class="container">
    

        <h3>Add a New Anganwadi Center</h3>
        
    <form action="aanganwadi.php" method="POST">
    <div class="input-group">
        <label for="centerName">Center Name:</label>
        <input type="text" name="centerName" id="centerName" placeholder="Enter center name" />

        <label for="centerStreet">Street:</label>
        <input type="text" name="centerStreet" id="centerStreet" placeholder="Enter street address" />

        <label for="centerCity">City:</label>
        <input type="text" name="centerCity" id="centerCity" placeholder="Enter city" />

        <label for="centerState">State:</label>
        <input type="text" name="centerState" id="centerState" placeholder="Enter state" />

        <label for="centerPincode">Pincode:</label>
        <input type="text" name="centerPincode" id="centerPincode" placeholder="Enter pincode" />

        <label for="centerTime">Opening Time:</label>
        <input type="time" name="centerTime" id="centerTime" />

        <label for="centerClosingTime">Closing Time:</label>
        <input type="time" name="centerClosingTime" id="centerClosingTime" />

            <button class="add-btn" onclick="addCenter()">Add</button>
          
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