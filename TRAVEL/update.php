<?php
// Establish a database connection
include_once('dbconnect.php');

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if the user is not logged in
  header('Location: guest.html');
  exit();
}

// Retrieve the user ID from the session
$userId = $_SESSION['user_id'];

// Retrieve the edited values from the POST data
$name = $_POST['user_name'];
$email = $_POST['user_email'];
$insta = $_POST['user_insta'];
$follower = $_POST['user_follower'];

// Update the user profile in the database
$sql = "UPDATE tbl_user SET user_name = ?, user_email = ?, user_insta = ?, user_follower = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $name, $email, $insta, $follower, $userId);
if ($stmt->execute()) {
  // Update successful
  echo "<script>alert('Profile updated successfully.'); window.history.back();</script>";

} else {
  // Update failed
  echo "Failed to update the profile.";
}

$stmt->close();
$conn->close();
?>