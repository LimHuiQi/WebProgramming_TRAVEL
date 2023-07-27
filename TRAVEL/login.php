<?php
// Retrieve the form data
$userEmail = $_POST['email'];
$userPassword = $_POST['password'];

// Establish a database connection
include_once('dbconnect.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Query to check if the user exists
    $sql = "SELECT * FROM tbl_user WHERE user_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $storedPassword = $user['user_password'];

        // Verify user password
        if ($userPassword === $storedPassword) {
            // Login successful
            session_start();
            $_SESSION['user_id'] = $user['user_id']; // Store user ID in session for future use

            // Redirect to the home page or any other authenticated page
            $userName = $user['user_name'];

            echo "<script>
            window.alert('Login successful. Welcome, $userName!');
            window.location.href = 'index.html'; // Replace 'home.php' with the desired destination
            </script>";
            exit();
        } else {
            // Login failed
            echo "<script>alert('Invalid email or password. Please try again.'); window.history.back();</script>";
        }
    } else {
        // Login failed
        echo "<script>alert('Invalid email or password. Please try again.'); window.history.back();</script>";
    }
}

$conn->close();
?>
