<?php
// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Perform database connection
include('dbconnect.php');

// Retrieve user_id, user_name, and user_email from tbl_user
$userQuery = "SELECT user_id, user_name, user_email FROM tbl_user";
$userResult = mysqli_query($conn, $userQuery);
$userData = mysqli_fetch_assoc($userResult);
$userId = $userData['user_id'];
$userName = $userData['user_name'];
$userEmail = $userData['user_email'];

// Prepare and execute the SQL statement
$sql = "INSERT INTO tbl_feedback (user_id, user_name, user_email, message_subject, user_message) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "issss", $userId, $userName, $userEmail, $subject, $message);

if (mysqli_stmt_execute($stmt)) {
    // Successful save, display success message and navigate back to index.html
    echo '<script>alert("Form submitted successfully!"); window.location.href = "contact.html";</script>';
    exit();
} else {
    // Error occurred
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
