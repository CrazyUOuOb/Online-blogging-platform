<?php
include 'db_connect.php';

# Check if user is logged in
if (!isset($_COOKIE['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_COOKIE['user_id']; # Get user ID from cookie

# Get user role and display name
$sql = "SELECT role, display_name FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $is_admin = ($row['role'] == 'admin');
    $display_name = $row['display_name'];
} else {
    echo "User not found!";
    exit();
}

# Check if post ID is set in the URL
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

# select post details and its user display name
$sql = "SELECT posts.*, users.display_name 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.post_id = $post_id";
$result = $conn->query($sql);

# Check if post exists
if ($result->num_rows === 0) {
    echo "Post not found!";
}

# save the post details in a variable
$post = $result->fetch_assoc();
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

    <!--- Dynamic heading using current post title name -->
    <title><?= $post['title'] ?></title>

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
        <div class="post">
            <hr class="post-divider">
            <p class="post_meta">
                <?= $post['display_name'] . ": " . $post['created_at'] ?>
            </p>
            <h2><?= $post['title'] ?></h2>
            <!-- Display the content of the post -->
            <div class="content">
                <!-- Use nl2br to convert new lines to <br> tags -->
                <!-- Keep the 'enter' value for next line -->
                <?= nl2br(htmlspecialchars($post['content'], ENT_QUOTES)) ?>
            </div>

            <form method="post" action="add_comment.php">
                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                    <textarea name="comment_content" class="comment_box" placeholder="Leave your comment here" required></textarea>
                    <button type="submit">Add Comment</button>
            </form>

            <div class="comments">
                <hr>
                <h3>Comments</h3>
                <?php
                # Get all the comments by the current post ID
                $sql = "SELECT comments.*, users.display_name 
                        FROM comments 
                        JOIN users ON comments.user_id = users.id 
                        WHERE post_id = $post_id
                        ORDER BY created_at ASC";
                # store the sql result in $comments_result
                $comments_result = $conn->query($sql);
                ?>

                <?php if ($comments_result->num_rows > 0): ?>
                    <?php while ($comment = $comments_result->fetch_assoc()): ?>
                        <!-- Display each comment with user display name and created date -->
                        <div class="comment">
                            <p class="comment_meta">
                                <?= $comment['display_name'] . ": " . $comment['created_at'] ?>
                            </p>

                            <p class="comment_content">
                                <!-- Use nl2br to convert new lines to <br> tags -->
                                <!-- Keep the 'enter' value for next line -->
                                <?= nl2br(htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8')) ?>
                            </p>

                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- If no comments are found, display a message -->
                    <p>No comments here.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</body>
</html>
