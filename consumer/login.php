<?php
// Initialize the session
session_start();

// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, status, password, is_approved FROM consumers WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $status, $hashed_password, $is_approved);
                    if (mysqli_stmt_fetch($stmt)) {
                        // Debugging output
                        error_log("Hashed Password: $hashed_password");
                        error_log("Entered Password: $password");

                        if (password_verify($password, $hashed_password)) {
                            if ($is_approved == 0) {
                                $login_err = "Your account is awaiting approval. Please contact the system administrator.";
                            } elseif ($status === 'inactive') {
                                $login_err = "Your account is inactive. Please contact the system administrator.";
                            } else {
                                // Password is correct, so start a new session
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;

                                // Redirect user to the dashboard or welcome page
                                header("location: index.php");
                                exit;
                            }
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else {
                    // Email doesn't exist, display a generic error message
                    $login_err = "Invalid email or password.";
                }
            } else {
                // Error executing statement
                error_log('Error executing statement.');
                echo '<script>
                Swal.fire({
                    title: "Error!",
                    text: "Oops! Something went wrong. Please try again later.",
                    icon: "error",
                    toast: true,
                    position: "top-right",
                    showConfirmButton: false,
                    timer: 3000
                });
                </script>';
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            error_log('Error preparing statement.');
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="icon" href="logo.png" type="image/icon type">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('login_password');
            const toggleIcon = document.getElementById('toggle-icon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
    <style>
         body {
<<<<<<< HEAD
            background-image: url("image/tank.jpg");
=======
            background-image: url("tank.jpg");
>>>>>>> 99d1b02 (commit)
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;
        }
        .input-group {
            position: relative;
        }

        .input-group .form-control {
            padding-right: 2.5rem;
        }

        .input-group .input-group-text {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 10;
            margin-right: 20px;
        }

        .input-group .input-group-text i {
            color: #000;
            font-size: 1.2rem;
        }
        .card {
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: rgba(173, 216, 230, 0.0); /* Light blue with some transparency */
            padding: 20px; /* Add padding for content inside the card */
            backdrop-filter: blur(3px); /* Optional: Adds a blur effect to the background of the card */
        }

        .card-body {
            padding: 1rem;
        }

        .container {
            max-width: 550px;
            margin-left: 30px; /* Adjust this value to move the form further left */
        }

        .form-control {
            border-radius: 20px;
        }

        .btn {
            border-radius: 30px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <section class="vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="card">
                <div class="card-body text-center">
                    <?php 
                    if(!empty($login_err)){
                        echo '<script>
                        Swal.fire({
                        title: "Error!",
                        text: "' . $login_err . '",
                        icon: "error",
                        toast: true,
                        position: "top-right",
                        showConfirmButton: false,
                        timer: 3000
                        })
                        </script>';
                    }        
                    ?>
                    <!-- Login Form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <p class="text-center mb-4">
<<<<<<< HEAD
                            <img src="image/logo.png" alt="Admin-Icon" style="width: 200px; height: 100px;">
                        </p>   
                    <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">
                                <img src="image/users.png" alt="Accountant Icon" style="width: 60px; height: 60px;">
=======
                            <img src="logo.png" alt="Admin-Icon" style="width: 200px; height: 100px;">
                        </p>   
                    <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">
                                <img src="users.png" alt="Accountant Icon" style="width: 60px; height: 60px;">
>>>>>>> 99d1b02 (commit)
                        </p>
                        <!-- <p class="text-center">Please fill in your credentials to login.</p> -->

                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="login_email"><i class="bi bi-person-circle"></i><strong> Email</strong></label>
                            <input type="email" id="login_email" class="form-control form-control-lg py-3 <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" name="email" autocomplete="off" placeholder="Enter your email" style="border-radius:25px;">
                            <span class="invalid-feedback"><?php echo $email_err; ?></span>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="login_password"><i class="bi bi-chat-left-dots-fill"></i><strong>Password</strong></label>
                            <div class="input-group">
                                <input type="password" id="login_password" class="form-control form-control-lg py-3 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" name="password" autocomplete="off" placeholder="Enter your password" style="border-radius:25px;">
                                <span class="input-group-text" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggle-icon"></i>
                                </span>
                            </div>
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                            <input type="submit" value="Login" name="login" class="btn btn-primary btn-lg text-light my-2 py-3" style="width:100%; border-radius: 30px; font-weight:600;">
                        </div>
                    </form>
                    <p align="center"><strong>Don't have an account? Sign up</strong><a href="signup.php" class="text-primary" style="font-weight:600;text-decoration:none;"> here</a></p>
                    <p align="center"><strong>Forgot your password?</strong> <a href="forgot_password.php" class="text-primary" style="font-weight:600;text-decoration:none;">Click here</a></p>

                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
</body>
</html>
