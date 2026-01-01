<?php
session_start();
require_once 'connection.php';

header('Content-Type: application/json');

// Only respond to POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Validate action parameter
if (empty($_POST['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Action parameter is required']);
    exit;
}

try {
    $action = $_POST['action'];
    $response = [];

    switch ($action) {
        case 'get_doses':
            // Validate vaccine ID
            if (empty($_POST['vaccineId']) || !is_numeric($_POST['vaccineId'])) {
                throw new Exception('Invalid vaccine selection');
            }
            
            $vaccineId = intval($_POST['vaccineId']);
            $query = "SELECT DISTINCT dose FROM vaccine_doses 
                      WHERE vaccine_id = ? AND available = 1
                      ORDER BY dose";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $vaccineId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $doses = [];
            while ($row = $result->fetch_assoc()) {
                $doses[] = $row['dose'];
            }
            
            if (empty($doses)) {
                throw new Exception('No available doses for selected vaccine');
            }
            
            $response = ['doses' => $doses];
            break;
            
        case 'get_centers':
            // Validate vaccine and dose parameters
            if (empty($_POST['vaccineId']) || empty($_POST['doseNumber'])) {
                throw new Exception('Vaccine and dose selection required');
            }
            
            $vaccineId = intval($_POST['vaccineId']);
            $doseNumber = intval($_POST['doseNumber']);
            
            $query = "SELECT c.id, c.name, c.address, c.city, c.state, c.pincode 
                      FROM centers c
                      JOIN center_vaccines cv ON c.id = cv.center_id
                      WHERE cv.vaccine_id = ? AND cv.dose_number = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $vaccineId, $doseNumber);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $centers = [];
            while ($row = $result->fetch_assoc()) {
                $centers[] = $row;
            }
            
            if (empty($centers)) {
                throw new Exception('No centers available for this vaccine and dose');
            }
            
            $response = ['centers' => $centers];
            break;
            
        case 'get_time_slots':
            // Validate date
            if (empty($_POST['date'])) {
                throw new Exception('Date is required');
            }
            
            $date = $_POST['date'];
            if (!strtotime($date)) {
                throw new Exception('Invalid date format');
            }
            
            $businessHours = $_POST['businessHours'] ?? [
                'open' => '08:00',
                'close' => '18:00',
                'lunchStart' => '13:00',
                'lunchEnd' => '14:00'
            ];
            
            // Get booked slots for this date
            $bookedSlots = [];
            $query = "SELECT TIME_FORMAT(appointment_time, '%H:%i') as time_slot 
                      FROM appointments 
                      WHERE appointment_date = ? 
                      AND status IN ('confirmed', 'pending')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $date);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $bookedSlots[] = $row['time_slot'];
            }
            
            // Generate available time slots
            $slots = [];
            $current = new DateTime("{$date} {$businessHours['open']}");
            $end = new DateTime("{$date} {$businessHours['close']}");
            $interval = new DateInterval('PT30M'); // 30 minute intervals
            
            while ($current < $end) {
                $time = $current->format('H:i');
                $display = $current->format('h:i A');
                
                // Skip lunch time
                $isLunchTime = $time >= $businessHours['lunchStart'] && 
                               $time < $businessHours['lunchEnd'];
                               
                if (!$isLunchTime) {
                    $slots[] = [
                        'time' => $time,
                        'display' => $display,
                        'booked' => in_array($time, $bookedSlots)
                    ];
                }
                
                $current->add($interval);
            }
            
            $response = ['slots' => $slots];
            break;
            
        case 'book_appointment':
            // Validate all required fields
            $required = ['childName', 'vaccineId', 'doseNumber', 
                        'appointmentDate', 'appointmentTime', 'centerId'];
            
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }
            
            // Validate date format
            $date = $_POST['appointmentDate'];
            if (!strtotime($date)) {
                throw new Exception('Invalid date format');
            }
            
            // Validate time format
            $time = $_POST['appointmentTime'];
            if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
                throw new Exception('Invalid time format');
            }
            
            // Check if slot is still available
            $checkQuery = "SELECT id FROM appointments 
                          WHERE appointment_date = ? 
                          AND appointment_time = ?
                          AND status IN ('confirmed', 'pending')";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param('ss', $date, $time);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows > 0) {
                throw new Exception('This time slot is no longer available');
            }
            
            // Insert the appointment
            $insertQuery = "INSERT INTO appointments 
                          (child_name, vaccine_id, dose_number, 
                          appointment_date, appointment_time, center_id, 
                          created_at, status) 
                          VALUES (?, ?, ?, ?, ?, ?, NOW(), 'confirmed')";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param(
                'siissi',
                $_POST['childName'],
                $_POST['vaccineId'],
                $_POST['doseNumber'],
                $date,
                $time,
                $_POST['centerId']
            );
            
            if ($stmt->execute()) {
                $response = ['success' => 'Appointment booked successfully!'];
            } else {
                throw new Exception('Failed to book appointment. Please try again.');
            }
            break;
            
        default:
            http_response_code(400);
            throw new Exception('Invalid action');
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
