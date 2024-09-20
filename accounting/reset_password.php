<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                // echo `<div class="alert alert-success" role="alert">Password updated successfully.</div>`;
                session_destroy();
                include('login.php');
                echo '<script>
                Swal.fire({
                title: "Success!",
                text: "Password updated successfully.",
                icon: "success",
                toast: true,
                position: "top-right",
                showConfirmButton: false,
                timer: 3000
                })
                </script>';
                exit();
            } else{
                echo `<div class="alert alert-danger" role="alert">Oops! Something went wrong. Please try again later.</div>`;
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

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url("account.webp");
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: 200vh;
        }
        .alert {
            font-size: 14px;
            padding: 8px 12px;
            text-align: center;
            margin: 10px;
            max-width: 600px;
            position: fixed;
            top: 40px;
            right: 10px;
            z-index: 9999;
        }

    </style>
</head>
<body class="bg-light">
    <div class="container pt-5">
        <div class="w-100 m-auto bg-white border rounded p-3" style="max-width: 400px">
            <h2>Reset Password</h2>
            <p>Please fill out this form to reset your password.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                    <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a class="btn btn-link ml-2" href="index.php">Cancel</a>
                </div>
            </form>
        </div>
    </div> 
    <script>
    // Hide the alert after 3 seconds
    setTimeout(function(){
        var alert = document.querySelector('.alert');
        if (alert) {
        alert.style.display = 'none';
        }
    }, 3000);
    </script>   
      <script>
        // Disable right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, and Ctrl+U
        document.addEventListener('keydown', function(e) {
            if (e.keyCode == 123) { // F12
                e.preventDefault();
            }
            if (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74)) { // Ctrl+Shift+I or J
                e.preventDefault();
            }
            if (e.ctrlKey && e.keyCode == 85) { // Ctrl+U
                e.preventDefault();
            }
        });
    </script>
</body>
</html>