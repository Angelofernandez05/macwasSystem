<?php
    // Include config file
    require_once "config.php";
    
    // Define variables and initialize with empty values
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";
    $success_message = "";
    $error_message = "";

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    
        // Validate username
        if(empty(trim($_POST["username"]))){
            $username_err = "Please enter a username.";
        } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
            $username_err = "Username can only contain letters, numbers, and underscores.";
        } else{
            // Prepare a select statement
            $sql = "SELECT id FROM users WHERE username = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                
                // Set parameters
                $param_username = trim($_POST["username"]);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    /* store result */
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username_err = "This username is already taken.";
                    } else{
                        $username = trim($_POST["username"]);
                    }
                } else{
                    $error_message = "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        
        // Validate password
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter a password.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have atleast 6 characters.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Password did not match.";
            }
        }
        
        // Check input errors before inserting in database
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
                
                // Set parameters
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Registration successful
                    $success_message = "Registration successfully, You may now login!";
                    // Clear form inputs
                    $username = $password = $confirm_password = "";
                } else{
                    $error_message = "Oops! Something went wrong. Please try again later.";
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
        <title>Sign Up</title>
    
        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
        <link rel="icon" href="logo.png" type="image/icon type">

        <!-- SweetAlert -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="bg-light">

    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-2">
                            <div class="row justify-content-center">
                                <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">Sign up</p>
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                    <form class="mx-1 mx-md-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <label class="form-label" for="form3Example1c"><i class="bi bi-person-circle"></i> Username</label>
                                                <input type="text" id="form3Example1c" class="form-control form-control-lg py-3 <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" name="username" autocomplete="off" placeholder="enter username" style="border-radius:25px ;" />
                                                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <label class="form-label" for="form3Example3c"><i class="bi bi-chat-left-dots-fill"></i> Password</label>
                                                <input type="password" id="form3Example3c" class="form-control form-control-lg py-3 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" name="password" autocomplete="off" placeholder="enter your password" style="border-radius:25px ;" />
                                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div class="form-outline flex-fill mb-0">
                                                <label class="form-label" for="form3Example4c"><i class="bi bi-chat-left-dots-fill"></i> Confirm Password</label>
                                                <input type="password" id="form3Example4c" class="form-control form-control-lg py-3 <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" name="confirm_password" autocomplete="off" placeholder="enter your password" style="border-radius:25px ;" />
                                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                            <input type="submit" value="Register" name="register" class="btn btn-primary btn-lg text-light my-2 py-3" style="width: 100%; border-radius: 10px; font-weight: 600;">
                                            <input type="button" value="Reset" name="reset" onclick="location.href='reset-password.php';" class="btn btn-secondary btn-lg text-light my-2 py-3" style="width: 100%; border-radius: 10px; font-weight: 600; margin-left: 10px;">
                                        </div>


                                    </form>
                                    <p align="center">Already have an acccount? Login <a href="login.php" class="text-primary" style="font-weight:600; text-decoration:none;">here</a>.</p>
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

    <!-- SweetAlert Script -->
    <script>
        <?php if(!empty($success_message)): ?>
        Swal.fire({
            title: "Success!",
            text: "<?php echo $success_message; ?>",
            icon: "success",
            toast: true,
            position: "center",
            showConfirmButton: false,
            timer: 2000,
            customClass: {
                popup: 'my-swal-popup',
                content: 'my-swal-content',
                actions: 'my-swal-actions'
            }
        });
        <?php elseif(!empty($error_message)): ?>
        Swal.fire({
            title: "Error!",
            text: "<?php echo $error_message; ?>",
            icon: "error",
            toast: true,
            position: "center",
            showConfirmButton: false,
            timer: 2000,
            customClass: {
                popup: 'my-swal-popup',
                content: 'my-swal-content',
                actions: 'my-swal-actions'
            }
        });
        <?php endif; ?>
    </script>

    <style>
        /* Custom CSS for SweetAlert */
        .my-swal-popup {
            width: 400px; /* Adjust width as needed */
            height: auto; /* Adjust height as needed */
            padding: 300px; /* Adjust padding as needed */
        }
        .my-swal-content {
            /* Additional styling for content if necessary */
        }
        .my-swal-actions {
            /* Additional styling for actions if necessary */
        }
    </style>

    </body>
    </html>
