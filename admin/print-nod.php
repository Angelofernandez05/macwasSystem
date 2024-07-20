<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id =  trim($_GET["id"]);
    
    $sql = "SELECT *, (present - previous) as used, readings.status as reading_status FROM readings LEFT JOIN consumers ON consumers.id = readings.consumer_id WHERE readings.id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = $id;
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
            } else{
                header("location: reading.php?consumer_id=$consumer_id");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    mysqli_stmt_close($stmt);
}  else{
    header("location: consumer.php");
    exit();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill</title>
    <?php include 'includes/links.php'; ?>
</head>
<body>
    <div class="container pt-5">
        <div class="w-100 m-auto" style="max-width: 500px">
            <div class="text-center">
                <img class="img-fluid" src="logo.png" alt="" width=150>
                <p class="text-uppercase text-center mb-0">madridejos community waterworks system</p>
                <p class="text-uppercase text-center">
                    <small class="text-muted">municipality of madridejos</small><br />
                    <small class="text-muted">madridejos, cebu</small>
                </p>
                <h5>NOTICE OF DISCONNECTION</h5>
            </div>

            <div class="mt-3">
                <p class="mb-0"><small class="text-muted mr-2">Name:</small><?php echo $row['name']; ?></p>
                <p class="mb-0"><small class="text-muted mr-2">Address:</small><?php echo $row['barangay']; ?></p>
                <p class="mb-0"><small class="text-muted mr-2">Meter No.:</small><?php echo $row['meter_num']; ?></p>
            </div>
            <div class="mt-3">
                <p><small class="text-muted mr-2">Remarks:</small>NO PAYMENT</p>
            </div>
            <div class="mt-3">
                <p class="font-weight-bold">Date of Disconnection: <?php echo date("F j, Y", strtotime($row['due_date'] ." +15 day") ); ?></p>
            </div>
            <div class="mt-3">
                <p>Please pay the above billing month/s before the disconnection date. Thank you</p>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
      window.onload = function() { window.print(); }
    </script>
</body>
</html>