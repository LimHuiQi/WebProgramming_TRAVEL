<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password1 = $_POST['password'];
    $confirmPassword = $_POST['password2']; // Corrected variable name

    // Validate form data (you can add more validation as needed)
    if (empty($name) || empty($email) || empty($password1) || empty($confirmPassword)) {
        echo "<script>window.alert('Please fill in all fields.'); window.history.back();</script>";
    } elseif ($password1 !== $confirmPassword) {
        echo "<script>window.alert('Passwords do not match.'); window.history.back();</script>";
    } else {
        // Database connection parameters
        include_once('dbconnect.php');

        // Check if the email already exists in the database
        $checkEmailQuery = "SELECT * FROM tbl_user WHERE user_email = ?";
        $checkEmailStmt = $conn->prepare($checkEmailQuery);
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $result = $checkEmailStmt->get_result();
        $existingUser = $result->fetch_assoc();

        if ($existingUser) {
            // Email already exists, display error message
            echo "<script>window.alert('Email already exists.'); window.history.back();</script>";

        } else {
            // Prepare and bind the SQL statement
            $stmt = $conn->prepare("INSERT INTO tbl_user (user_name, user_email, user_password) VALUES (?, ?, ?)"); // Removed user_contact from the query
            $stmt->bind_param("sss", $name, $email, $password1); // Removed $contact from the bind_param

            // Execute the statement
            if ($stmt->execute()) {
                // Registration successful, display success message
                echo "<script>window.alert('Registration successful!'); window.location.href = 'loginreg.html';</script>";
            } else {
                // Registration failed, display error message
                echo "<script>window.alert('Error: " . $stmt->error . "');</script>";
            }

            // Close statement and database connection
            $stmt->close();
        }

        // Close the check email statement
        $checkEmailStmt->close();

        // Close the database connection
        $conn->close();
    }
}
?>