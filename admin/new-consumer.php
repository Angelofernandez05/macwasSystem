<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $name_err = "Please enter a valid name.";
    } else {
        $name = $input_name;
    }

    // Validate barangay
    $input_barangay = trim($_POST["barangay"]);
    if (empty($input_barangay)) {
        $barangay_err = "Please enter an barangay.";
    } else {
        $barangay = $input_barangay;
    }

    // Validate account_num
    $input_account_num = trim($_POST["account_num"]);
    if (empty($input_account_num)) {
        $account_num_err = "Please enter an account_num.";
    } else {
        $account_num = $input_account_num;
    }

    // Validate registration_num
    $input_registration_num = trim($_POST["registration_num"]);
    if (empty($input_registration_num)) {
        $registration_num_err = "Please enter an registration_num.";
    } else {
        $registration_num = $input_registration_num;
    }

    // Validate meter_num
    $input_meter_num = trim($_POST["meter_num"]);
    if (empty($input_meter_num)) {
        $meter_num_err = "Please enter an meter_num.";
    } else {
        $meter_num = $input_meter_num;
    }

    // Validate type
    $input_type = trim($_POST["type"]);
    if (empty($input_type)) {
        $type_err = "Please enter an type.";
    } else {
        $type = $input_type;
    }

    // Generate a random password
    $temp_password = bin2hex(random_bytes(4)); // Generate an 8-character random password
    $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT); // Hash the password

    // Check input errors before inserting in database
    if (empty($name_err) && empty($barangay_err) && empty($account_num_err) && empty($registration_num_err) && empty($meter_num_err) && empty($type_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO consumers (name, barangay, account_num, registration_num, meter_num, type, status, email, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssss", $name, $barangay, $account_num, $registration_num, $meter_num, $type, $status, $email, $phone, $hashed_password);

            // Set parameters
            $status = 1;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Send email with the temporary password
                if (!empty($email)) {
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
                        $mail->addAddress($email, $name); // Add a recipient

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'MACWAS Registration';
                        $mail->Body = "Hi $name,<br><br>
                        Thank you for signing up for MACWAS Online Billing.<br><br>
                        Your temporary login credentials are:<br>
                        <b>Username:</b> $email<br>
                        <b>Password:</b> $temp_password<br><br>
                        Please <a href='https://localhost/macwas/login.php'>click here to log in</a> and change your password for security.<br><br>
                        Thank you for trusting MACWAS.";

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
                } else {
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
            } else {
                include('consumer.php');
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
                <i class='bx bx-menu mr-3' style='color: black; cursor: pointer; font-size: 2rem'></i>
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
