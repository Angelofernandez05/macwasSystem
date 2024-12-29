<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Define variables and initialize with empty values
$name = $barangay = $account_num = $registration_num = $meter_num = $type = $email = $phone = "";
$name_err = $barangay_err = $account_num_err = $registration_num_err = $meter_num_err = $type_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    // Validate barangay
    $input_barangay = trim($_POST["barangay"]);
    if(empty($input_barangay)){
        $barangay_err = "Please enter an barangay.";
    } else{
        $barangay = $input_barangay;
    }

    // Validate account_num
    $input_account_num = trim($_POST["account_num"]);
    if(empty($input_account_num)){
        $account_num_err = "Please enter an account_num.";
    } else{
        $account_num = $input_account_num;
    }

    // Validate registration_num
    $input_registration_num = trim($_POST["registration_num"]);
    if(empty($input_registration_num)){
        $registration_num_err = "Please enter an registration_num.";
    } else{
        $registration_num = $input_registration_num;
    }

    // Validate meter_num
    $input_meter_num = trim($_POST["meter_num"]);
    if(empty($input_meter_num)){
        $meter_num_err = "Please enter an meter_num.";
    } else{
        $meter_num = $input_meter_num;
    }

    // Validate type
    $input_type = trim($_POST["type"]);
    if(empty($input_type)){
        $type_err = "Please enter an type.";
    } else{
        $type = $input_type;
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($barangay_err) && empty($account_num_err) && empty($registration_num_err) && empty($meter_num_err) && empty($type_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO consumers (name, barangay, account_num, registration_num, meter_num, type, status, email, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssss", $param_name, $param_barangay, $param_account_num, $param_registration_num, $param_meter_num, $param_type, $param_status, $param_email, $param_phone, $param_password);

            // Set parameters
            $param_name = $name;
            $param_barangay = $barangay;
            $param_account_num = $account_num;
            $param_registration_num = $registration_num;
            $param_meter_num = $meter_num;
            $param_type = $type;
            $param_status = 1;
            $param_email = trim($_POST["email"]);
            $param_phone = trim($_POST["phone"]);
            $param_password = password_hash($meter_num, PASSWORD_DEFAULT); // Creates a password hash
            $param_password = trim($_POST["password"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                //send email
                if(!empty($param_email)){
                    $mail = new PHPMailer(true);

                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'your-email@gmail.com'; // Your Gmail email address
                        $mail->Password = 'your-gmail-app-password'; // Your Gmail app password
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        // Sender and recipient settings
                        $mail->setFrom('your-email@gmail.com', 'MACWAS');
                        $mail->addAddress($param_email, $param_name); // Add a recipient

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'MACWAS Registration';
                        $mail->Body    = "Hi $param_name, Thank you for signing up to MACWAS Online Billing.
                                          This is your temporary username and password: $meter_num 

                                          To verify your account, please login through the link below:
                                          <a href='https://localhost/macwas/login.php'>https://localhost/macwas/login.php</a>

                                          REMINDER:
                                          You can only verify your account by using the link above
                                          For the security of the account, you are required to change
                                          your password according to your preference

                                          Thank you for trusting MACWAS";

                        // Send email
                        $mail->send();

                        // Success message
                        include('consumer.php');
                        echo '<script>
                        Swal.fire({
                            title: "Success!",
                            text: "Email verification successfully sent.",
                            icon: "success",
                            toast: true,
                            position: "top-right",
                            showConfirmButton: false,
                            timer: 3000
                        })
                        </script>';
                        exit();
                    } catch (Exception $e) {
                        // Error message
                        include('consumer.php');
                        echo '<script>
                        Swal.fire({
                            title: "Error!",
                            text: "Email verification not sent. ' . $mail->ErrorInfo . '",
                            icon: "error",
                            toast: true,
                            position: "top-right",
                            showConfirmButton: false,
                            timer: 3000
                        })
                        </script>';
                        exit();
                    }
                }else{
                    // echo `<div class="alert alert-danger" role="alert">Email parameter is empty!</div>`;
                    include('consumer.php');
                    echo '<script>
                    Swal.fire({
                        title: "Error!",
                        text: "Email parameter is empty!",
                        icon: "error",
                        toast: true,
                        position: "top-right",
                        showConfirmButton: false,
                        timer: 3000
                    })
                    </script>';
                    exit();
                }
            } else{
                include('consumer.php');
                // echo `<div class="alert alert-danger" role="alert">Oops! Something went wrong. Please try again later.</div>`;
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
                exit();
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>
<style>
    .navbar-light-gradient {
                background: linear-gradient(135deg, #36d1dc, #5b86e5);
                color: white;
                border-bottom: 2px solid black !important;
                margin-left: 10px;
            }
    </style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin New Consumer</title>
    <?php include 'includes/links.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='color: black; ursor: pointer; font-size: 2rem'></i>
                New Consumer
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container py-5">
            <div class="w-100 m-auto" style="max-width: 500px">
                <?php include 'forms/consumer-form.php'; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
</body>
</html>
