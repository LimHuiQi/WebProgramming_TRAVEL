<?php
session_start();

// Retrieve the user ID from the session
$userId = $_SESSION['user_id'];

// Check if the booking ID is provided
if (isset($_POST['booking_id'])) {
    $bookingId = $_POST['booking_id'];

    // Establish a database connection
    include_once('dbconnect.php');

    // Prepare the delete statement
    $sql = "DELETE FROM tbl_booking WHERE booking_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $bookingId, $userId);

    // Execute the delete statement
    if ($stmt->execute()) {
        // Deletion successful
        $_SESSION['delete_success'] = true;
        $_SESSION['delete_message'] = "Booking deleted successfully!";
    } else {
        // Deletion failed
        $_SESSION['delete_success'] = false;
        $_SESSION['delete_message'] = "Error deleting booking: " . $conn->error;
    }

    // Close the database connection
    $conn->close();

    // Redirect back to the booking details page
    header("Location: booking_details.php");
    exit();
} else {
    // Redirect if the booking ID is not provided
    header("Location: booking_details.php");
    exit();
}
?>
