<?php
// Process delete operation after confirmation
if(isset($_GET["id"]) && !empty($_GET["id"])){
    // Include config file
    require_once "config.php";
    
    $sql = "UPDATE consumers SET status=? WHERE id=?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "si", $param_status, $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        $param_status = trim($_GET["status"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            header("location: consumer.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
        // URL doesn't contain id parameter. Redirect to error page
        header("location: consumer.php");
        exit();
    }
}
?>