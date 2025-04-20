<?php
include 'db_connect.php';

# Check if user is logged in
if (!isset($_COOKIE['user_id'])) {
    header("Location: login.php");
    exit();
}

# Get user ID from cookie
$user = $_COOKIE['user_id'];

# Get user role, username and display name 
$sql = "SELECT id, username, display_name 
        FROM users 
        WHERE id='$user'";
$result = $conn->query($sql);

# Check if user exists
if ($result->num_rows > 0) {
    # set local user role, username and display name
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $display_name = $row['display_name'];
} else {
    echo "User not found!";
    exit();
}

# Handle password reset
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # Get form data
    $password = $_POST['password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    # get the current password from the database
    $sql = "SELECT password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    $check_password = mysqli_fetch_assoc($result);

    # Check if the current password is correct
    if (!password_verify($password, $check_password['password'])) {
        echo "Invalid password!";
    # Check if new password and confirm password match
    } else if ($new_password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {
        # Use password_hash() to hash the new password before storing it in the database
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);

        # Update the password in the database
        $sql = "UPDATE users 
        SET password='$new_password' 
        WHERE id='$user'";

        # check for errors in executing the SQL
        if ($conn->query($sql) === TRUE) {
            echo "Password reset successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

        <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ECF51EJ15B"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-ECF51EJ15B');
    </script>
    
    <title>Reset password</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Reset password</h1>
        <form method="post" action="" enctype="multipart/form-data">
            Password: <input type="password" name="password" required><br>
            New password: <input type="password" name="new_password" required><br>
            Confirm Password: <input type="password" name="confirm_password" required><br>
            <input type="submit" value="Reset password">
            <a href = "edit_profile.php"><input type="button" value ="Back"></a> 
        </form>
    </div>
</body>
</html>
