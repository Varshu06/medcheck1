<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appointment Reminder</title>
<Style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('hos.jpg') ; /* Replace 'background.jpg' with your image path */
        background-size: cover;
        background-position: center;
        
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    #logo {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 100px; /* Adjust the width as needed */
        height: auto;
    }
    #button-container {
        text-align: center;
        margin-top: 50px;

    }
    #quote {
        color: white;
        text-align: center;
        font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        align-items: center;
    }
    button {
        padding: 10px 20px;
        font-size: 18px;
        background-color: #0A4F51;
        color: #c7ae64;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    #button-container a {
        padding-right: 15px;
    }
    .redtext{
        color:white;
    }
</style>
</head>
<body>
    <img id="logo" src="logo1.png" alt="Logo"> <!-- Replace 'logo.png' with your logo path -->
    <div id="quote">
        <p>"Difficulties may come and go, but your resilience remains constant, empowering you to overcome any challenge."</p>
    </div>


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

    // Set timezone to Asia/Kolkata
    date_default_timezone_set('Asia/Kolkata');

    // Get current time with seconds
    $currentDateTime = date('Y-m-d H:i:s');
    
    echo '<p style="color: white;" id="currentTime">Current Time: ' . $currentDateTime . '</p>';

    // Fetch appointments from the database where the scheduled time is close to the current time
    $sql = "SELECT pat_name, tabletName, CONCAT(date, ' ', time) AS datetime FROM add_new WHERE CONCAT(date, ' ', time) <= '$currentDateTime' AND CONCAT(date, ' ', time) >= DATE_SUB('$currentDateTime', INTERVAL 1 MINUTE)";
    $result = $conn->query($sql);

    // Close database connection
    $conn->close();
    ?>

    <!-- Notification script -->
    <script>
        // Function to update current time with seconds
        function updateTime() {
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var seconds = now.getSeconds().toString().padStart(2, '0');
            var currentTimeString = now.getFullYear() + '-' + (now.getMonth() + 1).toString().padStart(2, '0') + '-' + now.getDate().toString().padStart(2, '0') + ' ' + hours + ':' + minutes + ':' + seconds;
            document.getElementById('currentTime').textContent = 'Current Time: ' + currentTimeString;

            // Refresh time every second
            setTimeout(updateTime, 1000);
        }

        // Call updateTime function to start updating time
        updateTime();

        // Send reminders for fetched appointments
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pat_name = $row['pat_name'];
                $tabletName = $row['tabletName'];
                $scheduledTime = $row['datetime'];
                
                // Calculate the time difference in seconds
                $scheduledDateTime = strtotime($scheduledTime);
                $currentTime = strtotime($currentDateTime);
                $timeDifference = abs($scheduledDateTime - $currentTime);
                
                // Check if the time difference is within 1 minute
                if ($timeDifference <= 60) {
                    // Send browser notification
                    echo "if ('Notification' in window) {
                            Notification.requestPermission().then(function (permission) {
                                if (permission === 'granted') {
                                    var notification = new Notification('Reminder', {
                                        body: 'Reminder for $pat_name - $tabletName at $scheduledTime',
                                        requireInteraction: true
                                    });
                                }
                            });
                        }";
                }
            }
        } else {
            // Display message if no appointments are found
            echo "document.body.innerHTML += '<p style=\"color: white;\">Currently No Remainders Are Available.</p>';";

        }
        ?>
    </script>

</body>
</html>
