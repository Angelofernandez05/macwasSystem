    <?php
    // Initialize the session
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login");
        exit;
    }

    // Include config file
    require_once "config";

    // Define variables and initialize with empty values
    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {
            // Validate new password
            if (empty(trim($_POST["new_password"]))) {
                $new_password_err = "Please enter the new password.";
            } elseif (strlen(trim($_POST["new_password"])) < 6) {
                $new_password_err = "Password must have at least 6 characters.";
            } else {
                $new_password = trim($_POST["new_password"]);
            }

            // Validate confirm password
            if (empty(trim($_POST["confirm_password"]))) {
                $confirm_password_err = "Please confirm the password.";
            } else {
                $confirm_password = trim($_POST["confirm_password"]);
                if (empty($new_password_err) && ($new_password != $confirm_password)) {
                    $confirm_password_err = "Password did not match.";
                }
            }

            // Check input errors before updating the database
            if (empty($new_password_err) && empty($confirm_password_err)) {
                // Prepare an update statement
                $sql = "UPDATE consumers SET password = ?, isUpdated = ? WHERE id = ?";

                if ($stmt = $link->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("ssi", $param_password, $param_isUpdated, $param_id);

                    // Set parameters
                    $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $param_isUpdated = 1;
                    $param_id = $_SESSION["id"];

                    // Attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        // Password updated successfully. Show success message and redirect to login page
                        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                        echo '<script>
                        Swal.fire({
                            title: "Success!",
                            text: "Your password has been reset successfully. You will be redirected to the login page.",
                            icon: "success",
                            toast: true,
                            position: "top-right",
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            window.location.href = "login";
                        });
                        </script>';
                    } else {
                        // Show error message
                        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
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
                    $stmt->close();
                }
            }

            // Close connection
            $link->close();
        } elseif (isset($_POST['cancel'])) {
            // Redirect to index page on cancel
            header("location: index");
            exit;
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Reset Password</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
        <link rel="icon" href="logo.png" type="image/icon type">
        <style>
             body {
            background-image: url("tank.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;
            font-size: 18px;
        }
        .card {
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: rgba(173, 216, 230, 0.2);
            padding: 20px;
            backdrop-filter: blur(3px);
        }
            .form-outline {
                position: relative;
            }
            .toggle-password {
                position: absolute;
                right: 15px;
                top: 45px;
                cursor: pointer;
            }
        </style>
    </head>
    <body class="bg-light">
        <section class="vh-100" style="background-color:;">
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
                                            <div class="d-flex flex-row align-items-center mb-4 mt-5">
                                                <div class="form-outline flex-fill mb-0">
                                                    <label class="form-label" for="new_password"><i class="bi bi-chat-left-dots-fill"></i> New Password</label>
                                                    <input type="password" id="new_password" class="form-control form-control-lg py-3" name="new_password" autocomplete="off" placeholder="Enter your password" style="border-radius:25px;" />
                                                    <i class="bi bi-eye-slash toggle-password" id="toggleNewPassword"></i>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <div class="form-outline flex-fill mb-0">
                                                    <label class="form-label" for="confirm_password"><i class="bi bi-chat-left-dots-fill"></i> Confirm Password</label>
                                                    <input type="password" id="confirm_password" class="form-control form-control-lg py-3" name="confirm_password" autocomplete="off" placeholder="Enter your password" style="border-radius:25px;" />
                                                    <i class="bi bi-eye-slash toggle-password" id="toggleConfirmPassword"></i>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                                <input type="submit" value="Submit" name="submit" class="btn btn-primary btn-lg text-light my-2 py-3" style="width:100%; border-radius: 10px; font-weight:600;" />
                                                <input type="submit" value="Cancel" name="cancel" class="btn btn-secondary btn-lg text-light my-2 py-3" style="width:100%; border-radius: 10px; font-weight:600; margin-left: 10px;" />
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
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.getElementById('toggleNewPassword').addEventListener('click', function () {
                const newPasswordField = document.getElementById('new_password');
                const icon = this;
                if (newPasswordField.type === 'password') {
                    newPasswordField.type = 'text';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    newPasswordField.type = 'password';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });

            document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
                const confirmPasswordField = document.getElementById('confirm_password');
                const icon = this;
                if (confirmPasswordField.type === 'password') {
                    confirmPasswordField.type = 'text';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    confirmPasswordField.type = 'password';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });
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
