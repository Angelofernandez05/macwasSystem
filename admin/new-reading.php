<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if(!isset($_GET["id"]) || empty(trim($_GET["id"]))){
    header("location: consumer.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Reading</title>
    <?php include 'includes/links.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                New Reading
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container">
            <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
</body>
</html>