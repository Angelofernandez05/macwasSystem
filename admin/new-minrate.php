<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$type = $ratex = $ratey = $ratez ="";
$type_err = $ratex_err = $ratey_err = $ratez_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_type = trim($_POST["type"]);
    if(empty($input_type)){
        $type_err = "Please enter a type.";
    } elseif(!filter_var($input_type, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $type_err = "Please enter a valid type.";
    } else{
        $type = $input_type;
    }
    
    // Validate rate x
    $input_ratex = trim($_POST["ratex"]);
    if(empty($input_ratex)){
        $ratex_err = "Please enter a rate x.";     
    } else{
        $ratex = $input_ratex;
    }

    // Validate rate y
    $input_ratey = trim($_POST["ratey"]);
    if(empty($input_ratey)){
        $ratey_err = "Please enter a rate y.";     
    } else{
        $ratey = $input_ratex;
    }

       // Validate rate y
       $input_ratez = trim($_POST["ratez"]);
       if(empty($input_ratez)){
           $ratez_err = "Please enter a rate z.";     
       } else{
           $ratez = $input_ratez;
       }

    
    // Check input errors before inserting in database
    if(empty($type_err) && empty($ratex_err) && empty($ratey_err) && empty($ratez_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO minimum_rates (type, rate_x, rate_y, rate_z) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_name, $param_barangay, $param_account_num, $param_registration_num, $param_meter_num, $param_type, $param_status, $param_email, $param_phone, $param_password);
            
            // Set parameters
            $param_type = $type;
            $param_ratex = $ratex;
            $param_ratey = $ratey;
            $param_ratez = $ratez;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                    header("location: settings.php");
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
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Consumer</title>
    <?php include 'includes/links.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                New Consumer
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container py-5">
            <div class="w-100 m-auto" style="max-width: 500px">
                <?php include 'forms/minrate-form.php'; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
</body>
</html>