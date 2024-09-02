<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

// Debugging: Check if $pdo is set
if (!isset($pdo)) {
    die("ERROR: Database connection is not established.");
}

// Initialize variables
$row = null;

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    
    // Prepare a select statement
    $sql = "SELECT name, barangay, meter_num, type, due_date FROM consumers WHERE id = ?";
    
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Fetch result row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Handle query execution error
            echo "Oops! Something went wrong. Please try again later.";
            exit;
        }
    } else {
        // Handle preparation error
        echo "Oops! Something went wrong. Please try again later.";
        exit;
    }
} else {
    // Handle case where ID is not provided or invalid
    header("location: consumer.php"); // Redirect back to consumer page if ID is missing
    exit();
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
