<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors as HTML, log them instead
ini_set('log_errors', 1);

ob_start(); // Prevent output before headers

session_start(); // This should be at the very beginning
include("connection.php");

set_error_handler("customError");

function customError($errno, $errstr) {
    // Clear any buffered output
    ob_clean();
    error_log("Error [$errno]: $errstr");
    // Only output JSON for AJAX requests
    header('Content-Type: application/json', true);
    http_response_code(500);
    echo json_encode(['error' => 'Server error. Please try again.']);
    exit();
}

// Handle AJAX POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if an 'action' is specified for AJAX requests
    if (isset($_POST['action'])) {
        ob_clean(); // Clear any buffered output
        header("Content-Type: application/json"); // Set header for AJAX responses

        $action = $_POST['action'];

        if ($action === 'get_doses' && !empty($_POST['vaccine'])) {
            $vaccine = $connection->real_escape_string($_POST['vaccine']);
            // Use prepared statements for all queries to prevent SQL injection
            $stmt = $connection->prepare("SELECT DISTINCT doses FROM vaccines WHERE name = ? AND stock > 0");
            if (!$stmt) {
                error_log("Query preparation failed: " . $connection->error);
                echo json_encode(['error' => 'Database prepare error for doses.']);
                exit();
            }
            $stmt->bind_param('s', $vaccine);
            $stmt->execute();
            $result = $stmt->get_result();

            $doses = [];
            while ($row = $result->fetch_assoc()) {
                // Split comma-separated doses (e.g., "1,2,3" becomes [1, 2, 3])
                $dosesArray = array_map('trim', explode(',', $row['doses']));
                $doses = array_merge($doses, $dosesArray);
            }
            // Remove duplicates and sort
            $doses = array_unique($doses);
            sort($doses);
            echo json_encode($doses);
            exit;
        } elseif ($action === 'get_centers') {
            $stmt = $connection->prepare("SELECT id, name FROM anganwadi_centers WHERE status = 'registered'");
            if (!$stmt) {
                error_log("Query preparation failed: " . $connection->error);
                echo json_encode(['error' => 'Database prepare error for centers.']);
                exit();
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $centers = [];
            while ($row = $result->fetch_assoc()) {
                $centers[] = ['id' => (int)$row['id'], 'name' => $row['name']];
            }
            echo json_encode($centers);
            $stmt->close();
            exit;
        } elseif ($action === 'get_address' && isset($_POST['h_id']) && is_numeric($_POST['h_id'])) {
            $centerId = (int)$_POST['h_id'];
            $stmt = $connection->prepare("SELECT street, city, state, pincode FROM anganwadi_centers WHERE id = ?");
            if (!$stmt) {
                error_log("Query preparation failed: " . $connection->error);
                echo json_encode(['error' => 'Database prepare error for address.']);
                exit();
            }

            $stmt->bind_param('i', $centerId);
            if (!$stmt->execute()) {
                error_log("Query execution failed: " . $stmt->error);
                echo json_encode(['error' => 'Error fetching center address.']);
                exit();
            }
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $centerData = $result->fetch_assoc();
                // Ensure all values are properly escaped for JSON
                echo json_encode([
                    'street' => htmlspecialchars($centerData['street']),
                    'city' => htmlspecialchars($centerData['city']),
                    'state' => htmlspecialchars($centerData['state']),
                    'pincode' => htmlspecialchars($centerData['pincode'])
                ]);
            } else {
                echo json_encode(['error' => 'Center not found.']);
            }
            $stmt->close();
            exit;
        }
    }

    // 🎯 Form submission to book appointment
    // This block is for the main form submission, which is also a POST request but without 'action' in this specific structure
    if (isset($_POST['childName'], $_POST['vaccineName'], $_POST['vaccineDose'],
              $_POST['appointmentDate'], $_POST['appointmentTime'], $_POST['centerId'])) {

        ob_clean(); // Clear any buffered output before JSON response
        header("Content-Type: application/json", true); // Explicitly set content type for this response too

        $childName = trim($connection->real_escape_string($_POST['childName']));
        $vaccineName = trim($connection->real_escape_string($_POST['vaccineName']));
        $vaccineDose = trim($connection->real_escape_string($_POST['vaccineDose'])); // Ensure dose is trimmed
        $appointmentDate = trim($connection->real_escape_string($_POST['appointmentDate']));
        $appointmentTime = trim($connection->real_escape_string($_POST['appointmentTime']));
        $centerId = (int)$_POST['centerId'];

        // Log incoming data for debugging
        error_log("Appointment data: childName=$childName, vaccine=$vaccineName, dose=$vaccineDose, date=$appointmentDate, time=$appointmentTime, centerId=$centerId");

        // Basic validation for date and time to ensure they are not empty or invalid formats
        if (empty($childName) || empty($vaccineName) || empty($vaccineDose) ||
            empty($appointmentDate) || empty($appointmentTime) || $centerId <= 0) {
            http_response_code(400); // Bad request
            echo json_encode(['error' => 'All fields are required and valid.']);
            exit();
        }
        
        // Validate date format (YYYY-MM-DD)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $appointmentDate)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid appointment date format.']);
            exit();
        }
        
        // Validate time format (HH:MM)
        if (!preg_match('/^\d{2}:\d{2}$/', $appointmentTime)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid appointment time format.']);
            exit();
        }

        // Fetch center details (street, city, state, pincode)
        $centerStmt = $connection->prepare("SELECT name, street, city, state, pincode FROM anganwadi_centers WHERE id = ?");
        if (!$centerStmt) {
            error_log("Center query prepare failed: " . $connection->error);
            http_response_code(500);
            echo json_encode(['error' => 'Database error fetching center details.']);
            exit();
        }
        
        $centerStmt->bind_param('i', $centerId);
        $centerStmt->execute();
        $centerResult = $centerStmt->get_result();
        
        if ($centerResult->num_rows === 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Center not found.']);
            exit();
        }
        
        $centerData = $centerResult->fetch_assoc();
        $centerName = $centerData['name'];
        $street = $centerData['street'];
        $city = $centerData['city'];
        $state = $centerData['state'];
        $pincode = $centerData['pincode'];
        $centerStmt->close();

        // Fetch vaccine_id from vaccine name
        $vaccineStmt = $connection->prepare("SELECT id FROM vaccines WHERE name = ? LIMIT 1");
        if (!$vaccineStmt) {
            error_log("Vaccine query prepare failed: " . $connection->error);
            http_response_code(500);
            echo json_encode(['error' => 'Database error fetching vaccine details.']);
            exit();
        }
        
        $vaccineStmt->bind_param('s', $vaccineName);
        $vaccineStmt->execute();
        $vaccineResult = $vaccineStmt->get_result();
        
        $vaccineId = 0;
        if ($vaccineResult->num_rows > 0) {
            $vaccineData = $vaccineResult->fetch_assoc();
            $vaccineId = (int)$vaccineData['id'];
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Vaccine "' . htmlspecialchars($vaccineName) . '" not found in database.']);
            $vaccineStmt->close();
            exit();
        }
        $vaccineStmt->close();
        
        error_log("Vaccine lookup result: vaccineName='$vaccineName', vaccineId=$vaccineId");
        
        // Get parent_id from session (if available) - set to 0 if not available
        $parentId = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : 0;

        // Check if user is logged in (parentId should not be 0)
        if ($parentId <= 0) {
            http_response_code(401);
            echo json_encode(['error' => 'You must be logged in as a parent to book an appointment. Please log in first.']);
            exit();
        }

        // Validate all variables are set before INSERT
        if (empty($childName) || empty($vaccineName) || empty($vaccineDose) || 
            empty($appointmentDate) || empty($appointmentTime) || empty($centerName) || 
            empty($street) || empty($city) || empty($state) || empty($pincode) || 
            $vaccineId <= 0 || $centerId <= 0) {
            $missingFields = [];
            if (empty($childName)) $missingFields[] = 'childName';
            if (empty($vaccineName)) $missingFields[] = 'vaccineName';
            if (empty($vaccineDose)) $missingFields[] = 'vaccineDose';
            if (empty($appointmentDate)) $missingFields[] = 'appointmentDate';
            if (empty($appointmentTime)) $missingFields[] = 'appointmentTime';
            if (empty($centerName)) $missingFields[] = 'centerName';
            if (empty($street)) $missingFields[] = 'street';
            if (empty($city)) $missingFields[] = 'city';
            if (empty($state)) $missingFields[] = 'state';
            if (empty($pincode)) $missingFields[] = 'pincode';
            if ($vaccineId <= 0) $missingFields[] = 'vaccineId';
            if ($centerId <= 0) $missingFields[] = 'centerId';
            
            error_log("Missing fields before INSERT: " . implode(', ', $missingFields));
            http_response_code(400);
            echo json_encode(['error' => 'Missing required data: ' . implode(', ', $missingFields)]);
            exit();
        }

        // Log all data before insertion
        error_log("INSERT data: parentId=$parentId, vaccineId=$vaccineId, centerId=$centerId, childName='$childName', vaccineName='$vaccineName', vaccineDose='$vaccineDose', appointmentDate='$appointmentDate', appointmentTime='$appointmentTime', centerName='$centerName', street='$street', city='$city', state='$state', pincode='$pincode'");

        $query = "INSERT INTO appointments (parent_id, vaccine_id, anganwadi_id, child_name, vaccine_name, vaccine_dose, appointment_date, appointment_time, center_name, street, city, state, pincode, status)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $connection->prepare($query);

        if (!$stmt) {
            $prepareError = $connection->error;
            error_log("Query preparation failed: " . $prepareError);
            http_response_code(500); // Internal Server Error
            // Check if table exists
            if (strpos($prepareError, 'appointments') !== false) {
                echo json_encode(['error' => 'Database table "appointments" not found. Please contact support.', 'debug' => $prepareError]);
            } else {
                echo json_encode(['error' => 'Database error: ' . $prepareError]);
            }
            exit();
        }

        // Bind all 13 parameters (without status as it's hardcoded to 'pending')
        $bindResult = $stmt->bind_param('iiissssssssss', $parentId, $vaccineId, $centerId, $childName, $vaccineName, $vaccineDose, $appointmentDate, $appointmentTime, $centerName, $street, $city, $state, $pincode);
        
        if (!$bindResult) {
            $bindError = $stmt->error ?: 'Unknown bind error';
            error_log("Appointment bind_param failed: " . $bindError);
            http_response_code(500);
            echo json_encode(['error' => 'Bind Error: ' . $bindError]);
            $stmt->close();
            exit();
        }
        
        if (!$stmt->execute()) {
            $execError = $stmt->error ?: 'Unknown database error';
            error_log("Appointment booking execute failed: " . $execError);
            http_response_code(500);
            echo json_encode(['error' => 'Database Error: ' . $execError]);
        } else {
            http_response_code(200);
            echo json_encode(['success' => 'Appointment booked successfully!']);
        }
        $stmt->close();
        exit();
    }

    // If it's a POST request but doesn't match any known action or form submission
    header("Content-Type: application/json");
    echo json_encode(['error' => 'Invalid POST request or missing data.']);
    exit;
}

// If it's not a POST request, then it's an unauthorized access to this file
// You could redirect or show an error
http_response_code(405); // Method Not Allowed
echo "Method Not Allowed.";
exit();?>