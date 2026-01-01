<?php
include("connection.php");

// Mark as Vaccinated
if (isset($_GET['vaccinate_id'])) {
    $id = $_GET['vaccinate_id'];
    $updateQuery = "UPDATE appointments SET status='Vaccinated' WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: hospital_record.php"); // Reload after action
    exit;
}

// Fetch records
$query = "SELECT * FROM appointments";
$result = $conn->query($query);
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
    <nav >
        <a href="hospital_dashboard.php" >Home</a>
        <a href="hvaccine_details.html" >Vaccine Details</a>
        <a href="hschedule.html" >Schedule</a>
        <a href="hanganwadi.html" >Anganwadi Details</a>
        <a href="hospital record.html" > Hospital record </a>
        <a href="hprofile.html">Profile</a>
        <a href="logout.html">logout</a>
        <a href="contactus.html">Contact Us</a>
        <a href="aboutus.html">about Us</a>
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
            <th>Parents Contact Number</th>
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
                        <td>{$row['child_name']}</td>
                        <td>{$row['vaccine_name']}</td>
                        <td>{$row['vaccine_dose']}</td>
                        <td>{$row['appointment_date']}</td>
                        <td>{$row['appointment_time']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['status']}</td>
                        <td>";
                if ($row['status'] !== 'Vaccinated') {
                    echo "<a href='?vaccinate_id={$row['id']}'><button class='mark-btn'>Mark as Vaccinated</button></a>";
                } else {
                    echo "✔️";
                }
                echo "</td>
                    </tr>";
                $counter++;
            }
        } else {
            echo "<tr><td colspan='9' style='text-align:center;'>No Appointments Found</td></tr>";
        }

        $conn->close();
        ?>
        </tbody>
    </table>
    
    <!-- <script>
        function loadRecords() {
            const appointments = JSON.parse(localStorage.getItem("appointments")) || [];
            const table = document.getElementById("recordsTable");
            table.innerHTML = "";
            appointments.forEach((record, index) => {
                table.innerHTML += `<tr>
                    <td>${index + 1}</td>
                    <td>${record.name}</td>
                    <td>${record.vaccineName}</td>
                    <td>${record.doseNumber}</td>
                    <td>${record.date}</td>
                    <td>${record.time}</td>
                    <td>${record.contact || "N/A"}</td>
                    <td>${record.status || "Not Vaccinated"}</td>
                    <td><button class='mark-btn' onclick='markVaccinated(${index})'>Mark as Vaccinated</button></td>
                </tr>`;
            });
        }

        function markVaccinated(index) {
            let appointments = JSON.parse(localStorage.getItem("appointments")) || [];
            appointments[index].status = "Vaccinated";
            localStorage.setItem("appointments", JSON.stringify(appointments));
            loadRecords();
        }

        function validateForm() {
            const childName = document.getElementById("childName").value.trim();
            const vaccineName = document.getElementById("vaccineName").value.trim();
            const doseNumber = document.getElementById("doseNumber").value;
            const appointmentDate = document.getElementById("appointmentDate").value;
            const appointmentTime = document.getElementById("appointmentTime").value;
            const contact = document.getElementById("contact").value.trim();

            if (!childName || !vaccineName || !doseNumber || !appointmentDate || !appointmentTime || !contact) {
                alert("Please fill in all fields.");
                return false;
            }
            if (!/^[a-zA-Z ]+$/.test(childName)) {
                alert("Child name should only contain letters and spaces.");
                return false;
            }
            if (!/^[0-9]{10}$/.test(contact)) {
                alert("Enter a valid 10-digit contact number.");
                return false;
            }
            return true;
        }

        window.onload = loadRecords;
    </script> -->
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
