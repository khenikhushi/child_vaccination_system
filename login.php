<?php
ob_start(); 
session_start();
include('connection.php');
$passwordError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['userType'];
    $password = $_POST['password'];

    if ($userType === 'parent' && isset($_POST['parentEmail'], $_POST['childName'])) {
        $email = $_POST['parentEmail'];
        $childName = $_POST['childName'];
        $sql = "SELECT * FROM users WHERE role = 'parent' AND email = '$email' AND child_name = '$childName'";
    } elseif ($userType === 'hospital' && isset($_POST['hospitalEmail'], $_POST['hospitalName'])) {
        $email = $_POST['hospitalEmail'];
        $hospitalName = $_POST['hospitalName'];
        $sql = "SELECT * FROM users WHERE role = 'hospital' AND email = '$email' AND hospital_name = '$hospitalName'";
       
    } elseif ($userType === 'admin' && isset($_POST['adminEmail'])) {
      $email = $_POST['adminEmail'];
      $password = $_POST['password'];
  
      if ($email === 'khushi@gmail.com' && $password === '987654') {
          echo "<script>
              alert('Admin login successful!');
              window.location.href = 'adashboard.php';
          </script>";
          exit();
      } else {
          echo "<script>alert('Invalid admin credentials.');</script>";
          exit();
      }
    } else {
        echo "<script>alert('Please fill in all required fields.');</script>";
        exit();
    }
  
    $result = $connection->query($sql);
 

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        
        if (password_verify($password, $user['password'])) {
          $_SESSION['user'] = $user;
      
          
          if ($user['role'] === 'parent') {
              header("Location: appointment.php");
              exit();
          } elseif ($user['role'] === 'hospital') {
              header("Location: hospital_dashboard.php");
              exit();
          } elseif ($user['role'] === 'admin') {
              header("Location: adashboard.php");
              exit();
          } else {
              echo "<script>alert('Enter valid email id and password.');</script>";
          }
      } else {
        $passwordError = 'Incorrect password. Please try again.';
      }        
        }  
        else {
          $passwordError = 'No account found with this email.';
      }   
    }
   

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Child Vaccination System</title>
  <style>
    h2 {
      text-align: center;
      color: #007bff;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 10px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin: 5px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    p {
      text-align: center;
      margin-top: 10px;
    }

    nav a {
      color: red;
      text-decoration: none;
      padding: 10px 15px;
      display: inline-block;
    }

    nav a:hover {
      background: #004080;
      border-radius: 5px;
    }

    .error {
      color: red;
      font-size: 14px;
    }

    .parent-fields, .hospital-fields, .admin-fields {
      display: none;
    }

    .container {
      max-width: 400px;
      margin: auto;
    }

    button {
      margin-top: 15px;
      width: 100%;
      padding: 10px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
    }

    button:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Login</h2>

  <form method="POST" action="login.php">
    <label>Select User Type:</label>
    <select name="userType" id="userType" onchange="toggleFields()">
      <option value="parent" selected>Parent</option>
      <option value="hospital">Hospital Management Team</option>
      <option value="admin">Admin</option>
    </select>

    <div class="parent-fields" id="parentFields">
      <label>Email Address:</label>
      <input type="email" name="parentEmail" id="parentEmail" placeholder="Enter your email" />

      <label>Child Name:</label>
      <input type="text" name="childName" id="childName" placeholder="Enter child name" />
    </div>

    <div class="hospital-fields" id="hospitalFields">
      <label>Hospital Name:</label>
      <input type="text" name="hospitalName" id="hospitalName" placeholder="Enter hospital name" />

      <label>Email Address:</label>
      <input type="email" name="hospitalEmail" id="hospitalEmail" placeholder="Enter hospital email" />
    </div>

    <div class="admin-fields" id="adminFields">
      <label>Email Address:</label>
      <input type="email" name="adminEmail" id="adminEmail" placeholder="Enter admin email" />
    </div>

    <label>Password:</label>
    <input type="password" name="password" id="password" placeholder="Enter password" />
    <?php if (!empty($passwordError)): ?>
            <script>alert('<?php echo $passwordError; ?>');</script>
        <?php endif; ?>
    

    <button type="submit">Login</button>

    <p><a href="forgot_password.php">Forgot Password?</a></p>
    <p>If you don't have an account, <a href="register.php">Register here</a></p>
  </form>
</div>

<script>
  function toggleFields() {
    const userType = document.getElementById("userType").value;
    document.getElementById("parentFields").style.display = "none";
    document.getElementById("hospitalFields").style.display = "none";
    document.getElementById("adminFields").style.display = "none";

    if (userType === "parent") {
      document.getElementById("parentFields").style.display = "block";
    } else if (userType === "hospital") {
      document.getElementById("hospitalFields").style.display = "block";
    } else if (userType === "admin") {
      document.getElementById("adminFields").style.display = "block";
    }
  }
  function validateForm() {
  const userType = document.getElementById("userType").value;
  let emailField, childNameField, hospitalNameField, adminEmailField;

  if (userType === 'parent') {
    emailField = document.getElementById('parentEmail');
    childNameField = document.getElementById('childName');
  } else if (userType === 'hospital') {
    emailField = document.getElementById('hospitalEmail');
    hospitalNameField = document.getElementById('hospitalName');
  } else if (userType === 'admin') {
    emailField = document.getElementById('adminEmail');
  }

  if (emailField.value.trim() === '') {
    alert("Please fill in the email field.");
    return false;
  }

  if (userType === 'parent' && childNameField.value.trim() === '') {
    alert("Please fill in the child name.");
    return false;
  }

  if (userType === 'hospital' && hospitalNameField.value.trim() === '') {
    alert("Please fill in the hospital name.");
    return false;
  }

  return true; 
}

  window.onload = () => {
    toggleFields();
  };
</script>

</body>
</html>
