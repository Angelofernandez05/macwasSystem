<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login");
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

// Fetch total used
$used_sql = "SELECT SUM(present - previous) AS total_used FROM readings WHERE consumer_id = $id;";
$used_result = mysqli_query($link, $used_sql);

if (!$used_result) {
    die("Error executing query: " . mysqli_error($link));
}

$used_row = mysqli_fetch_assoc($used_result);
$total_used = $used_row['total_used'] ? number_format((float)$used_row['total_used'], 2, '.', '') : '0.00';

// Fetch user info
$user_sql = "SELECT name, email, registration_date FROM consumers WHERE id = $id;";
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
    <title>Consumer Dashboard</title>
    <?php include 'includes/links.php'; ?>
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar-light-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-bottom: 2px solid black !important;
            height: 65px;
            margin-left: 15px;
        }
        .bg-complaints-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
        }
        .bg-used-gradient {
            background: linear-gradient(135deg, #ff6f61, #d84a38);
            color: white;
        }
        .clock {
            font-size: 1.1rem;
            font-family: 'Verdana', sans-serif;
            font-weight: 550;
            color: black;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            margin-left: 15px;
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .marquee {
            overflow: hidden;
            position: relative;
            white-space: nowrap;
            box-sizing: border-box;
            height: 40px;
            display: flex;
            align-items: center;
        }
        .marquee-content {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 8s linear infinite;
            font-size: 30px;
            color: darkblue;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .gradient-text {
            background: linear-gradient(45deg, #36d1dc, #5b86e5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .chart-container {
            position: relative;
            height: 400px; /* Adjusted height to fit within viewport */
            width: 100%; /* Full width for responsiveness */
            max-width: 700px; /* Max width for large screens */
            margin: 20px auto; /* Centered */
            margin-left: 20px;
            overflow: hidden; /* Hide overflow to prevent scroll bars */
        }

        .content {
            flex: 1; /* Take up remaining space */
            padding: 20px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
        }
        .col-md-4 {
            flex: 1 1 30%; /* Responsive column */
            margin: 10px;
        }
        .card {
            border-radius: 8px;
            overflow: hidden;
        }
        @media (max-width: 768px) {
            .col-md-4 {
                flex: 1 1 100%; /* Full width on small screens */
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <div class="navbar-header">
                <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                    <i class='bx bx-menu mr-3' style='color: black; cursor: pointer; font-size: 2rem'></i>
                    <div class="marquee">
                        <div class="marquee-content">Macwas Water Billing System 2.0</div>
                    </div>
                </span>
            </div>
            <?php include 'includes/userMenu.php'; ?>
        </nav>
        <div class="clock" id="clock"></div>
        <div class="content">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-complaints-gradient text-white ml-1">
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
                    <div class="card bg-used-gradient text-white ml-1">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?php echo $total_used; ?></h4>
                                    <small class="mb-0">Total Used</small>
                                </div>
                                <i class='bx bx-tachometer bx-md'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            <br>
            <!-- Chart Container -->
            <div class="mt-5">
                <h4>Dashboard Chart</h4>
                <div class="chart-container">
                    <canvas id="dashboardChart"></canvas>
                </div>
            </div>
        </div>
    </section>  
    <?php include 'includes/scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('keydown', function (e) {
        // Disable F12
        if (e.key === 'F12') {
            e.preventDefault();
        }
        // Disable Ctrl + Shift + I
        if (e.ctrlKey && e.shiftKey && e.key === 'I') {
            e.preventDefault();
        }
        });

        // Disable right-click
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
        });
          // JavaScript for Clock
          function updateClock() {
            var now = new Date();
            
            // Time
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            var timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
            
            // Date
            var day = now.getDate();
            var month = now.getMonth() + 1; // January is 0!
            var year = now.getFullYear();
            var dateString = day + '/' + month + '/' + year;

            // Set both date and time
            document.getElementById('clock').textContent = timeString + ' | ' + dateString;
        }

        setInterval(updateClock, 1000);
        updateClock();  // Initialize the clock immediately

        // JavaScript for Chart.js
        var ctx = document.getElementById('dashboardChart').getContext('2d');
        var dashboardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Complaints', 'Total Used'],
                datasets: [{
                    label: 'Total',
                    data: [<?php echo $complaints_total; ?>, <?php echo $total_used; ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)', // Blue for complaints
                        'rgba(255, 99, 132, 0.2)'  // Red for total used
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',   // Blue for complaints
                        'rgba(255, 99, 132, 1)'    // Red for total used
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
