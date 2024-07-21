<?php
$servername = "127.0.0.1";
$port = "3306";
$username = "u510162695_maccwas";
$password = "1Macwas_pass";
$dbname = "u510162695_maccwas";

// Create connection
$link = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
?>
