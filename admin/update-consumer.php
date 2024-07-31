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
$name = $barangay = $account_num = $registration_num = $meter_num = $type = $email = $phone ="";
$name_err = $barangay_err = $account_num_err = $registration_num_err = $meter_num_err = $type_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate barangay
    $input_barangay = trim($_POST["barangay"]);
    if(empty($input_barangay)){
        $barangay_err = "Please enter an barangay.";     
    } else{
        $barangay = $input_barangay;
    }

    // Validate account_num
    $input_account_num = trim($_POST["account_num"]);
    if(empty($input_account_num)){
        $account_num_err = "Please enter an account_num.";     
    } else{
        $account_num = $input_account_num;
    }

    // Validate registration_num
    $input_registration_num = trim($_POST["registration_num"]);
    if(empty($input_registration_num)){
        $registration_num_err = "Please enter an registration_num.";     
    } else{
        $registration_num = $input_registration_num;
    }

    // Validate meter_num
    $input_meter_num = trim($_POST["meter_num"]);
    if(empty($input_meter_num)){
        $meter_num_err = "Please enter an meter_num.";     
    } else{
        $meter_num = $input_meter_num;
    }

    // Validate type
    $input_type = trim($_POST["type"]);
    if(empty($input_type)){
        $type_err = "Please enter an type.";     
    } else{
        $type = $input_type;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($barangay_err) && empty($account_num_err) && empty($registration_num_err) && empty($meter_num_err) && empty($type_err)){
        // Prepare an update statement
        $sql = "UPDATE consumers SET name=?, barangay=?, account_num=?, registration_num=?, meter_num=?, type=?, email=?, phone=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssi", $param_name, $param_barangay, $param_account_num, $param_registration_num, $param_meter_num, $param_type, $param_email, $param_phone, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_barangay = $barangay;
            $param_account_num = $account_num;
            $param_registration_num = $registration_num;
            $param_meter_num = $meter_num;
            $param_type = $type;
            $param_id = $id;
            $param_email = trim($_POST["email"]);
            $param_phone = trim($_POST["phone"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                // echo '<div class="alert alert-success" role="alert">Records updated successfully.</div>';
                include('consumer.php');
                echo '<script>
                Swal.fire({
                title: "Success!",
                text: "Records updated successfully.",
                icon: "success",
                background: "#dff0d8",
                toast: true,
                position: "center",
                showConfirmButton: false,
                timer: 3000
                })
                </script>';
                exit();
            } else{
                // echo '<div class="alert alert-danger" role="alert">Oops! Something went wrong. Please try again later.</div>';
                include('consumer.php');
                echo '<script>
                Swal.fire({
                title: "Error!",
                text: "Oops! Something went wrong. Please try again later.",
                icon: "error",
                toast: true,
                position: "center",
                showConfirmButton: false,
                timer: 3000
                })
                </script>';
                exit();
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM consumers WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
                    $barangay = $row["barangay"];
                    $account_num = $row["account_num"];
                    $registration_num = $row["registration_num"];
                    $meter_num = $row["meter_num"];
                    $type = $row["type"];
                    $email = $row["email"];
                    $phone = $row["phone"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    // echo `<div class="alert alert-danger" role="alert">URL doesn't contain valid id.</div>`;
                    include('consumer.php');
                    echo '<script>
                    Swal.fire({
                    title: "Error!",
                    text: "URL doesnt contain valid id.",
                    icon: "error",
                    toast: true,
                    position: "center",
                    showConfirmButton: false,
                    timer: 3000
                    })
                    </script>';
                    exit();
                }
                
            } else{
                // echo `<div class="alert alert-danger" role="alert">Oops! Something went wrong. Please try again later.</div>`;
                include('consumer.php');
                echo '<script>
                Swal.fire({
                title: "Error!",
                text: "Oops! Something went wrong. Please try again later.",
                icon: "error",
                toast: true,
                position: "center",
                showConfirmButton: false,
                timer: 3000
                })
                </script>';
                exit();
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        // echo `<div class="alert alert-danger" role="alert">URL doesn't contain id parameter.</div>`;
        include('consumer.php');
        echo '<script>
        Swal.fire({
        title: "Error!",
        text: "URL doesnt contain id parameter",
        icon: "error",
        toast: true,
        position: "center",
        showConfirmButton: false,
        timer: 3000
        })
        </script>';
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Consumer</title>
    <?php include 'includes/links.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                Update Consumer
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container py-5">
            <div class="w-100 m-auto" style="max-width: 500px">
                <?php include 'forms/consumer-form.php'; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
</body>
</html>