<?php
// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "medcheck";
session_start();

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

    // Prepare SQL statement to select user data from the database
    $check_user_sql = "SELECT password FROM signup WHERE username = ?";
    $check_stmt = $conn->prepare($check_user_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    // Verify the password
    if ($check_stmt->num_rows == 1) {
        // Username exists, fetch the password from the database
        $check_stmt->bind_result($stored_password);
        $check_stmt->fetch();

        // Compare stored plaintext password with the password entered by the user
        if ($password === $stored_password) {
            // Password is correct, store the username in a session variable
            $_SESSION['username'] = $username;
            echo '<script>
            setTimeout(function() {
                window.location.href = "after login.html";
            }, 1000); // 1000 milliseconds = 1 second
            </script>';
            exit();
        } else {
            // Password is incorrect, show alert to the user
            echo '<script>
            alert("Incorrect password.");
            setTimeout(function() {
                window.location.href = "login.html";
            }, 1000); // 1000 milliseconds = 1 second
            </script>';
        }
    } else {
        // Username does not exist, show alert to the user
        echo '<script>
        alert("Username not found.");
        setTimeout(function() {
            window.location.href = "login.html";
        }, 1000); // 1000 milliseconds = 1 second
        </script>';
    }

    // Close statement
    $check_stmt->close();
}

// Close connection
$conn->close();
?>
