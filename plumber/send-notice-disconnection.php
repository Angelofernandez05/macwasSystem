<?php
// Ensure that the MySQLi connection is not closed until all operations are complete.
if (isset($_GET['consumer_id'])) {
    $consumer_id = $_GET['consumer_id'];

    // Include the database configuration file.
    require_once "config.php";
    $sql = "SELECT * FROM consumers WHERE id = ?";

    // Prepare the SQL statement.
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind the consumer_id parameter to the SQL statement.
        mysqli_stmt_bind_param($stmt, "i", $consumer_id);

        // Execute the SQL statement.
        if (mysqli_stmt_execute($stmt)) {
            // Get the result set from the executed statement.
            $result = mysqli_stmt_get_result($stmt);

            // Check if exactly one record was found.
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else {
                echo "No records found!";
                mysqli_stmt_close($stmt); // Close the statement before exiting.
                mysqli_close($link); // Close the MySQLi connection before exiting.
                exit;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
            mysqli_stmt_close($stmt); // Close the statement before exiting.
            mysqli_close($link); // Close the MySQLi connection before exiting.
            exit;
        }

        // Close the prepared statement.
        mysqli_stmt_close($stmt);
    } else {
        echo "Unable to prepare the SQL statement.";
        mysqli_close($link); // Close the MySQLi connection before exiting.
        exit;
    }

    // Close the MySQLi connection only after all operations are complete.
    mysqli_close($link);
} else {
    echo "Consumer ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill</title>
    <?php include 'includes/links.php'; ?>
    <style>
        /* Add any additional styling here if needed */
    </style>
</head>
<body>
    <div class="container pt-5">
        <div class="w-100 m-auto" style="max-width: 500px">
            <div class="text-center">
                <img class="img-fluid" src="logo.png" alt="Logo" width="150">
                <p class="text-uppercase text-center mb-0">Madridejos Community Waterworks System</p>
                <p class="text-uppercase text-center">
                    <small class="text-muted">Municipality of Madridejos</small><br>
                    <small class="text-muted">Madridejos, Cebu</small>
                </p>
                <br>
                <h5><strong>NOTICE OF DISCONNECTION</strong></h5>
            </div>

            <div class="mt-3">
                <p class="mb-0"><small class="text-muted mr-2">Name:</small><?php echo htmlspecialchars($row['name']); ?></p>
                <p class="mb-0"><small class="text-muted mr-2">Address:</small><?php echo htmlspecialchars($row['barangay']); ?></p>
                <p class="mb-0"><small class="text-muted mr-2">Meter No.:</small><?php echo htmlspecialchars($row['meter_num']); ?></p>
                <p class="mb-0"><small class="text-muted mr-2">Class:</small><?php echo htmlspecialchars($row['type']); ?></p>
            </div>
            <div class="mt-3">
                <p><small class="text-muted mr-2">Remarks:</small><strong>NO PAYMENT:</strong></p>
            </div>
            <div class="mt-4">
                <p class="font-weight-bold">Date of Disconnection: <?php echo date("F j, Y", strtotime($row['due_date'] . " +15 days")); ?></p>
            </div>
            <div class="mt-4">
                <p>Please pay the above billing month/s before the disconnection date. Thank you.</p>
                <div class="col-md-11 text-right"><strong>M C W S</strong></div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
      window.onload = function() { window.print(); }
    </script>
</body>
</html>
