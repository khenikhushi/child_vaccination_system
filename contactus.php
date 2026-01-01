<?php
include('connection.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $connection->real_escape_string($_POST['name']);
  $email = $connection->real_escape_string($_POST['email']);
  $message = $connection->real_escape_string($_POST['message']);
  

  $user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'Guest'; 


  $_SESSION['contact_data'] = [
    'name' => $name,
    'email' => $email,
    'message' => $message,
    'user_type' => $user_type
  ];


  $sql = "INSERT INTO contact_messages (name, email, user_type, message) VALUES ('$name', '$email', '$user_type', '$message')";
  if ($connection->query($sql) === TRUE) {
    $successMessage = "Your message has been submitted successfully!";
  } else {
    $errorMessage = "Error: " . $connection->error;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us - Child Vaccination System</title>
  <link rel="stylesheet" href="commamcss.css">
  <!-- <link rel="stylesheet" href="footer.css"> -->
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f7fa;
      margin: 0;
      padding: 0;
      color: #333;
    }

    header {
      background-color: #007bff;
      color: white;
      padding: 30px 20px;
      text-align: center;
      font-size: 26px;
      font-weight: bold;
    }

    nav {
      background-color: #0056b3;
      text-align: center;
      padding: 10px 0;
    }

    nav a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
      font-weight: bold;
    }

    nav a:hover {
      text-decoration: underline;
    }

    .form-section {
      max-width: 600px;
      margin: 40px auto;
      padding: 30px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .form-section h2 {
      text-align: center;
      color: #007bff;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 15px;
    }

    button {
      margin-top: 20px;
      background-color: #007bff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #0056b3;
    }

    footer {
      background-color: #007bff;
      color: white;
      text-align: center;
      padding: 15px;
      margin-top: 40px;
    }

    @media (max-width: 600px) {
      nav a {
        display: block;
        margin: 5px 0;
      }

      .form-section {
        margin: 20px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <header>Contact Us</header>

  <!-- <nav>
    <a href="home.html">Home</a>
    <a href="schedule.html">Schedule</a>
    <a href="anganwadi-details.html">Anganwadi</a>
    <a href="appointment.html">Appointment</a>
    <a href="vaccine.html">Vaccine Details</a>
    <a href="profile.html">Profile</a>
    <a href="logout.html">Logout</a>
    <a href="contactus.html">Contact Us</a>
    <a href="aboutus.html">About Us</a>
  </nav> -->

  <section class="form-section">
    <h2>We'd Love to Hear From You</h2>

    <?php if (!empty($successMessage)): ?>
      <p class="message success"><?= $successMessage ?></p>
    <?php elseif (!empty($errorMessage)): ?>
      <p class="message error"><?= $errorMessage ?></p>
    <?php endif; ?>

   
    <form method="POST" action="contactus.php">

      <label for="name">Your Name</label>
      <input type="text" name="name" id="name" value="<?= isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : '' ?>" <?= isset($_SESSION['user_type']) ? 'readonly' : '' ?> required>

      <label for="email">Your Email</label>
      <input type="email" name="email" id="email" value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>" <?= isset($_SESSION['user_type']) ? 'readonly' : '' ?> required>

      <label for="message">Enter Your Message / Problem</label>
      <textarea name="message" id="message" rows="5" required></textarea>

      <button type="submit">Send Message</button>
     
    </form>
  </section>

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
