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
        $sql = "UPDATE consumers SET password = ?, isUpdated = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_password, $param_isUpdated, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_isUpdated = 1;
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
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

     <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link rel="icon" href="logo.png" type="image/icon type">
</head>
<body class="bg-light">
    <section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
          <div class="card text-black" style="border-radius: 25px;">
            <div class="card-body p-md-2">
              <div class="row justify-content-center">
              <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">Reset Password</p>
              <p class="text-center">Please fill out this form to reset your password.</p>
                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                  <form class="mx-1 mx-md-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="d-flex flex-row align-items-center mb-4">
                    </div>
 
                    <div class="d-flex flex-row align-items-center mb-4 mt-5">
                      <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="form3Example3c"><i class="bi bi-chat-left-dots-fill"></i> New Password</label>
                        <input type="password" id="form3Example3c" class="form-control form-control-lg py-3 <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>" name="new_password" autocomplete="off" placeholder="Enter your password" style="border-radius:25px ;" />
                        <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                      </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="form3Example4c"><i class="bi bi-chat-left-dots-fill"></i> Confirm Password</label>
                        <input type="password" id="form3Example4c" class="form-control form-control-lg py-3 <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" name="confirm_password" autocomplete="off" placeholder="Enter your password" style="border-radius:25px ;" />
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                      </div>
                    </div>
                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                      <input type="submit" value="Submit" name="submit" class="btn btn-primary btn-lg text-light my-2 py-3" style="width:100% ; border-radius: 10px; font-weight:600;" style="border-radius:25px ;" />
                      <input type="reset" value="Cancel" name="reset" class="btn btn-secondary btn-lg text-light my-2 py-3" style="width:100% ; border-radius: 10px; font-weight:600; margin-left: 10px;" style="border-radius:25px ;" />
                    </div>

                  </form>
                  
                </div>
                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                  <img src="signup.png" class="img-fluid" alt="Sample image" height="300px" width="500px">

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
    
    <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>
</html>