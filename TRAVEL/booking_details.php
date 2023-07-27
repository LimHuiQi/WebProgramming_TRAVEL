<?php
// Retrieve the user ID from the session
session_start();
$userId = $_SESSION['user_id'];

// Establish a database connection
include_once('dbconnect.php');

// Fetch booking history for the logged-in user from the tbl_booking table
$sql = "SELECT booking_id, destination, user_checkin, user_checkout, no_of_adult, no_of_children FROM tbl_booking WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Close the PHP tag here to enter HTML mode
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Booking-Responsive table || Learningrobo</title>
    <link rel="stylesheet" href="booking_details.css">
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th>Destination</th>
                <th>Check-In Date</th>
                <th>Check-Out Date</th>
                <th>Number of Adults</th>
                <th>Number of Children</th>
                <th>Booking Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display booking history as options in the search bar
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $bookingId = $row['booking_id'];
                    $destination = $row['destination'];
                    $checkin = $row['user_checkin'];
                    $checkout = $row['user_checkout'];
                    $adults = $row['no_of_adult'];
                    $children = $row['no_of_children'];

                    // Determine the booking status based on the check-in date
                    $today = date("Y-m-d");
                    if ($checkin > $today) {
                        $bookingStatus = "Upcoming";
                    } else {
                        $bookingStatus = "Passed";
                    }
                    ?>
                    <tr>
                        <td data-label="Destination"><?php echo $destination; ?></td>
                        <td data-label="Check-In Date"><?php echo $checkin; ?></td>
                        <td data-label="Check-Out Date"><?php echo $checkout; ?></td>
                        <td data-label="Number of Adults"><?php echo $adults; ?></td>
                        <td data-label="Number of Children"><?php echo $children; ?></td>
                        <td data-label="Booking Status"><?php echo $bookingStatus; ?></td>
                        <td data-label="Action">
                            <form method="POST" action= "delete.php" onsubmit="return confirmDelete();">
                                <input type="hidden" name="booking_id" value="<?php echo $bookingId; ?>">
                                <button type="submit">Cancel</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='7'>No booking history found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function confirmDelete() {
            if (confirm("Are you sure you want to cancel this booking?")) {
                return true;
            }
            return false;
        }
    </script>
</body>
</html>
