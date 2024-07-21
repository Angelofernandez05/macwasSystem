<?php
session_start();
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = trim($_POST['password']); // Trim whitespace

    $id = 1;
    $stmt = $link->prepare("SELECT password FROM security WHERE id = ?");
    
    if ($stmt === false) {
        die('Database query preparation failed: ' . $link->error);
    }
    
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hash = $row['password'];

            if (password_verify($password, $hash)) {
                $_SESSION['login_message'] = '<div class="alert alert-success" role="alert">Access granted.</div>';
                header('Location: dashboard.php');
                exit();
            } else {
                $_SESSION['login_message'] = '<div class="alert alert-danger" role="alert">Incorrect password.</div>';
                header('Location: login.php');
                exit();
            }
        } else {
            $_SESSION['login_message'] = '<div class="alert alert-danger" role="alert">User not found.</div>';
            header('Location: login.php');
            exit();
        }
    } else {
        die('Database query execution failed: ' . $stmt->error);
    }
}
?>
