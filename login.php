    <?php
    // Initialize the session
    session_start();

    // Include config file
    require_once 'config.php';

    // Define variables and initialize with empty values
    $email = $password = "";
    $email_err = $password_err = $login_err = "";

    // Process form submission
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

        // Verify reCAPTCHA
        if (empty($email_err) && empty($password_err)) {
            $recaptcha_secret = '6LeNVYIqAAAAAFKB4J4PHK5M3GDRb0mjkHlpxe4Y';
            $recaptcha_response = $_POST['g-recaptcha-response'];
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
            $response_keys = json_decode($response, true);

            if (intval($response_keys["success"]) !== 1) {
                $login_err = "Please complete the CAPTCHA verification.";
            } else {
                // Prepare a select statement
                $sql = "SELECT id, status, password, is_approved FROM consumers WHERE email = ?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $param_email);
                    $param_email = $email;

                    // Execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);

                        // Check if email exists, if yes then verify password
                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            mysqli_stmt_bind_result($stmt, $id, $status, $hashed_password, $is_approved);
                            if (mysqli_stmt_fetch($stmt)) {
                                if (password_verify($password, $hashed_password)) {
                                    if ($is_approved == 0) {
                                        $login_err = "Your account is awaiting approval. Please contact the system administrator.";
                                    } elseif ($status === 'inactive') {
                                        $login_err = "Your account is inactive. Please contact the system administrator.";
                                    } else {
                                        // Regenerate session ID for security
                                        session_regenerate_id();

                                        // Set session variables
                                        $_SESSION["loggedin"] = true;
                                        $_SESSION["id"] = $id;
                                        $_SESSION["email"] = $email;

                                        // Redirect user to the dashboard
                                        header("location: index.php");
                                        exit;
                                    }
                                } else {
                                    $login_err = "Invalid email or password.";
                                }
                            }
                        } else {
                            $login_err = "Invalid email or password.";
                        }
                    } else {
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
                    mysqli_stmt_close($stmt);
                }
            }
        }
        mysqli_close($link);
    }

    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Permissions-Policy: geolocation=(self), microphone=()");

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Consumer Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style2.css">
        <link rel="icon" href="logo.png" type="image/icon type">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <style>
            body {
                background-image: url("tank.jpg");
                background-repeat: no-repeat;
                background-position: center;
                background-attachment: fixed;
                background-size: cover;
                font-family: 'Georgia', serif;
            }
            .card {
                border-radius: 25px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                background-color: rgba(173, 216, 230, 0.2);
                padding: 20px;
                backdrop-filter: blur(3px);
            }
            .container {
                max-width: 550px;
                margin-left: auto;
                margin-right: auto;
            }
            .form-control {
                border-radius: 20px;
            }
            .btn {
                border-radius: 30px;
                font-weight: 600;
            }
            .recaptcha-container {
            display: flex;
            justify-content: center;
            align-items: center;
            }
            .g-recaptcha {
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <section class="vh-100 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="card">
                    <div class="card-body text-center">
                        <?php 
                        if (!empty($login_err)) {
                            echo '<script>
                            Swal.fire({
                                title: "Error!",
                                text: "' . htmlspecialchars($login_err) . '",
                                icon: "error",
                                toast: true,
                                position: "top-right",
                                showConfirmButton: false,
                                timer: 3000
                            });
                            </script>';
                        }        
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <p class="text-center mb-4">
                                <img src="logo.png" alt="Logo" style="max-width: 200px; height: auto;">
                            </p>   
                            <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">
                                <img src="users.png" alt="User Icon" style="width: 60px; height: 60px;">
                            </p>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="login_email"><strong>Email</strong></label>
                                <input type="email" id="login_email" class="form-control py-3 <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>" name="email" autocomplete="off" placeholder="Enter your email" required>
                                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="login_password"><strong>Password</strong></label>
                                <div class="input-group">
                                    <input type="password" id="login_password" class="form-control py-3 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" name="password" autocomplete="off" placeholder="Enter your password" required>
                                    <span class="input-group-text" onclick="togglePasswordVisibility()">
                                        <i class="fas fa-eye" id="toggle-icon"></i>
                                    </span>
                                </div>
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>

                            <!-- Add reCAPTCHA widget -->
                            <div class="g-recaptcha mb-3" data-sitekey="6LeNVYIqAAAAAD8moza5cF_4G7YsCSUZjy4ZMzZi"></div>

                            <div class="d-grid mb-3">
                                <input type="submit" value="Login" name="login" class="btn btn-primary text-light py-3">
                            </div>
                        </form>
                        <p class="text-center"><strong>Don't have an account? <a href="signup.php" class="text-primary">Sign up here</a></strong></p>
                        <p class="text-center"><strong>Forgot your password? <a href="forgot_password.php" class="text-primary">Click here</a></strong></p>
                    </div>
                </div>
            </div>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>
        <script>
            // Toggle password visibility
            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('login_password');
                const toggleIcon = document.getElementById('toggle-icon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye'); 
                }
            }
        </script>
    </body>
    </html>
