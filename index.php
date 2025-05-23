<?php
session_start(); 
include 'db_connect.php';

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
    
    <title>Online blogging platform</title>
    <link rel="stylesheet" type="text/css" href="indexstyle.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php
            if (isset($_COOKIE['user_id'])) {
                # Get user ID from cookie
                $user_id = $_COOKIE['user_id'];

                # Get user role and display name
                $sql = "SELECT role, display_name FROM users WHERE id = '$user_id'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) { # Check if user exists with role and display name by their ID
                    $row = $result->fetch_assoc(); # store the result from sql
                    $is_admin = ($row['role'] == 'admin');
                    $display_name = $row['display_name'];
                }  else {
                    header("Location: logout.php");
                }

                echo '<li><a href="post.php">Post</a></li>';
                echo '<li><a href="create_post.php">Create New Post</a></li>';
                if ($is_admin):
                    echo '<li><a href="manage_post.php">Manage Posts</a></li>';
                endif;
                
                echo '<li><a href="edit_profile.php">Edit Profile</a></li>';
                echo '<li><a href="logout.php">Logout (' . $display_name . ')</a></li>'; # Display logout button with display name
                
            } else {
                echo '<li><a href="login.php">Login</a></li>';
                echo '<li><a href="register.php">Register</a></li>';
            }
            ?>
        </ul>
    </nav>

    <div class="container">
        <header>
            <h1>Welcome to the Online blogging platform</h1>
            <p>Share the information to everyone!</p>
        </header>

        <?php if (!isset($_COOKIE['user_id'])): ?> 
            <section class="auth-prompt">
                <h2>Get Started</h2>
                <p>Join our platform to create or find a post.</p>
                <div class="auth-buttons">
                    <a href="login.php" class="btn">Login</a>
                    <a href="register.php" class="btn">Register</a>
                </div>
            </section>
        <?php endif; ?>
    </div>
</body>
</html>
