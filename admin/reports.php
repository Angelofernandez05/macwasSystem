<?php
// Initialize the session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once "config.php";

// Get current date information for reports
$curr_month = date('m');
$curr_year = date('Y');

$unpaid_sql = "SELECT * FROM readings WHERE status = 0;";
$unpaid_result = mysqli_query($link, $unpaid_sql);
$unpaid_total = mysqli_num_rows($unpaid_result);

$paid_sql = "SELECT * FROM readings WHERE status = 1;";
$paid_result = mysqli_query($link, $paid_sql);
$paid_total = mysqli_num_rows($paid_result);

// ... (existing code)

// Set the date variable for current month and year
$date = date('m-Y'); // Current month and year in 'mm-yyyy' format

// Query to get total income for the current month
// $income_monthly_sql = "SELECT SUM(amount) AS total_income FROM readings WHERE status = 1 AND DATE_FORMAT(date_paid, '%m-%Y') = '$date';";
// $income_monthly_result = mysqli_query($link, $income_monthly_sql);
// $total_income_monthly = 0;
// if ($income_monthly_result && mysqli_num_rows($income_monthly_result) > 0) {
//     $row = mysqli_fetch_assoc($income_monthly_result);
//     $total_income_monthly = $row['total_income'];
// }


$date_year = date('Y');
$paid_sql_year = "SELECT * FROM readings WHERE status = 1 AND DATE_FORMAT(date_paid, '%Y') = '$date_year';";
$paid_result_year = mysqli_query($link, $paid_sql_year);
$paid_total_year = mysqli_num_rows($paid_result_year);


// Query to get total paid bills for the current year
$yearly_paid_sql = "SELECT COUNT(*) AS yearly_paid FROM readings WHERE status = 1 AND YEAR(date_paid) = '$curr_year';";
$yearly_paid_result = mysqli_query($link, $yearly_paid_sql);
$yearly_paid = 0;
if ($yearly_paid_result && mysqli_num_rows($yearly_paid_result) > 0) {
    $row = mysqli_fetch_assoc($yearly_paid_result);
    $yearly_paid = $row['yearly_paid'];
}

// Query to get total unpaid bills for the current year
$yearly_unpaid_sql = "SELECT COUNT(*) AS yearly_unpaid FROM readings WHERE status = 0 AND YEAR(date_paid) = '$curr_year';";
$yearly_unpaid_result = mysqli_query($link, $yearly_unpaid_sql);
$yearly_unpaid = 0;
if ($yearly_unpaid_result && mysqli_num_rows($yearly_unpaid_result) > 0) {
    $row = mysqli_fetch_assoc($yearly_unpaid_result);
    $yearly_unpaid = $row['yearly_unpaid'];
}


// Query for lose connection
$lose_connection_sql = "SELECT COUNT(id) AS lose_connection FROM consumers WHERE status = 'disconnected';";
$lose_connection_result = mysqli_query($link, $lose_connection_sql);
$lose_connection = 0;
if ($lose_connection_result && mysqli_num_rows($lose_connection_result) > 0) {
    $row = mysqli_fetch_assoc($lose_connection_result);
    $lose_connection = $row['lose_connection'];
}
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
    <title>Admin Dashboard</title>
    <?php include 'includes/links.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Add Font Awesome -->
    <style>
        body {
            background-color: #f7f9fb;
            font-family: Arial, sans-serif;
        }
        .dashboard {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Change to 2 columns */
            gap: 20px;
            margin: 40px;
        }
        .dashboard-section {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
        }
        .dashboard-section h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .dashboard-section p {
            font-size: 18px;
            color: #333;
        }
        .dashboard-section small {
            display: block;
            margin-top: 5px;
            color: #777;
        }
        .chart-container {
            height: 300px;
            width: 100%;
        }
        .print-button {
            margin-top: 10px;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #007bff;
            transition: transform 0.2s;
        }
        .print-button:hover {
            color: #0056b3;
            transform: scale(1.1);
        }
        .bx-printer {
            font-size: 24px; /* Set icon size */
        }
        .navbar-light-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-bottom: 2px solid black !important;
            margin-left: 10px;
        }
    </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<section class="home-section">
    <nav class="navbar navbar-light-gradient bg-white border-bottom">
        <span class="navbar-brand mb-0 h1 d-flex align-items-center">
            <i class='bx bx-menu mr-3' style='color: black; cursor: pointer; font-size: 2rem'></i>
            Reports
        </span>
        <?php include 'includes/userMenu.php'; ?>
    </nav>
    <div class="dashboard">
            <div class="dashboard-section">
            <h2>Income Monthly</h2>
            <!-- <p>₱<?php echo number_format($total_income_monthly, 2); ?></p> -->
            <small>Total Income This Month (<?php echo date('F Y'); ?>)</small>
            <div id="monthlyIncomeChart" class="chart-container"></div>
            <!-- <button class="print-button" onclick="printChart('monthlyIncomeChart', 'Income Monthly: ₱<?php echo number_format($total_income_monthly, 2); ?>')">
                <i class='bx bxs-printer'></i>
            </button> -->
        </div>

        <div class="dashboard-section">
            <h2>Income Yearly</h2>
            <p>₱<?php echo number_format($paid_total_year); ?></p>
            <small>Total Income This Year (<?php echo date('Y'); ?>)</small>
            <div id="yearlyIncomeChart" class="chart-container"></div>
            <!-- <button class="print-button" onclick="printChart('yearlyIncomeChart', 'Income Yearly: ₱<?php echo number_format($paid_total_year); ?>')">
                <i class='bx bxs-printer'></i>
            </button> -->
        </div>

        <div class="dashboard-section">
            <h2>Total Paid Bills Monthly</h2>
            <p><?php echo number_format($paid_total); ?></p>
            <small>Total Amount Paid Bills</small>
            <div id="totalBilledChart" class="chart-container"></div>
        </div>

        <div class="dashboard-section">
            <h2>Total Unpaid Bills Monthly</h2>
            <p><?php echo number_format($unpaid_total); ?></p>
            <small>Total Unpaid Bills</small>
            <div id="totalUnbilledChart" class="chart-container"></div>
        </div>

        <div class="dashboard-section">
            <h2>Total Paid Bills Yearly</h2>
            <p><?php echo number_format($yearly_paid); ?></p>
            <small>Total Paid Bills This Year (<?php echo date('Y'); ?>)</small>
            <div id="yearlyPaidChart" class="chart-container"></div>
        </div>

        <div class="dashboard-section">
            <h2>Total Unpaid Bills Yearly</h2>
            <p><?php echo number_format($yearly_unpaid); ?></p>
            <small>Total Unpaid Bills This Year (<?php echo date('Y'); ?>)</small>
            <div id="yearlyUnpaidChart" class="chart-container"></div>
        </div>

        <div class="dashboard-section">
            <h2>Disconnection Monthly</h2>
            <p><?php echo $lose_connection; ?> users</p>
            <small>Disconnected Users</small>
            <div id="loseConnectionChart" class="chart-container"></div>
        </div>
        <div class="dashboard-section">
            <h2>Disconnection Yearly</h2>
            <p><?php echo $lose_connection; ?> users</p>
            <small>Disconnected Users</small>
            <div id="loseConnectionChart" class="chart-container"></div>
    </div>
    </div>
    </div>
    <div class="mt-5">
                <h4><label class="icon">⚠️ <strong>Overdue Billing Statement:</strong></label>
                </h4>
                <div>
                    <?php if (mysqli_num_rows($result_overdue) > 0): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>👤Name</th>
                                    <th>⏲️Meter No.</th>
                                    <th>📅Date of Disconnection</th>
                                    <th>⌛ Due Date</th>
                                    <th>🟢Present</th>
                                    <th>⏮️Previous</th>
                                    <th>🛠️Used</th>
                                    <th>🔄Action</th>
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
                                                    <button class="btn btn-sm btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class='bx bxs-printer'></i> 
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                        <a target="_blank" href="print-reading.php?id=<?php echo $row['reading_id']; ?>" class="dropdown-item" title="Print Billing Statement" data-toggle="tooltip">Billing Statement</a>
                                                    </div>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class='bx bx-mail-send'></i> 
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                        <a target="_self" href="send-billing-statement.php?id=<?php echo $row['reading_id']; ?>" class="dropdown-item" title="Send Billing Statement" data-toggle="tooltip">Job Order</a>
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
</section>

<?php include 'includes/scripts.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Monthly Income Chart
    var monthlyIncomeOptions = {
        series: [{
            name: 'Monthly Income',
            data: [200000, 300000, 400000, 500000, 600000, 1000000] // Sample Data
        }],
        chart: {
            type: 'area',
            height: 300,
            zoom: { enabled: false }
        },
        colors: ['#00E396'],
        xaxis: {
            categories: ['September','October']
        },
        title: {
            text: 'Monthly Income Trend',
            align: 'left'
        },
        dataLabels: {
            enabled: false
        },
    };
    var monthlyIncomeChart = new ApexCharts(document.querySelector("#monthlyIncomeChart"), monthlyIncomeOptions);
    monthlyIncomeChart.render();

    // Yearly Income Chart
    var yearlyIncomeOptions = {
        series: [{
            name: 'Yearly Income',
            data: [30000, 45000, 55000] // Sample Data
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        colors: ['#008FFB'],
        xaxis: {
            categories: ['2024', '2025', '2026']
        },
        title: {
            text: 'Yearly Income Overview',
            align: 'left'
        },
        dataLabels: {
            enabled: true
        },
    };
    var yearlyIncomeChart = new ApexCharts(document.querySelector("#yearlyIncomeChart"), yearlyIncomeOptions);
    yearlyIncomeChart.render();

    // Chart for Total Billed
    var totalBilledOptions = {
        series: [{
            name: 'Billed',
            data: [100, 200, 150, 300, 250] // Sample Data
        }],
        chart: {
            type: 'donut',
            height: 300
        },
        colors: ['#FF4560', '#775DD0'],
        labels: ['January', 'February', 'March', 'April', 'May','June','July','August','September','October','November','December'],
        title: {
            text: 'Total Billed Distribution',
            align: 'left'
        },
    };
    var totalBilledChart = new ApexCharts(document.querySelector("#totalBilledChart"), totalBilledOptions);
    totalBilledChart.render();

    // Chart for Total Unbilled
    var totalUnbilledOptions = {
        series: [{
            name: 'Unbilled',
            data: [50, 70, 90, 40, 80] // Sample Data
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        colors: ['#FEB019'],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May']
        },
        title: {
            text: 'Total Unbilled Overview',
            align: 'left'
        },
        dataLabels: {
            enabled: true
        },
    };
    var totalUnbilledChart = new ApexCharts(document.querySelector("#totalUnbilledChart"), totalUnbilledOptions);
    totalUnbilledChart.render();

    // Chart for Lose Connection
    var loseConnectionOptions = {
        series: [{
            name: 'Lose Connection',
            data: [5, 8, 12] // Sample Data
        }],
        chart: {
            type: 'line',
            height: 300
        },
        colors: ['#FF4560'],
        xaxis: {
            categories: ['Month 1', 'Month 2', 'Month 3']
        },
        title: {
            text: 'Lose Connection Trend',
            align: 'left'
        },
        dataLabels: {
            enabled: true
        },
    };
    // Existing chart scripts...

    // Yearly Paid Bills Chart
    var yearlyPaidOptions = {
        series: [{
            name: 'Paid Bills',
            data: [<?php echo implode(',', array_fill(0, 12, $yearly_paid)); ?>] // Sample Data, replace as needed
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        colors: ['#00E396'],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        title: {
            text: 'Total Paid Bills Yearly',
            align: 'left'
        },
        dataLabels: {
            enabled: true
        },
    };
    var yearlyPaidChart = new ApexCharts(document.querySelector("#yearlyPaidChart"), yearlyPaidOptions);
    yearlyPaidChart.render();

    // Yearly Unpaid Bills Chart
    var yearlyUnpaidOptions = {
        series: [{
            name: 'Unpaid Bills',
            data: [<?php echo implode(',', array_fill(0, 12, $yearly_unpaid)); ?>] // Sample Data, replace as needed
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        colors: ['#FF4560'],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        title: {
            text: 'Total Unpaid Bills Yearly',
            align: 'left'
        },
        dataLabels: {
            enabled: true
        },
    };
    var yearlyUnpaidChart = new ApexCharts(document.querySelector("#yearlyUnpaidChart"), yearlyUnpaidOptions);
    yearlyUnpaidChart.render();
    var loseConnectionChart = new ApexCharts(document.querySelector("#loseConnectionChart"), loseConnectionOptions);
    loseConnectionChart.render();
    
    function printChart(chartId, title) {
        var chart = document.getElementById(chartId);
        var win = window.open('', '', 'height=500,width=800');
        win.document.write('<html><head><title>' + title + '</title>');
        win.document.write('</head><body >');
        win.document.write('<h1>' + title + '</h1>');
        win.document.write(chart.outerHTML);
        win.document.write('</body></html>');
        win.document.close();
        win.print();
    }
</script>
</body>
</html>
