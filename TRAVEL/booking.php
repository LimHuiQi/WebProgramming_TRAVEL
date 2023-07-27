<?php
// Retrieve form data
$booking_id = rand(10000, 99999); // Generate random booking ID
$destination = $_POST['destination'];
$no_of_adult = $_POST['no_of_adult'];
$no_of_children = $_POST['no_of_children'];
$user_checkin = $_POST['user_checkin'];
$user_checkout = $_POST['user_checkout'];

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.html');
    exit();
}

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Connect to the database
include_once('dbconnect.php');

// Prepare and execute the SQL statement to insert the data into the database
$sql = "INSERT INTO tbl_booking (booking_id, user_id, destination, no_of_adult, no_of_children, user_checkin, user_checkout)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiiss", $booking_id, $user_id, $destination, $no_of_adult, $no_of_children, $user_checkin, $user_checkout);
if ($stmt->execute()) {
    // Booking saved successfully
    echo "<script>
              window.alert('Booking successful!');
              window.location.href = 'index.html';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>