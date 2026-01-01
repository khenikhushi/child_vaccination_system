<?php
ob_start(); 
include 'connection.php';
if (!isset($connection)) {
    die("Database connection not available.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = $_POST['userType'];

    if ($userType == 'parent') {
        $name = $_POST['parentName'];
        $email = $_POST['parentEmail'];
        $password = $_POST['passwordParent'];
        $confirmPassword = $_POST['confirmPasswordParent'];
        $parentContact = $_POST['parentContact'];
        $parentAddress = $_POST['parentAddress'];
        $childName = $_POST['childName'];
        $childDOB = $_POST['childDOB'];
        $gender = $_POST['gender'];

    } elseif ($userType == 'hospital') {
        $name = $_POST['staffName'];
        $email = $_POST['staffEmail'];
        $password = $_POST['passwordStaff'];
        $confirmPassword = $_POST['confirmPasswordStaff'];
        $hospitalName = $_POST['hospitalNameStaff'];
        $street = $_POST['parentStreet'];
        $city = $_POST['parentCity'];
        $state = $_POST['parentState'];
        $pincode = $_POST['parentPincode'];
        $openingTime = $_POST['openingTime'];
        $closingTime = $_POST['closingTime'];
        $staffID = $_POST['staffID'];
    }
  

    if ($password !== $confirmPassword) {
        
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($userType == 'parent') {
            $stmt = $connection->prepare("INSERT INTO users (name, email, password, role, parent_contact, parent_address, child_name, child_dob, gender) VALUES (?, ?, ?, 'parent', ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $name, $email, $hashedPassword, $parentContact, $parentAddress, $childName, $childDOB, $gender);
            $checkEmailStmt = $connection->prepare("SELECT id FROM users WHERE email = ?");
            $checkEmailStmt->bind_param("s", $email);
            $checkEmailStmt->execute();
            $checkEmailStmt->store_result();
    
            if ($checkEmailStmt->num_rows > 0) {
                echo "<script>alert('Email already registered. Please use a different email.');</script>";
            } else {
              
                if ($stmt->execute()) {
                    echo "<script>
                        alert('Registration successful!');
                        window.location.href = 'login.php';
                    </script>";
                    exit();
                } else {
                    echo "<script>alert('Something went wrong: " . $stmt->error . "');</script>";
                }
             }
        } 
        
        if ($userType == 'hospital') {
            // Step 1: Prepare and insert into users table
            $stmt = $connection->prepare("INSERT INTO users (name, email, password, role, hospital_name, street, city, state, pincode, opening_time, closing_time, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $name, $email, $hashedPassword, $userType, $hospitalName, $street, $city, $state, $pincode, $openingTime, $closingTime, $staffID);
        
            $checkEmailStmt = $connection->prepare("SELECT id FROM users WHERE email = ?");
            $checkEmailStmt->bind_param("s", $email);
            $checkEmailStmt->execute();
            $checkEmailStmt->store_result();
        
            if ($checkEmailStmt->num_rows > 0) {
                echo "<script>alert('Email already registered. Please use a different email.');</script>";
            } else {
                if ($stmt->execute()) {
                    // Step 2: Insert into anganwadi_center table
                    $status = 'registered';
$insertAnganwadi = $connection->prepare("INSERT INTO anganwadi_centers (name, street, city, state, pincode, openingTime, closingTime, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$insertAnganwadi->bind_param("ssssssss", $hospitalName, $street, $city, $state, $pincode, $openingTime, $closingTime, $status);

                    if ($insertAnganwadi->execute()) {
                        echo "<script>
                            alert('Registration successful!');
                            window.location.href = 'login.php';
                        </script>";
                        exit();
                    } else {
                        echo "<script>alert('User registered but failed to add to Anganwadi center: " . $insertAnganwadi->error . "');</script>";
                    }
                } else {
                    echo "<script>alert('Something went wrong: " . $stmt->error . "');</script>";
                }
            }
        }
        
        
              
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Child Vaccination System - Registration</title>
    <link rel="stylesheet" href="commamcss.css" />
    <!-- <link rel="stylesheet" href="footer.css"/> -->
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-height: 90vh;
            overflow-y: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="password"],
        select {
            width: calc(100% - 12px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .radio-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }

        .hidden-fields {
            display: none;
        }
        .success-message {
   
            font-size: 0.9em;
            margin-top: 5px;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Child Vaccination System - Registration</h2>
        

       
        <form action="" method="POST" id="registrationForm">
            <div class="form-group">
                <label for="userType">User Type:</label>
                <select id="userType" name="userType" required>
                    <option value="">-- Select --</option>
                    <option value="parent">Parent/Guardian</option>
                    <option value="hospital">Hospital Staff</option>
                    <!-- <option value="admin">Administrator</option> -->
                </select>
            </div>

            <div id="parentFields" class="hidden-fields">
                <h3>Parent/Guardian Information</h3>
                <div class="form-group">
                    <label for="parentName">Full Name:</label>
                    <input type="text" id="parentName" name="parentName" required>
                </div>
                <div class="form-group">
                    <label for="parentEmail">Email:</label>
                    <input type="email" id="parentEmail" name="parentEmail" required>
                </div>
                <div class="form-group">
                    <label for="parentContact">Contact Number:</label>
                    <input type="text" id="parentContact" name="parentContact" required pattern="[6-9]\d{9}" title="Enter a valid 10-digit mobile number starting with 6-9">
                </div>
                <div class="form-group">
                    <label for="parentAddress">Address:</label>
                    <input type="text" id="parentAddress" name="parentAddress" required>
                </div>
                <h3>Child's Information</h3>
                <div class="form-group">
                    <label for="childName">Child's Full Name:</label>
                    <input type="text" id="childName" name="childName" required>
                </div>
                <div class="form-group">
                    <label for="childDOB">Child's Date of Birth:</label>
                    <input type="date" id="childDOB" name="childDOB" max="<?= date('Y-m-d'); ?>" required>

                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <div class="radio-group">
                        <input type="radio" id="male" name="gender" value="male" required>
                        <label for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="female" required>
                        <label for="female">Female</label>
                        <input type="radio" id="other" name="gender" value="other" required>
                        <label for="other">Other</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="passwordParent">Password:</label>
                    <input type="password" id="passwordParent" name="passwordParent" required>
                </div>
                <div class="form-group">
                    <label for="confirmPasswordParent">Confirm Password:</label>
                    <input type="password" id="confirmPasswordParent" name="confirmPasswordParent" required>
                    <p id="passwordMatchParent" class="error-message" style="display: none;">Passwords do not match.</p>
                </div>
                
            </div>

            <div id="hospitalFields" class="hidden-fields">
                <h3>Hospital Information</h3>
                <div class="form-group">
                    <label for="staffName">Full Name:</label>
                    <input type="text" id="staffName" name="staffName" required>
                </div>
                
                <div class="form-group">
                    <label for="staffID">Staff ID:</label>
                    <input type="text" id="staffID" name="staffID" required>
                </div>

                <div class="form-group">
                    <label for="staffEmail">Email:</label>
                    <input type="email" id="staffEmail" name="staffEmail" required>
                </div>
                <div class="form-group">
                    <label for="hospitalNameStaff">Hospital Name:</label>
                    <input type="text" id="hospitalNameStaff" name="hospitalNameStaff" required>
                </div>
                <div class="form-group">
                    <label for="parentStreet">Street:</label>
                    <input type="text" id="parentStreet" name="parentStreet" required>
                </div>
                <div class="form-group">
                    <label for="parentCity">City:</label>
                    <input type="text" id="parentCity" name="parentCity" required>
                </div>
                <div class="form-group">
                    <label for="parentState">State:</label>
                    <input type="text" id="parentState" name="parentState" required>
                </div>
                <div class="form-group">
                    <label for="parentPincode">Pincode:</label>
                    <input type="text" id="parentPincode" name="parentPincode" required pattern="\d{6}" title="Enter a valid 6-digit pincode">
                </div>
                <div class="form-group">
                    <label for="openingTime">Opening Time:</label>
                    <input type="time" id="openingTime" name="openingTime" required>
                </div>

                <div class="form-group">
                    <label for="closingTime">Closing Time:</label>
                    <input type="time" id="closingTime" name="closingTime" required>
                </div>


                <div class="form-group">
                    <label for="passwordStaff">Password:</label>
                    <input type="password" id="passwordStaff" name="passwordStaff" required>
                </div>
                <div class="form-group">
                    <label for="confirmPasswordStaff">Confirm Password:</label>
                    <input type="password" id="confirmPasswordStaff" name="confirmPasswordStaff" required>
                    <p id="passwordMatchStaff" class="error-message" style="display: none;">Passwords do not match.</p>
                </div>
              
            </div>


            
<div class="form-group">
    <button type="submit">Register</button>
</div>


        </form>
    </div>

    <script>
    const userTypeSelect = document.getElementById('userType');
    const parentFieldsDiv = document.getElementById('parentFields');
    const hospitalFieldsDiv = document.getElementById('hospitalFields');
    

    function clearRequiredFields() {
        document.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'));
    }

    userTypeSelect.addEventListener('change', function () {
     
        parentFieldsDiv.classList.add('hidden-fields');
        hospitalFieldsDiv.classList.add('hidden-fields');
        // adminFieldsDiv.classList.add('hidden-fields');
        clearRequiredFields();

    
        if (this.value === 'parent') {
            parentFieldsDiv.classList.remove('hidden-fields');
            parentFieldsDiv.querySelectorAll('input').forEach(input => input.setAttribute('required', true));
        } else if (this.value === 'hospital') {
            hospitalFieldsDiv.classList.remove('hidden-fields');
            hospitalFieldsDiv.querySelectorAll('input').forEach(input => input.setAttribute('required', true));
        } 
    });
</script>

</body>
</html>