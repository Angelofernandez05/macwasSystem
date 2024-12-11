<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// Include config file
require_once "config.php";

// // Define variables and initialize with empty values
// $username = $password = "";
// $username_err = $password_err = $login_err = $captcha_err = "";

// // Google reCAPTCHA secret key
// $secret_key = '6LeNVYIqAAAAAFKB4J4PHK5M3GDRb0mjkHlpxe4Y';

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // // Check if reCAPTCHA is checked
    // if(isset($_POST['g-recaptcha-response'])){
    //     $recaptcha_response = $_POST['g-recaptcha-response'];
    //     $verify_recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$recaptcha_response");
    //     $recaptcha_response_keys = json_decode($verify_recaptcha);

    //     // If reCAPTCHA is not successful
    //     if(intval($recaptcha_response_keys->success) !== 1) {
    //         $captcha_err = "Please verify that you are not a robot.";
    //     }
    // } else {
    //     $captcha_err = "Please verify that you are not a robot.";
    // }

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err) && empty($captcha_err)){
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
        // Prepare a select statement
        $sql = "SELECT id, username, password, name FROM accounting WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $name);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["name"] = $name; // Store the user's name in session
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
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
    <title>Accounting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="icon" href="logo.png" type="image/icon type">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
    <script src="https://www.google.com/recaptcha/api.js?render=6LfCwZYqAAAAAJ8wBxWCzCwsgeFpTdSYTagAmnwL"></script>

<script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", function (e) {
        // Toggle the type attribute
        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);

        // Toggle the eye slash icon
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
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