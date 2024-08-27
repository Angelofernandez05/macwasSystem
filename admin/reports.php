<?php
// Initialize the session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once "config.php";

// Verify database connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Check for monthly and yearly totals
$date = date('m-Y');
$date_year = date('Y');

// Adjust the SQL queries to use valid columns
$monthly_paid_sql = "
    SELECT SUM(present - previous) AS total_paid_month 
    FROM readings 
    WHERE status = 1 
    AND DATE_FORMAT(date_paid, '%m-%Y') = '$date';
";
$yearly_paid_sql = "
    SELECT SUM(present - previous) AS total_paid_year 
    FROM readings 
    WHERE status = 1 
    AND DATE_FORMAT(date_paid, '%Y') = '$date_year';
";

// Execute the queries and check for errors
$monthly_paid_result = mysqli_query($link, $monthly_paid_sql);
if (!$monthly_paid_result) {
    die("ERROR: Could not execute $monthly_paid_sql. " . mysqli_error($link));
}

$yearly_paid_result = mysqli_query($link, $yearly_paid_sql);
if (!$yearly_paid_result) {
    die("ERROR: Could not execute $yearly_paid_sql. " . mysqli_error($link));
}

// Fetch the results
$monthly_paid_data = mysqli_fetch_assoc($monthly_paid_result);
$yearly_paid_data = mysqli_fetch_assoc($yearly_paid_result);

$monthly_paid_total = $monthly_paid_data['total_paid_month'];
$yearly_paid_total = $yearly_paid_data['total_paid_year'];

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <?php include 'includes/links.php'; ?>
    <link rel="icon" href="logo.png" type="image/icon type">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
           body{
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
        }

        .navbar-light-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-bottom: 2px solid black !important;
        }

        .bg-warning-gradient {
            background: linear-gradient(135deg, #43cea2, #185a9d);
            color: white;
        }

        .bg-danger-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

    </style>
    
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                Reports
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container-fluid py-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-warning-gradient text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?php echo $monthly_paid_total !== null ? $monthly_paid_total : '0'; ?><large class="ml-2">cu. m.</large></h4>
                                    <small class="mb-0">Total Paid Monthly</small>
                                </div>
                                <i class='bx bxs-calendar-check bx-md'></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger-gradient text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?php echo $yearly_paid_total !== null ? $yearly_paid_total : '0'; ?><large class="ml-2">cu. m.</large></h4>
                                    <small class="mb-0">Total Paid Yearly</small>
                                </div>
                                <i class='bx bxs-calendar-star bx-md'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
</body>
</html>
