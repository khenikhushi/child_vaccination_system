<?php
include('connection.php'); 

// if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'admin') {
//     echo "You must be logged in as a admin to access this page.";
//     exit();
// }

$sql = "SELECT * FROM anganwadi_centers WHERE status = 'registered'";
$result = $connection->query($sql);

$sql_not_registered = "SELECT * FROM anganwadi_centers WHERE status != 'registered'";
$result_not_registered = $connection->query($sql_not_registered);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <meta charset="UTF-8" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard</title>
    <link rel="stylesheet" href="commamcss.css" />
    <!-- <link rel="stylesheet" href="footer.css"/> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        .nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .nav button {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }
        .nav button:hover {
            background: #0056b3;
        }
        .section {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
    <header class="header">Dashboard</header>
    <nav>
   
            <a href="adashbord.php">Dashboard</a>
            <a href="aanganwadi.php">Add Anganwadi Center</a>
            <a href="display.php">Complains</a>
            <a href="logout.php">Logout</a>
          
      </nav>
        <div class="section">
            <h2>Welcome to the Dashboard</h2>
            <p>Select an option from the navigation above to manage Anganwadi Centers, Complains.</p>
        </div>
    </div>
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
              <th>Status</th>
              <!-- <th>Action<th> -->
            </tr>
          </thead>
          <tbody>
             <?php
            if ($result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $i++ . "</td>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['street']) . "</td>
                        <td>" . htmlspecialchars($row['city']) . "</td>
                        <td>" . htmlspecialchars($row['state']) . "</td>
                        <td>" . htmlspecialchars($row['pincode']) . "</td>
                        <td>" . htmlspecialchars($row['status']) . "</td>
                         
                     </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No centers found.</td></tr>";
            }
           
            ?></tbody>
          
        </table>
        <h2>Anganwadi Centers - Not Registered</h2>
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Center Name</th>
      <th>Street</th>
      <th>City</th>
      <th>State</th>
      <th>Pincode</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if ($result_not_registered->num_rows > 0) {
        $i = 1;
        while ($row = $result_not_registered->fetch_assoc()) {
            echo "<tr>
                <td>" . $i++ . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['street']) . "</td>
                <td>" . htmlspecialchars($row['city']) . "</td>
                <td>" . htmlspecialchars($row['state']) . "</td>
                <td>" . htmlspecialchars($row['pincode']) . "</td>
                <td>" . htmlspecialchars($row['status']) . "</td>
                <td><a href='aanganwadi.php?delete_id={$row['id']}' class='delete-btn' onclick='return confirm(\"Delete this center?\");'>Delete</a></td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No unregistered centers found.</td></tr>";
    }
    $connection->close();
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