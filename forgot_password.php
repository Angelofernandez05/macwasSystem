<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$email = "";
$email_err = $msg = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate email
    if (empty($email_err)) {
        // Prepare a select statement
        $sql = "SELECT id FROM consumers WHERE email = ?";

        // Initialize statement
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                // Check if email exists
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Generate a unique token
                    $token = bin2hex(random_bytes(50));
                    $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

                    // Insert the token and expiry into the database
                    $sql = "INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)";

                    // Initialize statement
                    if ($stmt2 = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt2, "sss", $param_email, $token, $expiry);

                        if (mysqli_stmt_execute($stmt2)) {
                            // Send reset email
                            $reset_link = "http://yourdomain.com/reset_password.php?token=" . $token;

                            $subject = "Password Reset Request";
                            $message = "Hi,\n\nPlease click the following link to reset your password: \n" . $reset_link;
                            $headers = "From: no-reply@yourdomain.com";

                            if (mail($email, $subject, $message, $headers)) {
                                $msg = "An email with instructions to reset your password has been sent.";
                            } else {
                                $msg = "Failed to send reset email. Please try again.";
                            }
                        } else {
                            $msg = "Error executing statement: " . mysqli_error($link);
                        }
                        // Close the second statement
                        mysqli_stmt_close($stmt2);
                    } else {
                        $msg = "Error preparing statement: " . mysqli_error($link);
                    }
                } else {
                    $email_err = "No account found with that email address.";
                }
                // Close the first statement
                mysqli_stmt_close($stmt);
            } else {
                $msg = "Error executing statement: " . mysqli_stmt_error($stmt);
            }
        } else {
            $msg = "Error preparing statement: " . mysqli_error($link);
        }
    }

    // Close the connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="icon" href="logo.png" type="image/icon type">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-image: url("tank.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;
        }

        .card {
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: rgba(173, 216, 230, 0.2);
            padding: 20px;
            backdrop-filter: blur(3px);
        }

        .container {
            max-width: 490px;
            margin-top: 70px;
            margin-left: 50px;
        }

        .form-control {
            border-radius: 25px;
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
                    if (!empty($msg)) {
                        echo '<script>
                        Swal.fire({
                            title: "Info",
                            text: "' . $msg . '",
                            icon: "info",
                            toast: true,
                            position: "top-right",
                            showConfirmButton: false,
                            timer: 3000
                        })
                        </script>';
                    }        
                    ?>
                    <!-- Forgot Password Form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <p class="text-center mb-4">
                            <img src="logo.png" alt="Logo" style="width: 200px; height: 100px;">
                        </p>
                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="email"><i class="bi bi-envelope"></i><strong> Email</strong></label>
                            <input type="email" id="email" class="form-control form-control-lg py-3 <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" name="email" value="<?php echo $email; ?>" placeholder="Enter your email">
                            <span class="invalid-feedback"><?php echo $email_err; ?></span>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                            <input type="submit" value="Request Reset Link" class="btn btn-primary btn-lg text-light my-2 py-3" style="width:100%; border-radius: 30px; font-weight:600;">
                        </div>
                    </form>
                    <p align="center"><strong>Remembered your password?</strong> <a href="login.php" class="text-primary" style="font-weight:600;text-decoration:none;">Login here</a></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
</body>
</html>
