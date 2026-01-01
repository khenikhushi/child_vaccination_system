<?php 

include('connection.php'); 


session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="commamcss.css">
    <style>
       
        h2 {
            color: #007bff;
        }
        
    </style>
</head>
<body>

    <div class="container">
        <h2>You have been logged out.</h2>
        <p>Click below to go back to the home page.</p>
        <button onclick="redirectHome()">Go to Home</button>
    </div>

    <script>
        
        function redirectHome() {
            window.location.href = "home.html"; 
        }
    
        
        window.onload = function() {
            localStorage.removeItem("isLoggedIn");
        }
    </script>

</body>
</html>
