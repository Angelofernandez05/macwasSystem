<?php
// Include config file
require_once "config.php";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Get URL parameter
    $id =  trim($_GET["id"]);

    // Prepare an update statement
    $sql = "UPDATE complaints SET is_resolved=? WHERE id=?";
         
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "si", $param_is_resolved, $param_id);
        
        // Set parameters
        $param_is_resolved = 1;
        $param_id = $id;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records updated successfully. Redirect to landing page
            header("location: complaint.php");
            exit();
        } else{
            // echo "Oops! Something went wrong. Please try again later.";
            echo '<script>
            Swal.fire({
            title: "Error!",
            text: "Oops! Something went wrong. Please try again later.",
            icon: "error",
            toast: true,
            position: "top-right",
            showConfirmButton: false,
            timer: 3000
            })
            </script>';
        }
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
}  else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: complaint.php");
    exit();
}
?>