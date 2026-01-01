<?php
ob_start(); // Prevent output before headers

session_start(); // This should be at the very beginning
include("connection.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

set_error_handler("customError");

function customError($errno, $errstr) {
    error_log("Error [$errno]: $errstr");
    // Only output JSON for AJAX requests, otherwise handle differently or log
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'An error occurred on the server. Please try again.']);
    } else {
        // For non-AJAX requests, you might redirect or show a user-friendly error page
        // For now, just a generic message
        echo "An unexpected error occurred.";
    }
    exit();
}

// Handle AJAX POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if an 'action' is specified for AJAX requests
    if (isset($_POST['action'])) {
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
                $doses[] = $row['doses'];
            }
            echo json_encode($doses);
            exit;
        } elseif ($action === 'get_centers') {
            $result = $connection->query("SELECT id, name FROM anganwadi_centers WHERE status = 'registered'");
            $centers = [];
            while ($row = $result->fetch_assoc()) {
                $centers[] = ['id' => $row['id'], 'name' => $row['name']];
            }
            echo json_encode($centers);
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
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $centerData = $result->fetch_assoc();
                echo json_encode($centerData);
            } else {
                echo json_encode(['error' => 'Center not found.']);
            }
            exit;
        }
    }

    // 🎯 Form submission to book appointment
    // This block is for the main form submission, which is also a POST request but without 'action' in this specific structure
    if (isset($_POST['childName'], $_POST['vaccineName'], $_POST['vaccineDose'],
              $_POST['appointmentDate'], $_POST['appointmentTime'], $_POST['centerId'])) {

        header("Content-Type: application/json"); // Explicitly set content type for this response too

        $childName = $connection->real_escape_string($_POST['childName']);
        $vaccineName = $connection->real_escape_string($_POST['vaccineName']);
        $vaccineDose = $connection->real_escape_string($_POST['vaccineDose']);
        $appointmentDate = $connection->real_escape_string($_POST['appointmentDate']);
        $appointmentTime = $connection->real_escape_string($_POST['appointmentTime']);
        $centerId = (int)$_POST['centerId'];

        // Basic validation for date and time to ensure they are not empty or invalid formats
        if (empty($childName) || empty($vaccineName) || empty($vaccineDose) ||
            empty($appointmentDate) || empty($appointmentTime) || empty($centerId)) {
            http_response_code(400); // Bad request
            echo json_encode(['error' => 'All fields are required.']);
            exit();
        }

        $query = "INSERT INTO appointments (anganwadi_id, child_name, vaccine_name, vaccine_dose, appointment_date, appointment_time)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($query);

        if (!$stmt) {
            error_log("Query preparation failed: " . $connection->error);
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Database prepare error for appointment booking.']);
            exit();
        }

        $stmt->bind_param('isssss', $centerId, $childName, $vaccineName, $vaccineDose, $appointmentDate, $appointmentTime);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Appointment booked successfully.']);
        } else {
            error_log("Appointment booking failed: " . $stmt->error); // Log the specific MySQL error
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to book appointment. Please try again.']);
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
exit();

?>