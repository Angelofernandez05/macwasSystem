<?php
session_start();
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];

    $id = 1;
    $stmt = $link->prepare("SELECT password FROM security WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hash = $row['password'];

        // echo "Hashed password in database: $hash<br>";
        // echo "Password entered by user: $password<br>";

        if ($password == $hash) {

            echo '<div class="alert alert-success" role="alert">Access granted.</div>';
            include('settings.php');
            exit();
        } else {
            echo '<div class="alert alert-success" role="alert">Incorrect password.</div>';
            include('index.php');
            exit();
        }

    } else {
        // handle user not found case
    }
}
?>
