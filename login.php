<?php
// Initialize the session
session_start();

// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$meter_num = $password = "";
$meter_num_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    // Check if meter_num is empty
    if (empty(trim($_POST["meter_num"]))) {
        $meter_num_err = "Please enter meter number.";
    } else {
        $meter_num = trim($_POST["meter_num"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($meter_num_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, status, password, isUpdated FROM consumers WHERE meter_num = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_meter_num);

            // Set parameters
            $param_meter_num = $meter_num;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if meter_num exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $status, $hashed_password, $isUpdated);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            if ($status === 0) {
                                $login_err = "Invalid account. Please contact the system administrator.";
                            } else {
                                // Password is correct, so start a new session
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["meter_num"] = $meter_num;
                                $_SESSION["isUpdated"] = $isUpdated;

                                // Redirect user to welcome page
                                header("location: index.php");
                                exit;
                            }
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid meter number or password.";
                        }
                    }
                } else {
                    // meter_num doesn't exist, display a generic error message
                    $login_err = "Invalid meter number or password.";
                }
            } else {
                // Error executing statement
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="icon" href="logo.png" type="image/icon type">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script> <!-- Font Awesome -->
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
        .input-group {
            position: relative;
        }

        .input-group .form-control {
            padding-right: 2.5rem; /* Adjust this value if needed */
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
    </style>
</head>
<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="logo2.jpg" class="img-fluid" alt="Logo" height="300px" width="600px">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <?php
                    if (!empty($login_err)) {
                        echo '<script>
                        Swal.fire({
                            title: "Error!",
                            text: "' . $login_err . '",
                            icon: "error",
                            toast: true,
                            position: "top-right",
                            showConfirmButton: false,
                            timer: 3000
                        });
                        </script>';
                    }
                    ?>
                    <!-- Login Form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">User Login</p>
                        <p class="text-center">Please fill in your credentials to login.</p>

                        <!-- Meter Number input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="login_meter_num"><i class="bi bi-person-circle"></i> Meter No.</label>
                            <input type="text" id="login_meter_num" class="form-control form-control-lg py-3 <?php echo (!empty($meter_num_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $meter_num; ?>" name="meter_num" autocomplete="off" placeholder="Enter meter no." style="border-radius:25px;">
                            <span class="invalid-feedback"><?php echo $meter_num_err; ?></span>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="login_password"><i class="bi bi-chat-left-dots-fill"></i> Password</label>
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
                    <p align="center">Don't have an account? Sign up<a href="signup.php" class="text-primary" style="font-weight:600;text-decoration:none;"> here</a></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
</body>
</html>


