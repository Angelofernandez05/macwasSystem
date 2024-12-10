<?php
// Initialize the session
session_start();

// Include config file
require_once 'config.php';

// Define constants
define('MAX_ATTEMPTS', 3); // Max login attempts
define('LOCKOUT_TIME', 300); // Lockout time in seconds (5 minutes)

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Initialize session variables for login attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = 0;
}

// Check lockout status
if (time() < $_SESSION['lockout_time']) {
    $login_err = "Too many failed login attempts. Please try again after " . 
                 ceil(($_SESSION['lockout_time'] - time()) / 60) . " minutes.";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    if (empty($login_err)) { // Proceed only if not locked out
        // Validate email
        if (empty(trim($_POST["email"]))) {
            $email_err = "Please enter your email.";
        } else {
            $email = trim($_POST["email"]);
        }

        // Validate password
        if (empty(trim($_POST["password"]))) {
            $password_err = "Please enter your password.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Proceed if no validation errors
        if (empty($email_err) && empty($password_err)) {
            // Prepare a select statement
            $sql = "SELECT id, status, password, is_approved FROM consumers WHERE email = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                $param_email = $email;

                // Execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);

                    // Check if email exists, then verify password
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $id, $status, $hashed_password, $is_approved);
                        if (mysqli_stmt_fetch($stmt)) {
                            if (password_verify($password, $hashed_password)) {
                                // Reset login attempts on successful login
                                $_SESSION['login_attempts'] = 0;
                                $_SESSION['lockout_time'] = 0;

                                // Regenerate session ID for security
                                session_regenerate_id();

                                // Set session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;

                                // Redirect user to the dashboard
                                header("location: index.php");
                                exit;
                            } else {
                                // Increment login attempts
                                $_SESSION['login_attempts']++;
                                $remaining_attempts = MAX_ATTEMPTS - $_SESSION['login_attempts'];
                                if ($_SESSION['login_attempts'] >= MAX_ATTEMPTS) {
                                    $_SESSION['lockout_time'] = time() + LOCKOUT_TIME;
                                    $login_err = "Too many failed login attempts. Please try again after 5 minutes.";
                                } else {
                                    $login_err = "Invalid password. You have $remaining_attempts attempts left.";
                                }
                            }
                        }
                    } else {
                        $login_err = "Invalid email or password.";
                    }
                } else {
                    $login_err = "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
    mysqli_close($link);
}

// Security headers
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
                            <!--<div class="g-recaptcha mb-3" data-sitekey="6LeNVYIqAAAAAD8moza5cF_4G7YsCSUZjy4ZMzZi"></div>-->

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
        <script src="https://www.google.com/recaptcha/api.js?render=6LfCwZYqAAAAAJ8wBxWCzCwsgeFpTdSYTagAmnwL"></script>

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
        <script>
        grecaptcha.ready(function() {
        grecaptcha.execute('6LfCwZYqAAAAAJ8wBxWCzCwsgeFpTdSYTagAmnwL', { action: 'login' }).then(function(token) {
            const recaptchaResponseField = document.createElement('input');
            recaptchaResponseField.setAttribute('type', 'hidden');
            recaptchaResponseField.setAttribute('name', 'recaptcha_response');
            recaptchaResponseField.setAttribute('value', token);
            document.querySelector('form').appendChild(recaptchaResponseField);
        });
    });
</script>

    </body>
    </html>
