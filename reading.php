<!DOCTYPE html>
<?php
// Initialize the session
ob_start();
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login");
    exit;
}

// Include config file
require_once "config";

// Fetch the user ID from the session
$id = $_SESSION["id"];

// Fetch user info
$user_sql = "SELECT name FROM consumers WHERE id = ?"; // Use parameterized query
if ($stmt = mysqli_prepare($link, $user_sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $user_result = mysqli_stmt_get_result($stmt);
    $user_row = mysqli_fetch_assoc($user_result);
    mysqli_stmt_close($stmt);
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consumer Bill</title>
    <?php include 'includes/links'; ?>
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar-light-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-bottom: 2px solid black;
            height: 65px;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            color: black;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .table td img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        .container-fluid {
            flex: 1;
            padding: 20px;
        }
        .card {
            border-radius: 8px;
            overflow: hidden;
        }
        .card img {
            width: 100%;
            height: auto;
            max-height: 150px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <span class="navbar-brand mb-0 h1">
                <i class='bx bx-menu mr-3' style='color: black; cursor: pointer; font-size: 2rem'></i>
                Bill
            </span>
            <?php include 'includes/userMenu'; ?>
        </nav>

        <div class="container-fluid py-5">
            <div>
                <?php
                // Attempt select query execution
                $sql = "SELECT *, (present - previous) as used FROM readings WHERE consumer_id = ?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        echo '<table class="table table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Due Date</th>";
                        echo "<th>Date</th>";
                        echo "<th>Present</th>";
                        echo "<th>Previous</th>";
                        echo "<th>Used</th>";
                        echo "<th>Reference</th>";
                        echo "<th>Screenshot</th>";
                        echo "<th>Status</th>";
                        echo "<th>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            $status = 'Pending';

                            if ($row['status'] == 1) {
                                $status = 'PAID';
                            } else if ($row['status'] == 2) {
                                $status = 'Waiting for approval';
                            }

                            echo "<tr>";
                            echo "<td class='text-uppercase'>" . date_format(date_create($row['due_date']), 'F j, Y') . "</td>";
                            echo "<td class='text-uppercase'>" . date_format(date_create($row['reading_date']), 'Y-F') . "</td>";
                            echo "<td>" . $row['present'] . "</td>";
                            echo "<td>" . $row['previous'] . "</td>";
                            echo "<td>" . number_format((float)$row['used'], 2, '.', '') . "</td>";
                            echo "<td>" . $row['ref'] . "</td>";
                            ?>
                            <td>
                                <?php if (!empty($row["screenshot"])) {
                                    echo '<img src="uploads/' . $row["screenshot"] . '" alt="Screenshot">';
                                } ?>
                            </td>
                            <?php
                            echo "<td>" . $status . "</td>";
                            echo "<td>";
                            echo '<a target="_blank" href="print-reading?id=' . $row['id'] . '" class="mr-2" title="bill" data-toggle="tooltip"><i class="bx bxs-file btn btn-danger btn-sm mb-3 rounded-circle"></i></a>';
                            echo '<a target="_blank" href="att_payment?id=' . $row['id'] . '" class="mr-2" title="Attach Payment" data-toggle="tooltip"><i class="bx bxs-wallet btn btn-primary btn-sm mb-3 ml-2 rounded-circle"></i></a>';                            
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo '<script>q
                        Swal.fire({
                            title: "Info!",
                            text: "No records were found.",
                            icon: "info",
                            toast: true,
                            position: "top-right",
                            showConfirmButton: false,
                            timer: 3000
                        })
                        </script>';
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close connection
                mysqli_close($link);
                ?>
            </div>
        </div>
    </section>
    <script>
        // Disable right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, and Ctrl+U
        document.addEventListener('keydown', function(e) {
            if (e.keyCode == 123) { // F12
                e.preventDefault();
            }
            if (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74)) { // Ctrl+Shift+I or J
                e.preventDefault();
            }
            if (e.ctrlKey && e.keyCode == 85) { // Ctrl+U
                e.preventDefault();
            }
        });
    </script>

    <?php include 'includes/scripts'; ?>
</body>
</html>
<?php
// End output buffering and flush all output
ob_end_flush();
?>
