<!DOCTYPE html>
<?php
// Initialize the session
ob_start();
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

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
    <title>Bill</title>
    <?php include 'includes/links.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                Bill
            </span>
            <?php include 'includes/userMenu.php'; ?>
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
                                    echo '<img width="100px" height="100px" src="uploads/' . $row["screenshot"] . '">';
                                } ?>
                            </td>
                            <?php
                            echo "<td>" . $status . "</td>";
                            echo "<td>";
                            echo '<a target="_blank" href="print-reading.php?id=' . $row['id'] . '" class="mr-2" title="Print Record" data-toggle="tooltip"><i class="bx bxs-printer"></i></a>';
                            echo '<a target="_blank" href="att_payment.php?id=' . $row['id'] . '" class="mr-2" title="Attach Payment" data-toggle="tooltip"><i class="bx bxs-cog"></i></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo '<script>
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

    <?php include 'includes/scripts.php'; ?>
</body>
</html>
<?php
// End output buffering and flush all output
ob_end_flush();
?>
