<?php
// Initialize the session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once "config.php";

$consumers_sql = "SELECT * FROM consumers;";
$consumers_result = mysqli_query($link, $consumers_sql);
$consumers_total = mysqli_num_rows($consumers_result);

$complaints_sql = "SELECT * FROM complaints;";
$complaints_result = mysqli_query($link, $complaints_sql);
$complaints_total = mysqli_num_rows($complaints_result);

$unpaid_sql = "SELECT * FROM readings WHERE status = 0;";
$unpaid_result = mysqli_query($link, $unpaid_sql);
$unpaid_total = mysqli_num_rows($unpaid_result);

$paid_sql = "SELECT * FROM readings WHERE status = 1;";
$paid_result = mysqli_query($link, $paid_sql);
$paid_total = mysqli_num_rows($paid_result);

$date = date('m-Y');
$paid_sql_month = "SELECT * FROM readings WHERE status = 1 AND DATE_FORMAT(date_paid, '%m-%Y') = '$date';";
$paid_result_month = mysqli_query($link, $paid_sql_month);
$paid_total_month = mysqli_num_rows($paid_result_month);

$date_year = date('Y');
$paid_sql_year = "SELECT * FROM readings WHERE status = 1 AND DATE_FORMAT(date_paid, '%Y') = '$date_year';";
$paid_result_year = mysqli_query($link, $paid_sql_year);
$paid_total_year = mysqli_num_rows($paid_result_year);

// Fetch overdue billing statement data
$currDate = date('Y-m-d');
$sql_overdue = "SELECT *, (present - previous) AS used, consumers.id AS consumer_id, readings.id AS reading_id FROM readings 
                LEFT JOIN consumers ON readings.consumer_id = consumers.id 
                WHERE DATE(readings.due_date) < '$currDate' AND readings.status = 0";
$result_overdue = mysqli_query($link, $sql_overdue);

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <?php include 'includes/links.php'; ?>
    <link rel="icon" href="logo.png" type="image/icon type">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js CDN -->
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

        <div class="container-fluid py-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?php echo $consumers_total; ?></h4>
                                    <small class="mb-0">Consumers</small>
                                </div>
                                <i class='bx bx-user bx-md'></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-success text-white">
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
                <div class="col-md-4">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?php echo $unpaid_total; ?></h4>
                                    <small class="mb-0">Unpaid Bills</small>
                                </div>
                                <i class='bx bxs-credit-card bx-md'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?php echo $paid_total; ?></h4>
                                    <small class="mb-0">Paid Bills</small>
                                </div>
                                <i class='bx bxs-check-circle bx-md'></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?php echo $paid_total_month; ?></h4>
                                    <small class="mb-0">Paid Bills Monthly</small>
                                </div>
                                <i class='bx bxs-calendar-check bx-md'></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?php echo "0"; ?></h4>
                                    <small class="mb-0">Paid Bills Yearly</small>
                                </div>
                                <i class='bx bxs-calendar-star bx-md'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h4>Overdue Billing Statement</h4>
                <div>
                    <?php if (mysqli_num_rows($result_overdue) > 0): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Meter No.</th>
                                    <th>Date of Disconnection</th>
                                    <th>Due Date</th>
                                    <th>Present</th>
                                    <th>Previous</th>
                                    <th>Used</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_array($result_overdue)): ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['meter_num']; ?></td>
                                        <td><?php echo date("F j, Y", strtotime($row['due_date'] . " +15 day")); ?></td>
                                        <td><?php echo date_format(date_create($row['due_date']), 'F j, Y'); ?></td>
                                        <td><?php echo $row['present']; ?></td>
                                        <td><?php echo $row['previous']; ?></td>
                                        <td><?php echo number_format((float)$row['used'], 2, '.', ''); ?></td>
                                        <td>
                                            <div class="d-flex" style="gap: 0.3rem">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm custom-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="bx bxs-printer"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                        <a target="_blank" href="print-reading.php?id=<?php echo $row['reading_id']; ?>" class="dropdown-item" title="Print Billing Statement" data-toggle="tooltip">Billing Statement</a>
                                                        <a target="_blank" href="print-nod.php?id=<?php echo $row['reading_id']; ?>" class="dropdown-item" title="Print Billing Statement" data-toggle="tooltip">Notice of Disconnection</a>
                                                    </div>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm custom-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class='bx bx-mail-send'></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                        <a target="_self" href="send-billing-statement.php?id=<?php echo $row['reading_id']; ?>" class="dropdown-item" title="Send Billing Statement" data-toggle="tooltip">Billing Statement</a>
                                                        <a target="_self" href="send-notice-disconnection.php?id=<?php echo $row['reading_id']; ?>" class="dropdown-item" title="Send Notice of Disconnection" data-toggle="tooltip">Notice of Disconnection</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No overdue billing statements found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-5">
                <h4>Dashboard Chart</h4>
                <canvas id="consumersChart" width="400" height="200"></canvas>
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
    <script>
        // Chart.js Configuration
        $(document).ready(function() {
            var ctx = document.getElementById('consumersChart').getContext('2d');
            var consumersChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Consumers', 'Complaints', 'Unpaid Bills', 'Paid Bills', 'Paid Bills Monthly', 'Paid Bills Yearly'],
                    datasets: [{
                        label: 'Data Count',
                        data: [
                            <?php echo $consumers_total; ?>,
                            <?php echo $complaints_total; ?>,
                            <?php echo $unpaid_total; ?>,
                            <?php echo $paid_total; ?>,
                            <?php echo $paid_total_month; ?>,
                            <?php echo $paid_total_year; ?>
                        ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
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
        });
    </script>
</body>
</html>
