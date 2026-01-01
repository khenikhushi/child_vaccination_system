<?php
include('connection.php');
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Anganwadi Centers - Details</title>
  <link rel="stylesheet" href="commamcss.css" />
  <!-- <link rel="stylesheet" href="footer.css"/> -->
  <style>
    h2 {
      color: #007bff;
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
  </style>
</head>
<body>
  <header class="header">Anganwadi Centers - Details</header>

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
    <h2>Anganwadi Centers - Details</h2>
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Center Name</th>
          <th>Street</th>
          <th>City</th>
          <th>State</th>
          <th>Pincode</th>
          <th>Opening Time</th>
          <th>Closing Time</th>
        </tr>
      </thead>
     
      <tbody>
      <?php
        $result = $connection->query("SELECT * FROM anganwadi_centers WHERE status = 'registered'");
        
        if ($result->num_rows > 0) {
          $i = 1;
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
              <td>{$i}</td>
              <td>{$row['name']}</td>
              <td>{$row['street']}</td>
              <td>{$row['city']}</td>
              <td>{$row['state']}</td>
              <td>{$row['pincode']}</td>
              <td>{$row['openingTime']}</td>
              <td>{$row['closingTime']}</td>
            </tr>";
            $i++;
          }
        } else {
          echo "<tr><td colspan='8'>No records found.</td></tr>";
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
