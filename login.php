<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; # Get username from input
    $password = $_POST['password']; # Get password from input

    $sql = "SELECT * FROM users WHERE username='$username'"; # Check if the user exists in the database
    $result = $conn->query($sql);

    if ($result->num_rows > 0) { # Check if user exists by sql results with at least one attribute
        $row = $result->fetch_assoc(); # get the user data from sql
        if (password_verify($password, $row['password'])) { # Verify the password with the hashed password
            # Set a cookie for the user ID for 30 days
            setcookie("user_id", $row['id'], time() + (86400 * 30), "/"); 

            header("Location: index.php"); # Redirect to the home page after successful login
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid username!";
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
    
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="post" action="">
            Username: <input type="text" name="username" required><br>
            Password: <input type="password" name="password" required><br>
            <input type="submit" value="Login">
            <a href = "register.php"><input type="button" value ="Register"></a> 
            <a href = "index.php"><input type="button" value ="Back"></a> 
        </form>
    </div>
</body>
</html>
