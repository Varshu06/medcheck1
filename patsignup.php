<?php
// Database connection parameters
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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if username already exists
    $check_username_sql = "SELECT username FROM patsignup WHERE username = ?";
    $check_stmt = $conn->prepare($check_username_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Username already exists, display a message
        echo '<script>alert("Username already exists. Please choose a different username.")
        setTimeout(function() {
            window.location.href = "patsignup.html";
        }, 1000); // 3000 milliseconds = 1 seconds;</script>';  
    } else {
        // Username is available, proceed with insertion
        // Prepare SQL statement to insert data into the database
        $sql = "INSERT INTO patsignup (username, password) VALUES (?, ?)";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        
        // Execute the query
        if ($stmt->execute()) {
            // Close statement
            $stmt->close();
            // Redirect to another page after a delay
            echo '<script>
            alert("successfully registered!!")
                    setTimeout(function() {
                        window.location.href = "after login.html";
                    }, 1000); // 3000 milliseconds = 1 seconds
                </script>';
            // Exit script to prevent further execution
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close statement
    $check_stmt->close();
}

// Close connection
$conn->close();
?>
