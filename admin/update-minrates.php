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
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate type
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
        $ratex_err = "Please enter an rate x.";     
    } else{
        $ratex = $input_ratex;
    }

    // Validate rate y
    $input_ratey = trim($_POST["ratey"]);
    if(empty($input_ratey)){
        $ratey_err = "Please enter an rate y.";     
    } else{
        $ratey = $input_ratey;
    }

        // Validate rate y
        $input_ratez = trim($_POST["ratez"]);
        if(empty($input_ratez)){
            $ratez_err = "Please enter an rate z.";     
        } else{
            $ratez = $input_ratez;
        }
    
    // Check input errors before inserting in database
    if(empty($type_err) && empty($ratex_err) && empty($ratey_err) && empty($ratez_err)){
        // Prepare an update statement
        $sql = "UPDATE minimum_rates SET type=?, rate_x=?, rate_y=?, rate_z=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssi", $param_type, $param_ratex, $param_ratey, $param_ratez, $param_id);
            
            // Set parameters
            $param_type = $type;
            $param_ratex = $ratex;
            $param_ratey = $ratey;
            $param_ratez = $ratez;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM minimum_rates WHERE id = ?";
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
                    $type = $row["type"];
                    $ratex = $row["rate_x"];
                    $ratey = $row["rate_y"];
                    $ratez = $row["rate_z"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: settings.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: settings.php");
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
                <?php include 'forms/minrate-form.php'; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
</body>
</html>