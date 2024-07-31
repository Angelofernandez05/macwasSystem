<?php

// Database connection parameters
$servername = '127.0.0.1';
$username = 'u510162695_macwas';
$password = '1Macwas_pass'; // Ensure to set a proper password here
$dbname = 'u510162695_macwas';
$port = 3306;

// Create connection
$link = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
