<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";
$id = intval($_SESSION["id"]); // Ensure $id is an integer

// Fetch user-specific data
$complaints_sql = "SELECT * FROM complaints WHERE consumer_id = $id;";
$complaints_result = mysqli_query($link, $complaints_sql);

if (!$complaints_result) {
    die("Error executing query: " . mysqli_error($link));
}

$complaints_total = mysqli_num_rows($complaints_result);

// Fetch billing information
$billing_sql = "SELECT SUM(present - previous) AS total_due FROM readings WHERE consumer_id = $id AND status = 0;";
$billing_result = mysqli_query($link, $billing_sql);

if (!$billing_result) {
    die("Error executing query: " . mysqli_error($link));
}

$billing_row = mysqli_fetch_assoc($billing_result);
$total_due = $billing_row['total_due'] ? number_format((float)$billing_row['total_due'], 2, '.', '') : '0.00';

// Fetch user info
$user_sql = "SELECT name, email, registration_date FROM consumers WHERE id = $id;"; // Adjust column names as needed
$user_result = mysqli_query($link, $user_sql);

if (!$user_result) {
    die("Error executing query: " . mysqli_error($link));
}

$user_row = mysqli_fetch_assoc($user_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <?php include '../includes/links.php'; ?>
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Your CSS styles */
        body{
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
        }
        .navbar-light-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-bottom: 2px solid black !important;
            height: 60px;
        }
        .bg-success-gradient {
            background: linear-gradient(135deg, #43cea2, #185a9d);
            color: white;
        }
    </style>
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                Dashboard
            </span>
            <?php include '../includes/userMenu.php'; ?>
        </nav>

        <br>
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-success-gradient text-white ml-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?php echo $complaints_total; ?></h4>
                                    <small class="mb-0">Complaints</small>
                                </div>
                                <i class='bx bx-message-rounded-dots bx-md'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <?php include '../includes/scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
   
</body>
</html>
