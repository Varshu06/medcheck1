<?php
// Establish database connection (replace these values with your actual database credentials)
$host = "localhost";
$username = "root";
$password = "";
$database = "medcheck";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$pat_id = $_POST['pat_id'];
$tabletNames = $_POST['tabletName'];
$quantities = $_POST['quantity'];
$intakeTimes = $_POST['intakeTime'];
$times = $_POST['time'] ?? [];


// Prepare and bind the SQL statement
$stmt = $conn->prepare("UPDATE add_new 
                        SET tabletName=?, quantity=?, intakeTime=?, time=?
                        WHERE pat_id=?");

for ($i = 0; $i < count($tabletNames); $i++) {
    $tabletName = $tabletNames[$i];
    $quantity = $quantities[$i];
    $intakeTime = $intakeTimes[$i];
    $time = $times[$i];
    
    // Bind parameters
    $stmt->bind_param("ssssi", $tabletName, $quantity, $intakeTime, $time, $pat_id);
    
    // Execute the prepared statement
    if ($stmt->execute()) {
        echo '<script>
                alert("Prescription details updated successfully")
                setTimeout(function() {
                    window.location.href = "after login.html";
                }, 1000); // 3000 milliseconds = 1 seconds
              </script>';
        // Exit script to prevent further execution
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
