<?php
// Start output buffering
session_start();
ob_start();

// Initialize the session
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
require_once "config.php";

// Fetch the user ID from the session
$id = $_SESSION["id"];

// Fetch user info
$user_sql = "SELECT name, email, registration_date FROM consumers WHERE id = ?"; // Use parameterized query
if ($stmt = mysqli_prepare($link, $user_sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $user_result = mysqli_stmt_get_result($stmt);
    $user_row = mysqli_fetch_assoc($user_result);
    mysqli_stmt_close($stmt);
}

// Updated SQL queries with consumer name and meter number
$new_com_sql = "SELECT complaints.*, complaints.id AS complaint_id, consumers.name, consumers.meter_num 
                FROM complaints 
                LEFT JOIN consumers ON complaints.consumer_id = consumers.id 
                WHERE complaints.consumer_id = ? AND complaints.is_resolved = 0 
                ORDER BY complaints.date ASC";

$resolved_com_sql = "SELECT complaints.*, complaints.id AS complaint_id, consumers.name, consumers.meter_num 
                     FROM complaints 
                     LEFT JOIN consumers ON complaints.consumer_id = consumers.id 
                     WHERE complaints.consumer_id = ? AND complaints.is_resolved = 1 
                     ORDER BY complaints.date DESC";

if ($stmt = mysqli_prepare($link, $new_com_sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $new_com_result = mysqli_stmt_get_result($stmt);
    $new_com_total = mysqli_num_rows($new_com_result);
    mysqli_stmt_close($stmt);
}

if ($stmt = mysqli_prepare($link, $resolved_com_sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resolved_com_result = mysqli_stmt_get_result($stmt);
    $resolved_com_total = mysqli_num_rows($resolved_com_result);
    mysqli_stmt_close($stmt);
}

// Close connection
// mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaints</title>
    <?php include 'includes/links.php'; ?>

    <style>
        body{
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
        }
        .navbar-light-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-bottom: 2px solid black !important;
        }
   </style> 
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                Complaints
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container-fluid py-5">
            <div class="row">
                <div class="col-md-9">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new" aria-selected="true">
                                New <span class="badge badge-primary"><?php echo htmlspecialchars($new_com_total); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="resolved-tab" data-toggle="tab" href="#resolved" role="tab" aria-controls="resolved" aria-selected="false">
                                Resolved <span class="badge badge-primary"><?php echo htmlspecialchars($resolved_com_total); ?></span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active py-3" id="new" role="tabpanel" aria-labelledby="new-tab">
                            <?php
                                if ($new_com_total > 0) {
                                    echo '<div class="row">';
                                    while ($row = mysqli_fetch_array($new_com_result, MYSQLI_ASSOC)) {
                                        include 'includes/complaint-list.php';
                                    }
                                    echo '</div>';
                                }
                            ?>
                        </div>
                        <div class="tab-pane fade py-3" id="resolved" role="tabpanel" aria-labelledby="resolved-tab">
                            <?php
                                if ($resolved_com_total > 0) {
                                    echo '<div class="row">';
                                    while ($row = mysqli_fetch_array($resolved_com_result, MYSQLI_ASSOC)) {
                                        include 'includes/complaint-list.php';
                                    }
                                    echo '</div>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <?php include 'forms/complaint-form.php'; ?>
                </div>
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
