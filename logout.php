<?php
# Delete the cookie by setting its expiration time to the past
setcookie("user_id", "", time() - 3600, "/");  

header("Location: index.php"); # Redirect to the home page after logout
exit();
?>
