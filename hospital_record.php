<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_id'])) {
    $id = $_POST['mark_id'];
    
    
    $stmt = $connection->prepare("SELECT vaccine_name FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($vaccine_name);
    $stmt->fetch();
    $stmt->close();


    $stmt = $connection->prepare("SELECT stock FROM vaccines WHERE name = ?");
    $stmt->bind_param("s", $vaccine_name);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

    if ($stock > 0) {
        
        $stmt = $connection->prepare("UPDATE vaccines SET stock = stock - 1 WHERE name = ?");
        $stmt->bind_param("s", $vaccine_name);
        $stmt->execute();

    
        $stmt = $connection->prepare("UPDATE appointments SET status = 'Vaccinated' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } else {
        echo "<script>alert('Stock unavailable for this vaccine!');</script>";
    }
}


$sql = "SELECT * FROM appointments";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Vaccination Records</title>
    <link rel="stylesheet" href="commamcss.css" />
    <!-- <link rel="stylesheet" href="footer.css"/> -->
    <style>
        
        h2{
            margin: 10px;
            width: 80%;
        }
        .table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .table-header {
            background: #007bff;
            color: white;
        }
        .mark-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header class="header">
        Hospital Vaccination Records
    </header>
    <nav>
        <a href="hospital_dashboard.php">Home</a>
        <a href="vaccine.php">Add Vaccine Details</a>
        <a href="hospital_record.php">Appointment Details</a>
        <a href="hprofile.php">Profile</a>
        <a href="contactus.php">Contact Us</a>
        <a href="logout.php">Logout</a>
</nav>

    
    <h2>Appointments</h2>
    <table class="table">
        <tr class="table-header">
            <th>No</th>
            <th>Child Name</th>
            <th>Vaccine Name</th>
            <th>Vaccine Dose</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            
            <th>Status</th>
            <th>Action</th>
        </tr>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$counter}</td>
                        <td>" . htmlspecialchars($row['child_name']) . "</td>
                        <td>" . htmlspecialchars($row['vaccine_name']) . "</td>
                        <td>" . htmlspecialchars($row['vaccine_dose']) . "</td>
                        <td>" . htmlspecialchars($row['appointment_date']) . "</td>
                        <td>" . htmlspecialchars($row['appointment_time']) . "</td>
                       
                        <td>" . htmlspecialchars($row['status']) . "</td>
                        <td>
                            <form method='POST'>
                                <input type='hidden' name='mark_id' value='{$row['id']}'>
                                <button class='mark-btn' type='submit'>Mark as Vaccinated</button>
                            </form>
                        </td>
                    </tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='9' style='text-align:center;'>No appointments found.</td></tr>";
            }
            ?>       
         </tbody>
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
