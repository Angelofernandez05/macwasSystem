<?php
// Database credentials
define('DB_SERVER', '127.0.0.1'); // Or 'localhost'
define('DB_USERNAME', 'u510162695_macwas');
define('DB_PASSWORD', '1Macwas_pass');
define('DB_NAME', 'u510162695_macwas');

// Attempt to connect to MySQL database
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
