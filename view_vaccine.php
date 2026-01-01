<?php
include("connection.php");

// Fetch all vaccines
$sql = "SELECT * FROM vaccines";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Vaccines</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-size: 18px;
        }
    </style>
</head>
<body>

<h2>All Vaccines and Doses</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Vaccine Name</th>
                <th>Dose Number</th>
                <th>Recommended Age</th>
                <th>Purpose</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                    $doseStocks = explode(",", $row['stock']);
                    for ($i = 0; $i < $row['doses_required']; $i++):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>Dose <?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($row['recommended_age']) ?></td>
                        <td><?= htmlspecialchars($row['purpose']) ?></td>
                        <td><?= intval($doseStocks[$i]) ?></td>
                    </tr>
                <?php endfor; ?>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="no-data">No vaccine data found.</p>
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
