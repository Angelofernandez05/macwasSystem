<?php
// Process delete operation after confirmation
if(isset($_GET["id"]) && !empty($_GET["id"])){
    $consumer_id = $_GET['consumer_id'];
    // Include config file
    require_once "config.php";
    
    // Prepare a delete statement
    $sql = "UPDATE readings SET status=?, date_paid=? WHERE id=?";
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssi", $param_status, $param_date_paid, $param_id);

        
        // Set parameters
        $param_status = 1;
        $param_date_paid = date('Y-m-d H:i:s', time()); // Set the current date as the value
        $param_id = $_GET["id"];
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records updated successfully. Redirect to landing page
            // header("location: reading.php?consumer_id=$consumer_id");
            echo '<script>
            Swal.fire({
            title: "Success!",
            text: "Records updated successfully.",
            icon: "success",
            toast: true,
            position: "top-right",
            showConfirmButton: false,
            timer: 3000
            })
            </script>';
            header('location: send-paid-notice.php?id='.$_GET["id"]);
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
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
        // URL doesn't contain id parameter. Redirect to error page
        header("location: reading.php?consumer_id=$consumer_id");
        echo '<script>
            Swal.fire({
            title: "Error!",
            text: "URL doesnt contain id parameter.",
            icon: "error",
            toast: true,
            position: "top-right",
            showConfirmButton: false,
            timer: 3000
            })
            </script>';
        exit();
    }
}
?>