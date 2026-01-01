<?php
include('connection.php');

// if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'hospital') {
//     echo "You must be logged in as a hospital to access this page.";
//     exit();
// }

if (isset($_GET['remove_id'])) {
    $appointmentId = $_GET['remove_id'];
    $query = "DELETE FROM appointments WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();
    $stmt->close();
}


$query = "SELECT * FROM appointments";
$result = $connection->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard - Child Vaccination System</title>
    <link rel="stylesheet" href="commamcss.css" />
    <!-- <link rel="stylesheet" href="footer.css"/> -->
    <style>
       
        

        h2 {
            text-align: center;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }


        .form-section {
            margin-top: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <header class="header">Hospital Dashboard</header>
    <nav>
        <a href="hospital_dashboard.php">Home</a>
        <a href="vaccine.php">Add Vaccine Details</a>
        <a href="hospital_record.php">Appointment Details</a>
        <a href="hprofile.php">Profile</a>
        <a href="contactus.php">Contact Us</a>
        <a href="logout.php">Logout</a>
</nav>
    <div class="container">
        

        <h3>Upcoming Appointments</h3>
        <table id="appointmentTable">
            <tr>
                <th>Child Name</th>
                <th>Vaccine Name</th>
                <th>Vaccine Dose </th>
                <th>Date</th>
                <th>Time</th>
                
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['child_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['vaccine_dose']); ?></td>
                    <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                    <td>
                        <a href="?remove_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to remove this appointment?');">
                            <button>Remove</button>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>

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
