<?php
include 'db_connect.php';

if (!isset($_COOKIE['user_id'])) { # Check if user is logged in
    header("Location: login.php");
    exit(); # End the function
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_COOKIE['user_id'];
    $post_id = $_POST['post_id'];
    $content = $_POST['comment_content'];

    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', '$user_id', '$content')";
    if ($conn->query($sql) === false) {
        # if the sql was failed, show the error message
        echo "Error: " . $sql . "<br>" . $conn->error;
        exit(); 
    }
}

# Refresh current page after adding the comment
header("Location: post_detail.php?post_id=" . $post_id);
exit();
?>
