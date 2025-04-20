<?php
include 'db_connect.php';

if (!isset($_COOKIE['user_id'])) { # Check if user is logged in
    header("Location: login.php");
    exit();
}

$user_id = $_COOKIE['user_id']; # Get user ID from cookie

# Check if user is admin
$sql = "SELECT role FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $is_admin = ($row['role'] == 'admin');
} else {
    echo "User not found!";
    exit();
}

# If user is not admin, redirect to post.php
if ($is_admin !== True) {
    header("Location: post.php");
    exit();
} 

# Get user display name
$sql = "SELECT display_name 
        FROM users 
        WHERE id='$user_id'";
$result = $conn->query($sql); # Get the SQL result
if ($result->num_rows > 0) { 
    $row = $result->fetch_assoc();
    $display_name = $row['display_name'];
} else {
    echo "User not found!";
    exit();
}

# Handle post deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_post'])) { # Check if delete post button was clicked and post ID was set
    $post_id = $_POST['post_id']; # Get post ID from form hidden input
    
    # Delete comments associated with the post before deleting the post itself
    $sql = "DELETE FROM comments WHERE post_id = '$post_id'";
    $conn->query($sql);

    # Delete the post from the database
    $sql = "DELETE FROM posts WHERE post_id = '$post_id'";
    $conn->query($sql);

    ## Redirect to manage_post.php after deletion
    header("Location: manage_post.php");
    exit();
}

# List all posts with user display name
$sql_list_posts = $conn->query("SELECT posts.*, users.display_name 
                      FROM posts 
                      JOIN users ON posts.user_id = users.id #
                      ORDER BY created_at DESC");
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
    
    <title>Manage Posts</title>
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
        <h1>Manage Posts</h1>
        
        <?php if ($sql_list_posts->num_rows > 0): ?>
            <!-- Fetch each post from the result set -->
            <?php while ($post = $sql_list_posts->fetch_assoc()): ?> 
                <div class="post">
                    <form method="POST" style="float: right">
                        <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                        <button type="submit" name="delete_post" >Delete post</button>
                    </form>
                    <!-- Display post title and content -->
                    <h2><h2><a class="post_detail" href="post_detail.php?post_id=<?= $post['post_id'] ?>"><?= $post['title'] ?></a></h2></h2>
                    <!-- Display the information of user related to that post -->
                    <p class="comment_meta">
                        By <?= $post['display_name'] ?> (<?=  $post['created_at'] ?>)
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?> 
            <!-- If no posts are found, display a message -->
            <p>No posts found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
