<?php
include('connection.php');
session_start();

// Fetch appointments from database
$sql = "SELECT * FROM appointments ORDER BY appointment_date, appointment_time";
$result = $connection->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule - Child Vaccination System</title>
    <link rel="stylesheet" href="commamcss.css" />
    <!-- <link rel="stylesheet" href="footer.css"/> -->
    <style>
        h2 {
            color: #007bff;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 80%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
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
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background: darkred;
        }
    </style>
</head>

<body>
    <header class="header" >
        Vaccination Schedule
    </header>
    <nav>
       
            <a href="anganwadi-details.php">Anganwadi</a>
            <a href="showvaccine.php">Vaccine Details</a>
            <a href="appointment.php">Appointment</a>
            <a href="schedule.php">Schedule</a>
            <a href="profile.php">profile</a>
            <a href="contactus.php">Contact Us</a>
            <a href="logout.php">logout</a>

      
    </nav>


    <div style="padding: 20px;">
        <h2>Appointments</h2>
        <table id="appointmentTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #007bff; color: white;">
                    <th style="padding: 10px;">No.</th>
                    <th style="padding: 10px;">Child Name</th>
                    <th style="padding: 10px;">Vaccine Name</th>
                    <th style="padding: 10px;">Vaccine Dose</th>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Time</th>
                    <th style="padding: 10px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($count) . "</td>";
                        echo "<td>" . htmlspecialchars($row['child_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['vaccine_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['vaccine_dose']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['appointment_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['appointment_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status'] ?? 'pending') . "</td>";
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='7'>No appointments found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
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