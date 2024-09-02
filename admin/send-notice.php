<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

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
                    $subject = "MACWAS Notice of Disconnection";

                    ob_start();
                    include("templates/notice.php");
                    $message = ob_get_contents();
                    ob_end_clean();

                    $mail = new PHPMailer(true);
                    try {
                        //Server settings
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.example.com'; // Set the SMTP server to send through
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'your_email@example.com'; // SMTP username
                        $mail->Password   = 'your_password'; // SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;

                        //Recipients
                        $mail->setFrom('your_email@example.com', 'MACWAS');
                        $mail->addAddress($to_email);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = $subject;
                        $mail->Body    = $message;

                        $mail->send();
                        echo '<script>
                        Swal.fire({
                        title: "Success!",
                        text: "Email sent successfully.",
                        icon: "success",
                        toast: true,
                        position: "top-right",
                        showConfirmButton: false,
                        timer: 3000
                        })
                        </script>';
                    } catch (Exception $e) {
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
                    }
                } else {
                    echo '<script>
                    Swal.fire({
                    title: "Error!",
                    text: "No email address found for the consumer.",
                    icon: "error",
                    toast: true,
                    position: "top-right",
                    showConfirmButton: false,
                    timer: 3000
                    })
                    </script>';
                }
            } else {
                header("location: reading.php?consumer_id=$consumer_id");
                exit();
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
            })
            </script>';
        }

        mysqli_stmt_close($stmt);
    }
} else {
    header("location: consumer.php");
    exit();
}
?>
