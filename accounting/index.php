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

$consumers_sql = "SELECT * FROM consumers;";
$consumers_result = mysqli_query($link, $consumers_sql);
$consumers_total = mysqli_num_rows($consumers_result);

$unpaid_sql = "SELECT * FROM readings WHERE status = 0;";
$unpaid_result = mysqli_query($link, $unpaid_sql);
$unpaid_total = mysqli_num_rows($unpaid_result);

$paid_sql = "SELECT * FROM readings WHERE status = 1;";
$paid_result = mysqli_query($link, $paid_sql);
$paid_total = mysqli_num_rows($paid_result);

// Fetch user-specific data
$complaints_sql = "SELECT * FROM complaints WHERE consumer_id = $id;";
$complaints_result = mysqli_query($link, $complaints_sql);

if (!$complaints_result) {
    die("Error executing query: " . mysqli_error($link));
}

$complaints_total = mysqli_num_rows($complaints_result);

// Fetch count of unpaid readings
$unpaid_count_sql = "SELECT COUNT(*) AS unpaid_count FROM readings WHERE consumer_id = $id AND status = 0;";
$unpaid_count_result = mysqli_query($link, $unpaid_count_sql);

if (!$unpaid_count_result) {
    die("Error executing query: " . mysqli_error($link));
}

$unpaid_count_row = mysqli_fetch_assoc($unpaid_count_result);
$unpaid_count = $unpaid_count_row['unpaid_count'] ? intval($unpaid_count_row['unpaid_count']) : 0;

// Fetch count of paid readings
$paid_count_sql = "SELECT COUNT(*) AS paid_count FROM readings WHERE consumer_id = $id AND status = 1;";
$paid_count_result = mysqli_query($link, $paid_count_sql);

if (!$paid_count_result) {
    die("Error executing query: " . mysqli_error($link));
}

$paid_count_row = mysqli_fetch_assoc($paid_count_result);
$paid_count = $paid_count_row['paid_count'] ? intval($paid_count_row['paid_count']) : 0;

// Fetch count of paid readings
$paid_count_sql = "SELECT COUNT(*) AS paid_count FROM readings WHERE consumer_id = $id AND status = 1;";
$paid_count_result = mysqli_query($link, $paid_count_sql);

if (!$paid_count_result) {
    die("Error executing query: " . mysqli_error($link));
}

$paid_count_row = mysqli_fetch_assoc($paid_count_result);
$paid_count = $paid_count_row['paid_count'] ? intval($paid_count_row['paid_count']) : 0;
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
    <?php include 'includes/links.php'; ?>
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
        }
        .navbar-light-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-bottom: 2px solid black !important;
            height: 60px;
        }
        .bg-paid-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .bg-consumer-gradient {
            background: linear-gradient(#ff66cc 0%, #9999ff 100%);  /* Pink gradient */
            color: white;
        }
        .bg-unpaid-gradient {
            background: linear-gradient(135deg, #f09819, #edde5d);
            color: white;
        }
        .card-custom {
            min-height: 80px; /* Further reduced minimum height */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px; /* Further reduced padding */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Slightly lighter shadow */
            border-radius: 8px; /* Maintain slight rounding */
            transition: transform 0.3s ease-in-out;
        }
        .card-custom:hover {
            transform: translateY(-3px); /* Less lift on hover */
        }
        .card-body {
            text-align: center;
        }
        .card-body h4 {
            font-size: 16px; /* Further reduced font size */
            margin-bottom: 3px;
        }
        .card-body small {
            font-size: 10px; /* Further reduced label size */
            letter-spacing: 0.5px;
        }
        .bx {
            font-size: 24px; /* Further reduced icon size */
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                Dashboard
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <br>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-paid-gradient text-white card-custom">
                        <div class="card-body">
                            <h4 class="mb-0"><?php echo $paid_total; ?></h4>
                            <small class="mb-0">Paid Bills</small>
                            <i class='bx bxs-check-circle bx-md'></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-unpaid-gradient text-white card-custom">
                        <div class="card-body">
                            <h4 class="mb-0"><?php echo $unpaid_total; ?></h4>
                            <small class="mb-0">Unpaid Bills</small>
                            <i class='bx bxs-credit-card bx-md'></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-consumer-gradient text-white card-custom">
                        <div class="card-body">
                            <h4 class="mb-0"><?php echo $consumers_total; ?></h4>
                            <small class="mb-0">Consumers</small>
                            <i class='bx bx-user bx-md'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>
</html>