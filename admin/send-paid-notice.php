<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);
    
    $sql = "SELECT *, (present - previous) as used, readings.status as reading_status FROM readings LEFT JOIN consumers ON consumers.id = readings.consumer_id WHERE readings.id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $id;
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                if (isset($row['email']) && !empty($row['email'])) {
                    $to_email = $row['email'];
                    $consumer_id = $row['consumer_id'];
                    $consumer_name = $row['name'];
                    $subject = "MACWAS Official Receipt";

                    // Start output buffering to get the email body from the template
                    ob_start();
                    include("templates/paid-notice.php");
                    $message = ob_get_contents();
                    ob_end_clean();

                    // Create a new PHPMailer instance
                    $mail = new PHPMailer();
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.yourserver.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'your-email@yourdomain.com';
                    $mail->Password = 'your-email-password';
                    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587; // TCP port to connect to

                    // Sender and recipient details
                    $mail->setFrom('your-email@yourdomain.com', 'MACWAS');
                    $mail->addAddress($to_email, $consumer_name);

                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $message;

                    // Send email
                    if ($mail->send()) {
                        include('consumer.php');
                        echo '<script>
                        Swal.fire({
                        title: "Success!",
                        text: "Records updated successfully.",
                        icon: "success",
                        toast: true,
                        position: "top-right",
                        showConfirmButton: false,
                        timer: 3000
                        })
                        </script>';
                        exit();
                    } else {
                        echo '<script>
                        Swal.fire({
                        title: "Error!",
                        text: "There was an error: ' . $mail->ErrorInfo . '",
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
                
            } else {
                header('location: reading.php?consumer_id=' . $consumer_id);
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
    mysqli_stmt_close($stmt);
} else {
    header('location: reading.php?consumer_id=' . $consumer_id);
    exit();
}
?>
