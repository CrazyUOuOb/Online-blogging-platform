<?php
include 'db_connect.php';


# Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # Get the form data
    $username = $_POST['username'];
    $display_name = $_POST['display_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    # Check if username already exists
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $username_exist = mysqli_fetch_assoc($result);

    # Check if display name already exists
    $sql = "SELECT id FROM users WHERE display_name = '$display_name'";
    $result = mysqli_query($conn, $sql);
    $display_name_exist = mysqli_fetch_assoc($result);

    # Print message if username or display name already exists
    if ($username_exist) {
        echo "User already exist!";
    } else if ($display_name_exist) {
        echo "Someone already takes this name!";
    # Check if password and confirm password match
    } else if ($password !== $confirm_password) {
        # Print message if passwords do not match
        echo "Passwords do not match!";
    } else {
        # Use password_hash() to hash the password before storing it in the database
        $password = password_hash($password, PASSWORD_DEFAULT);

        # Insert the new user into the database
        $sql = "INSERT INTO users (username, display_name, password) 
            VALUES ('$username', '$display_name', '$password')";
        # check for errors in executing the SQL 
        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
            header("Location: login.php");
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
    
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form method="post" action="" enctype="multipart/form-data"> 
            User name: <input type="text" name="username" required><br>
            Display name: <input type="text" name="display_name" required><br>
            Password: <input type="password" name="password" required><br>
            Confirm Password: <input type="password" name="confirm_password" required><br>
            <input type="submit" value="Register">
            <a href = "index.php"><input type="button" value ="Return to Home page"></a> 
        </form>
    </div>
</body>
</html>
