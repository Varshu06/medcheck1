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
$pat_id = $_POST['pat_id'] ?? '';
$pat_name = $_POST['pat_name'] ?? '';
$age = $_POST['age'] ?? '';
$phno = $_POST['phno'] ?? '';
$address = $_POST['address'] ?? '';
$disease = $_POST['disease'] ?? '';
$doc_id = $_POST['doc_id'] ?? '';
$doc_name = $_POST['doc_name'] ?? '';
$tabletNames = $_POST['tabletName'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$intakeTimes = $_POST['intakeTime'] ?? [];
$times = $_POST['time'] ?? [];
$date = $_POST['date'] ?? ''; // Retrieve date from the form

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO add_new (pat_id, pat_name, age, phno, address, disease, doc_id, doc_name, tabletName, quantity, intakeTime, time, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ississsssssss", $pat_id, $pat_name, $age, $phno, $address, $disease, $doc_id, $doc_name, $tabletName, $quantity, $intakeTime, $time, $date);

// Insert form data into the database for each prescription
for ($i = 0; $i < count($tabletNames); $i++) {
    $tabletName = $tabletNames[$i];
    $quantity = $quantities[$i];
    
    // Split intake time into separate times
    $intakeTimesArray = explode(" ", $intakeTimes[$i]);
    $timesArray = explode(" ", $times[$i]);

    // Insert a record for each intake time
    for ($j = 0; $j < count($intakeTimesArray); $j++) {
        $intakeTime = $intakeTimesArray[$j];
        $time = $timesArray[$j];

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo '<script>
            alert("Recorded successfully!!")
                    setTimeout(function() {
                        window.location.href = "after login.html";
                    }, 1000); // 3000 milliseconds = 1 second
                </script>';
            // Exit script to prevent further execution
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Close statement and connection
$stmt->close();
$conn->close();
?>