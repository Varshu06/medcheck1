<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    header("Location: login.html");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];
?>