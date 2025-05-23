<?php
include 'db_connect.php';

# Check if user is logged in
if (!isset($_COOKIE['user_id'])) { 
    header("Location: login.php");
    exit();
}

$user_id = $_COOKIE['user_id']; # Get user ID from cookie

$sql = "SELECT role, username, display_name FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) { # Check if user exists by sql results with at least one attribute
    $row = $result->fetch_assoc(); # Fetch user role, username and display name
    $is_admin = ($row['role'] == 'admin');
    $username = $row['username'];
    $display_name = $row['display_name'];
} else {
    echo "User not found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $input_display_name = $_POST['display_name'];

    # Check if username name already exists
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username_exist = $row['id'] != $user_id;
    } else {
        $username_exist = false;
    }

    # Check if display name already exists
    $sql = "SELECT id FROM users WHERE display_name = '$input_display_name'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) { 
        $row = $result->fetch_assoc();
        $display_name_exist = $row['id'] != $user_id;
    } else {
        $display_name_exist = false;
    }

    # print message if username or display name already exists
    if ($username_exist) {
        echo "User " . $username . " already exist!";
    } else if ($display_name_exist) {
        echo "Someone already takes the name " . $input_display_name . "!";
    } else {
        
        # Update the user profile in the database when no name conflict
        $sql = "UPDATE users 
                SET username='$username', display_name='$display_name' 
                WHERE id='$user_id'";
        $result = mysqli_query($conn, $sql);

        if ($conn->query($sql) === TRUE) {
            echo "Profile updated successfully!";
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
    
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="post.php">Post</a></li>
            <li><a href="create_post.php">Create New Post</a></li>

            <?php if ($is_admin): ?>
                <li><a href="manage_post.php">Manage Posts</a></li>
            <?php endif; ?>

            <li><a href="edit_profile.php">Edit Profile</a></li>
            <li><a href="logout.php">Logout (<?php echo $display_name; ?>)</a></li>
        </ul>
    </nav>
    <div class="container">
        <h1>Edit Profile</h1>
        <form method="post" action="" enctype="multipart/form-data">
            
            User name: <input type="text" name="username" value="<?php echo $username; ?>" required><br>
            Display name: <input type="text" name="display_name" value="<?php echo $display_name; ?>" required><br>

            <input type="submit" value="Update Profile">
            <a href="reset_password.php"><input type="button" value="Reset password"></a>
        </form>
    </div>
</body>
</html>
