<?php
// Process delete operation after confirmation
if(isset($_GET["id"]) && !empty($_GET["id"])){
    // Include config file
    require_once "config.php";
    
    // Prepare a delete statement
    $sql = "DELETE FROM consumers WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            echo `<div class="alert alert-success" role="alert">Record deleted successfully.</div>`;
            include("consumer.php");
            exit();
        } else{          
            echo `<div class="alert alert-danger" role="alert">Oops! Something went wrong. Please try again later.</div>`;
            include("consumer.php");
            exit();
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
        echo `<div class="alert alert-danger" role="alert">URL doesn't contain id parameter.</div>`;
            include("consumer.php");
            exit();
    }
}
?>