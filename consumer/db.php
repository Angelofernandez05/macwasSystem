<?php 
try {
    $conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=u510162695_macwas', 'u510162695_macwas', '1Macwas_pass');
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
