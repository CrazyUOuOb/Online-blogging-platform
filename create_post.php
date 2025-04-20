<?php
include 'db_connect.php';

# Check if user is logged in
if (!isset($_COOKIE['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_COOKIE['user_id']; # Get user ID from cookie

$sql = "SELECT role, display_name FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); # Fetch user role and display name
    $is_admin = ($row['role'] == 'admin'); 
    $display_name = $row['display_name']; 
} else {
    echo "User not found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_COOKIE['user_id']; # Get user ID from cookie
    $title = $_POST['title']; # Get post title from input
    $content = ($_POST['content']); # Get post content from input

    $sql = "INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
    if ($conn->query($sql) === True) { 
        # If the SQL query was successful, redirect to post.php
        header("Location: post.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; # Show error message
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
    
    <title>Create New Post</title>
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
        <h1>Create New Post</h1>
        <form method="POST">
            <textarea name="title" class="post_title" placeholder="Give a title" required></textarea>
            <textarea name="content" class="post" placeholder="Start your post here" rows="10" required></textarea>
            <button type="submit">Publish Post</button>
        </form>
    </div>
</body>
</html>
