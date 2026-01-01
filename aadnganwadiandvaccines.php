<?php
include('connection.php');

// SQL query to fetch hospital names and the vaccines they provide
$sql = "
    SELECT h.hospital_name, v.name AS vaccine_name 
    FROM anganwadi_centers ac
    JOIN users h ON ac.id = h.id
    JOIN vaccines v ON ac.id = v.id
    WHERE h.role = 'hospital'
    ORDER BY h.name, v.name
";

$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Vaccine Information</title>
    <link rel="stylesheet" href="commamcss.css" />
    <!-- <link rel="stylesheet" href="footer.css"/> -->
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 20px auto;
            max-width: 900px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        td {
            background-color: #f9f9f9;
            text-align: center;
        }
        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }
        tr:hover td {
            background-color: #eaeaea;
        }
        .hospital-name {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 12px 0;
            border-radius: 5px;
        }
        .empty-td {
            background-color: transparent;
            border: none;
        }
        .no-data {
            text-align: center;
            font-size: 18px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Hospitals and the Vaccines They Provide</h2>
    
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Hospital Name</th>
                    <th>Vaccine Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Output the data in table rows
                $currentHospital = '';
                while ($row = $result->fetch_assoc()) {
                    if ($currentHospital != $row['hospital_name']) {
                        $currentHospital = $row['hospital_name'];
                        echo "<tr><td colspan='2' class='hospital-name'>$currentHospital</td></tr>";
                    }
                    echo "<tr>";
                    echo "<td class='empty-td'></td>";  // Empty column for spacing
                    echo "<td>{$row['vaccine_name']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">No hospitals found or no vaccine data available.</p>
    <?php endif; ?>
</div>
< <div class="container">
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