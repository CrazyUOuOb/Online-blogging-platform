<?php
$DB_HOST = $_ENV["DB_HOST"];
$DB_USER = $_ENV["DB_USER"];
$DB_PASSWORD = $_ENV["DB_PASSWORD"];
$DB_NAME = $_ENV["DB_NAME"];
$DB_PORT = $_ENV["DB_PORT"];

try {
    $conn = mysqli_connect("$DB_HOST", "$DB_USER", "$DB_PASSWORD", "$DB_NAME", "$DB_PORT");
} catch (Exception $e) {
    echo '<!DOCTYPE html>
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
        <div class="container" style="text-align: center; padding: 50px;">
            <h1>Database is Waking Up</h1>
            <p>Please wait a moment and refresh the page.</p>
            <p><small>The database might need a few seconds to become available. This is normal for hosted services.</small></p>
        </div>
    </body>
    </html>';
    exit();
}

?>
