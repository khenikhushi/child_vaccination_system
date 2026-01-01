<?php
include('connection.php');
session_start();

$sql = "SELECT * FROM vaccines";
$result = $connection->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccine Details - Child Vaccination System</title>
    <link rel="stylesheet" href="commamcss.css" />
    <!-- <link rel="stylesheet" href="footer.css"/> -->
    <style>
       

        .nav-link {
            margin: 10px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        #content {
            background: white;
            width: 80%;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .table-header {
            background: #007bff;
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
        .vaccine-name {
            color: #007bff;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header class="header">
       Vaccine Details
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
   
    <div id="content">
        <h2>Available Vaccines</h2>
        <table class="table">
            <tr class="table-header">
                <th>Vaccine Name</th>
                <th>Recommended Age</th>
                <th>Purpose</th>
                
            </tr>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>{$row['recommended_age']}</td>";
                        echo "<td>{$row['purpose']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No vaccines found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div id="vaccineInfo" style="display: none; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
            <h3>Vaccine Information</h3>
            <p id="infoName"><strong>Name:</strong> </p>
            <p id="infoAge"><strong>Recommended Age:</strong> </p>
            <p id="infoPurpose"><strong>Purpose:</strong> </p>
            
        </div>
    </div>
    
    <script>
        const vaccines = [
            { name: "BCG", age: "At birth", purpose: "Protects against tuberculosis" },
            { name: "Hepatitis B", age: "At birth, 6 weeks, 10 weeks, 14 weeks", purpose: "Prevents hepatitis B infection" },
            { name: "Polio", age: "At birth, 6 weeks, 10 weeks, 14 weeks", purpose: "Prevents poliovirus infection" },
            { name: "DTP", age: "6 weeks, 10 weeks, 14 weeks", purpose: "Protects against diphtheria, tetanus, and pertussis" }
        ];

    


        function loadVaccines() {
        const table = document.getElementById("vaccineTable");
        const vaccineInfo = JSON.parse(localStorage.getItem("vaccines")) || [];

        table.innerHTML = "";
        vaccineInfo.forEach((vaccine, index) => {
            table.innerHTML += `<tr>
                <td class='vaccine-name' onclick='showVaccineInfo(${index})'>${vaccine.name}</td>
                <td>${vaccine.age}</td>
                <td>${vaccine.purpose}</td>
            </tr>`;
        });
    }

        window.vaccineList = vaccineInfo; 
    

        function showVaccineInfo(index) {
            document.getElementById("vaccineInfo").style.display = "block";
            document.getElementById("infoName").innerHTML = `<strong>Name:</strong> ${vaccines[index].name}`;
            document.getElementById("infoAge").innerHTML = `<strong>Recommended Age:</strong> ${vaccines[index].age}`;
            document.getElementById("infoPurpose").innerHTML = `<strong>Purpose:</strong> ${vaccines[index].purpose}`;
        }

        function hideVaccineInfo() {
            document.getElementById("vaccineInfo").style.display = "none";
        }

       
        
        window.onload = loadVaccines;
    </script>
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