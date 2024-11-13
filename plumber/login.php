<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect them to the welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Google reCAPTCHA secret key
$secret_key = "6Lc5Dn0qAAAAAMYzPsoS20eZ8vEIEzZPE9olVTrN";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if reCAPTCHA is valid
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];

        // Verify CAPTCHA with Google
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha");
        $response_keys = json_decode($response, true);

        // If CAPTCHA is valid
        if(intval($response_keys["success"]) !== 1) {
            $login_err = "Please verify that you are not a robot.";
        }
    } else {
        $login_err = "Please verify that you are not a robot.";
    }
    
    // Check if username and password are empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err) && empty($login_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, name FROM plumbers WHERE username = ?";

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
    <title>PLumber</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="icon" href="logo.png" type="image/icon type">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            background-image: url("plumber.jpg");
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
            backdrop-filter: blur(5px); /* Optional: Adds a blur effect to the background of the card */
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
                                <img src="plumber.png" alt="plumber Icon" style="width: 55px; height: 55px;">
                        </p>
                        <!-- Username input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form1Example13"> <i class="bi bi-person-circle"></i><strong> Username</strong></label>
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

                        <!-- Google reCAPTCHA -->
                        <div class="form-outline mb-4">
                            <div class="g-recaptcha" data-sitekey="6LdzD30qAAAAAGGUZtHHljbEuOozmOKjwgjBJWrw"></div>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-lg" style="width: 200px; height: 50px;">Login</button>
                        </div>

                        <!-- Register buttons -->
                        <div class="text-center mt-3">
                            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    // Password visibility toggle
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", function (e) {
        // Toggle the type attribute
        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);
        // Toggle the eye icon
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
</script>

</body>
</html>
