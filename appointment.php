<?php
session_start();

include("connection.php");


// Check parent login - ENABLED FOR PRODUCTION
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] != 'parent') {
    echo "You must be logged in as a parent to access this page.";
    exit();
}


// Get vaccines with stock
$vaccineNames = [];
$vaccineQuery = $connection->query("SELECT DISTINCT name FROM vaccines WHERE stock > 0");
if ($vaccineQuery) {
while ($row = $vaccineQuery->fetch_assoc()) {
    $vaccineNames[] = $row['name'];
}}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking - Child Vaccination</title>
    <link rel="stylesheet" href="commamcss.css" /> <style>
        /* Your styles go here */
        h2 {
            color: #007bff;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 80%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
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
        .delete-btn {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background: darkred;
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

<header class="header" >
Book Vaccination Appointment
    </header>
    <nav>
            <a href="anganwadi-details.php">Anganwadi</a>
            <a href="showvaccine.php">Vaccine Details</a>
            <a href="appointment.php">Appointment</a>
            <a href="schedule.php">Schedule</a>
            <a href="profile.php">profile</a>
            <a href="contactus.php">Contact Us</a>
            <a href="logout.php">logout</a>
    </nav>

<div class="container">
    <form id="appointmentForm" method="POST"> <label for="childName">Child's Name:</label>
        <input type="text" name="childName" required>

        <label for="vaccineName">Vaccine Name:</label>
        <select name="vaccineName" id="vaccineName" required>
            <option value="">--Select Vaccine--</option>
            <?php foreach ($vaccineNames as $vaccine): ?>
                <option value="<?= htmlspecialchars($vaccine) ?>"><?= htmlspecialchars($vaccine) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="vaccineDose">Vaccine Dose:</label>
        <select name="vaccineDose" id="vaccineDose" required>
            <option value="">--Select Dose--</option>
        </select>

        <label for="appointmentDate">Appointment Date:</label>
        <input type="date" name="appointmentDate" required>

        <label for="appointmentTime">Appointment Time:</label>
        <input type="time" name="appointmentTime" required>

        <label for="centerId">Center:</label>
        <select name="centerId" id="centerId" required>
            <option value="">--Select Center--</option>
        </select>

        <label for="street">Street:</label>
        <input type="text" name="street" id="street" readonly required>

        <label for="city">City:</label>
        <input type="text" name="city" id="city" readonly required>

        <label for="state">State:</label>
        <input type="text" name="state" id="state" readonly required>

        <label for="pincode">Pincode:</label>
        <input type="text" name="pincode" id="pincode" readonly required>

        <button type="submit">Book Appointment</button>
    </form>
</div>
<script>
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault(); // crucial: Prevent default form submission

        const formData = new FormData(this);

        fetch('appointment_data.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // IMPORTANT: Add this header
                // Note: Don't set Content-Type with FormData, browser sets it automatically
            }
        })
        .then(response => {
            // Always read the body once
            return response.text().then(text => {
                // If we got a response, try to parse as JSON
                try {
                    const data = text ? JSON.parse(text) : {};
                    // Check HTTP status
                    if (!response.ok) {
                        throw new Error(data.error || `Server error: ${response.status}`);
                    }
                    return data;
                } catch (e) {
                    // If JSON parsing fails and status is not OK, use the text as error
                    if (!response.ok) {
                        throw new Error(text || `Server error: ${response.status}`);
                    }
                    // If status is OK but JSON parsing failed, still try to use the response
                    throw new Error('Invalid response format from server');
                }
            });
        })
        .then(data => {
            if (data.success) {
                alert(data.success);
                this.reset(); // Clear form on successful submission
                // You might want to update a list of appointments here if displayed on the page
            } else if (data.error) {
                alert(data.error);
            } else {
                alert('An unexpected successful response was received.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to book appointment: ' + error.message);
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const vaccineSelect = document.getElementById('vaccineName');
        const doseSelect = document.getElementById('vaccineDose');
        const centerSelect = document.getElementById('centerId');
        const street = document.getElementById('street');
        const city = document.getElementById('city');
        const state = document.getElementById('state');
        const pincode = document.getElementById('pincode');

        // Function to fetch and populate doses
        const fetchDoses = (vaccine) => {
            doseSelect.innerHTML = '<option value="">--Select Dose--</option>'; // Reset doses
            if (!vaccine) return;

            fetch('appointment_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest' // Add this header for all AJAX calls
                },
                body: 'action=get_doses&vaccine=' + encodeURIComponent(vaccine)
            })
            .then(response => response.text().then(text => ({ status: response.status, ok: response.ok, text })))
            .then(({ status, ok, text }) => {
                try {
                    const data = JSON.parse(text);
                    if (!ok) {
                        throw new Error(data.error || `Server error: ${status}`);
                    }
                    return data;
                } catch (e) {
                    throw new Error(!ok ? (text || `Server error: ${status}`) : 'Invalid response format');
                }
            })
            .then(data => {
                data.forEach(dose => {
                    const opt = document.createElement('option');
                    opt.value = dose;
                    opt.textContent = dose;
                    doseSelect.appendChild(opt);
                });
            })
            .catch(error => {
                console.error('Dose Fetch Error:', error);
                alert('Failed to fetch doses: ' + error.message);
            });
        };

        // Function to fetch and populate centers
        const fetchCenters = () => {
            fetch('appointment_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest' // Add this header for all AJAX calls
                },
                body: 'action=get_centers'
            })
            .then(response => response.text().then(text => ({ status: response.status, ok: response.ok, text })))
            .then(({ status, ok, text }) => {
                try {
                    const data = JSON.parse(text);
                    if (!ok) {
                        throw new Error(data.error || `Server error: ${status}`);
                    }
                    return data;
                } catch (e) {
                    throw new Error(!ok ? (text || `Server error: ${status}`) : 'Invalid response format');
                }
            })
            .then(data => {
                centerSelect.innerHTML = '<option value="">--Select Center--</option>';
                data.forEach(center => {
                    const option = document.createElement("option");
                    option.value = center.id;
                    option.textContent = center.name;
                    centerSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error loading centers:", error);
                alert("Failed to load centers: " + error.message);
            });
        };

        // Function to fetch and populate address
        const fetchAddress = (centerId) => {
            if (!centerId) {
                street.value = city.value = state.value = pincode.value = "";
                return;
            }
            fetch('appointment_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest' // Add this header for all AJAX calls
                },
                body: 'action=get_address&h_id=' + encodeURIComponent(centerId)
            })
            .then(response => response.text().then(text => ({ status: response.status, ok: response.ok, text })))
            .then(({ status, ok, text }) => {
                try {
                    const data = JSON.parse(text);
                    if (!ok) {
                        throw new Error(data.error || `Server error: ${status}`);
                    }
                    return data;
                } catch (e) {
                    throw new Error(!ok ? (text || `Server error: ${status}`) : 'Invalid response format');
                }
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    street.value = city.value = state.value = pincode.value = ""; // Clear on error
                } else {
                    street.value = data.street || "";
                    city.value = data.city || "";
                    state.value = data.state || "";
                    pincode.value = data.pincode || "";
                }
            })
            .catch(error => {
                console.error("Error fetching address:", error);
                alert("Failed to fetch center address: " + error.message);
                street.value = city.value = state.value = pincode.value = ""; // Clear on error
            });
        };

        // Event Listeners
        vaccineSelect.addEventListener('change', () => {
            fetchDoses(vaccineSelect.value);
            // Also clear address and reset center when vaccine changes
            street.value = city.value = state.value = pincode.value = '';
            centerSelect.value = ''; // Reset center selection
        });

        centerSelect.addEventListener("change", () => {
            fetchAddress(centerSelect.value);
        });

        // Initial loads
        fetchCenters(); // Load centers on page load
    });
</script>


</body>
</html>