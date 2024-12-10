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

    // Verify reCAPTCHA v3
    if (empty($email_err) && empty($password_err)) {
        $recaptcha_secret = '6LfCwZYqAAAAAEbhh9M53gxnfqgwP2-Rkg7rnD5j'; // Replace with your reCAPTCHA v3 secret key
        $recaptcha_response = $_POST['recaptcha_response'];

        // Verify the reCAPTCHA response
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = [
            'secret' => $recaptcha_secret,
            'response' => $recaptcha_response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $response_keys = json_decode($response, true);

        // Check reCAPTCHA score (default threshold is 0.5)
        if (!$response_keys['success'] || $response_keys['score'] < 0.5) {
            $login_err = "CAPTCHA verification failed. Please try again.";
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
    <title>Accounting Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
    <link rel="icon" href="logo.png" type="image/icon type">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LfCwZYqAAAAAJ8wBxWCzCwsgeFpTdSYTagAmnwL"></script>
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
        .form-outline {
            position: relative;
        }
        .form-outline .fa-eye, .form-outline .fa-eye-slash {
            position: absolute;
            right: 20px;
            top: 45px;
            cursor: pointer;
            margin-top: 10px;
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
            margin-left: 50px; /* Adjust this value to move the form further left */
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
<body class="bg-light">

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

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <p class="text-center mb-3">
                            <img src="logo.png" alt="Admin-Icon" style="width: 250px; height: 200px;">
                        </p>   
                    <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">
                                <img src="accountant.png" alt="Accountant Icon" style="width: 55px; height: 55px;">
                        </p>
                        <!-- Username input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form1Example13"> <i class="bi bi-person-circle"></i>  <strong>Username</strong></label>
                            <input type="text" id="form1Example13" class="form-control form-control-lg py-3 <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>" name="username" autocomplete="off" placeholder="Enter username" style="border-radius:25px ;" >
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form1Example23"><i class="bi bi-chat-left-dots-fill"></i> <strong>Password</strong></label>
                            <input type="password" id="password" class="form-control form-control-lg py-3 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" name="password" autocomplete="off" placeholder="Enter your password" style="border-radius:25px ;">
                            <i class="fa fa-eye-slash" id="togglePassword"></i>
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>

                        <!-- reCAPTCHA -->
                        <div class="g-recaptcha" data-sitekey="6LeNVYIqAAAAAD8moza5cF_4G7YsCSUZjy4ZMzZi"></div>
                        <span class="invalid-feedback"><?php echo $captcha_err; ?></span>
                        <br>

                        <!-- Login button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Login</button>
                        </div>
                    </form>

                    <br>
                    <p>Don't have an account? <a href="signup.php" class="text-primary">Sign up</a></p>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("login_password");
            var toggleIcon = document.getElementById("toggle-icon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.replace("fa-eye-slash", "fa-eye");
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
