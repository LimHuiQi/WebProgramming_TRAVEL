<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <div class="container mt-4 mb-4 p-3 d-flex justify-content-center">
    <div class="card p-4">
      <div class="image d-flex flex-column justify-content-center align-items-center">
        <button class="btn btn-secondary">
          <img src="assets/profile.png" height="100" width="100" />
        </button>

        <?php
        // Establish a database connection
        include_once('dbconnect.php');

        // Check if the user is logged in
        session_start();
        if (!isset($_SESSION['user_id'])) {
          // Redirect to the login page if the user is not logged in
          header('Location: login.html');
          exit();
        }

        // Retrieve the user ID from the session
        $userId = $_SESSION['user_id'];

        // Query to retrieve the user's profile information
        $sql = "SELECT * FROM tbl_user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          // User profile found
          $user = $result->fetch_assoc();
          $userName = $user['user_name'];
          $userEmail = $user['user_email'];
          $userInsta = $user['user_insta'];
          $userFollower = $user['user_follower'];
          $datereg = $user['datereg'];

          // Display the user profile with editable fields
          echo "<span class='name mt-3' data-field='user_name'>$userName</span>";
          echo "<span class='idd' data-field='user_email'>$userEmail</span>";
        } else {
          // User profile not found
          echo "<p>User profile not found.</p>";
        }
        ?>

        <div class="d-flex flex-row justify-content-center align-items-center gap-2">
          <span class="idd1" data-field="user_insta">
            <?php echo $userInsta; ?>
          </span>
          <span><i class="fa fa-copy"></i></span>
        </div>
        <div class="d-flex flex-row justify-content-center align-items-center mt-3">
          <span class="number" data-field="user_follower">
            <?php echo $userFollower; ?> <span class="follow">Followers</span>
          </span>
        </div>
        <div class="d-flex mt-2">
          <button class="btn1 btn-dark" onclick="editProfile()">Edit Profile</button>
          <button class="btn1 btn-success d-none" onclick="saveChanges()">Save Changes</button>
        </div>
        <div class="d-flex mt-2">
          <a class="btn1 btn-dark" href="booking_details.php">Booking Details</a>
        </div>
        <div class="d-flex mt-2">
          <a class="btn3 btn-dark btn-danger" href="logout.php">Logout</a>
        </div>
        <div class="px-2 rounded mt-4 date">
          <span class="join">Joined
            <?php echo $datereg; ?>
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

  <script>
    function editProfile() {
      // Hide the "Edit Profile" button and show the "Save Changes" button
      $('.btn-dark').addClass('d-none');
      $('.btn-success').removeClass('d-none');

      // Make the profile fields editable
      $('[data-field]').attr('contenteditable', 'true');
    }

    function saveChanges() {
      // Retrieve the edited values from the input fields
      var name = $('[data-field="user_name"]').text();
      var email = $('[data-field="user_email"]').text();
      var insta = $('[data-field="user_insta"]').text();
      var follower = $('[data-field="user_follower"]').text();

      // Perform the form submission using hidden form and POST method
      var form = $('<form></form>');
      form.attr('method', 'post');
      form.attr('action', 'update.php');

      var nameField = $('<input></input>');
      nameField.attr('type', 'hidden');
      nameField.attr('name', 'user_name');
      nameField.val(name);

      var emailField = $('<input></input>');
      emailField.attr('type', 'hidden');
      emailField.attr('name', 'user_email');
      emailField.val(email);

      var instaField = $('<input></input>');
      instaField.attr('type', 'hidden');
      instaField.attr('name', 'user_insta');
      instaField.val(insta);

      var followerField = $('<input></input>');
      followerField.attr('type', 'hidden');
      followerField.attr('name', 'user_follower');
      followerField.val(follower);

      form.append(nameField);
      form.append(emailField);
      form.append(instaField);
      form.append(followerField);

      $('body').append(form);

      // Submit the form when the "Save Changes" button is clicked
      form.submit();
    }

  </script>
</body>

</html>