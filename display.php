<?php
include('connection.php');
session_start();


$complaints = [];


$sql = "SELECT name, email, message, user_type FROM contact_messages";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
   
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
} else {
    $errorMessage = "No complaints found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submission Received</title>
  <link rel="stylesheet" href="commamcss.css" />
  <!-- <link rel="stylesheet" href="footer.css"/> -->
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f8ff;
      margin: 0;
      padding: 0;
      color: #333;
    }

    header {
      background-color: #007bff;
      color: white;
      padding: 40px 20px;
      text-align: center;
    }

    section {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #4bb543;
    }

    p {
      font-size: 1.1rem;
      margin-bottom: 15px;
    }

    footer {
      background-color: #2d6a4f;
      color: white;
      text-align: center;
      padding: 15px;
      margin-top: 40px;
    }
  </style>
</head>

<body>
  <nav>
   <a href="adashboard.html">Dashboard</a>
    <a href="aanganwadi.html">Add Anganwadi Center</a>
    <!-- <a href="anganwadi-details.html">Anganwadi</a>  -->
    <a href="display.html">Complains</a> 
    <a href="logout.php">Logout</a>
  </nav>
  <header>
    <h1>Thank You!</h1>
    <p>Your message has been received.</p>
  </header>

  <?php if (isset($errorMessage)): ?>
      <p><?= htmlspecialchars($errorMessage) ?></p>
    <?php else: ?>
      <?php foreach ($complaints as $complaint): ?>
        <div>
          <p><strong>Name:</strong> <?= htmlspecialchars($complaint['name']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($complaint['email']) ?></p>
          <p><strong>Message:</strong> <?= htmlspecialchars($complaint['message']) ?></p>
          <p><strong>User Type:</strong> <?= htmlspecialchars($complaint['user_type']) ?></p>
          <hr>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
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
