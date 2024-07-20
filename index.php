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
    <?php include 'includes/links.php'; ?>
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                Dashboard
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container py-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h4 class="mb-0">Hi, <?php echo htmlspecialchars($user_row['name']); ?></h4>
                            <small class="mb-0">Name</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <h4 class="mb-0"><?php echo htmlspecialchars($user_row['email']); ?></h4>
                            <small class="mb-0">Email</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h4 class="mb-0"><?php echo date('F j, Y', strtotime($user_row['registration_date'])); ?></h4>
                            <small class="mb-0">Registered On</small>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-warning text-white">
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
                <div class="col-md-6">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?php echo $total_due; ?></h4>
                                    <small class="mb-0">Total Due</small>
                                </div>
                                <i class='bx bxs-credit-card bx-md'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h4>Complaints Over Time</h4>
                <canvas id="complaintsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        // Chart.js Configuration
        $(document).ready(function() {
            var ctx = document.getElementById('complaintsChart').getContext('2d');
            var complaintsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [], // You might want to fetch and include the actual data here
                    datasets: [{
                        label: 'Complaints Over Time',
                        data: [], // Fetch and include complaint data here
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: true
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // SweetAlert for logout confirmation
            function confirmLogout() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be logged out.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, log me out!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'logout.php';
                    }
                });
                return false; // Prevent the default link behavior
            }
        });
    </script>
</body>
</html>
