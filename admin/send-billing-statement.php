<?php
// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Include config file
require_once "config.php";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Get URL parameter
    $id = trim($_GET["id"]);

    $sql = "SELECT *, (present - previous) as used, readings.status as reading_status FROM readings LEFT JOIN consumers ON consumers.id = readings.consumer_id WHERE readings.id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $id;

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $myObj = getMinimumRates($link, $row['type']);

                $rate_x = property_exists($myObj, 'rate_x') ? $myObj->rate_x : 0;
                $rate_y = property_exists($myObj, 'rate_y') ? $myObj->rate_y : 0;
                $rate_z = property_exists($myObj, 'rate_z') ? $myObj->rate_z : 0;

                $x = 10;
                $y = 0;
                $z = 0;

                $x_value = (float)$rate_x;
                $y_value = 0;
                $z_value = 0;

                $date_now = date("Y-m-d");
                $over_due = $row['reading_status'] == 0 && $row['due_date'] < $date_now ? 20 : 0;

                if((int)$row['used'] >= 20){
                    $y = 10;
                    $z = (int)$row['used'] - 20;
                } else if((int)$row['used'] >= 10){
                    $z = (int)$row['used'] - 10;
                }
                
                $y_value = (float)$rate_y * $y;
                $z_value = (float)$rate_z * $z;
                $total = $x_value + $y_value + $z_value + $over_due;
                
                if(isset($row['email']) && !empty($row['email'])){
                    $to_email = $row['email'];
                    $subject = "MACWAS Billing Statement";

                    ob_start();
                    include("templates/billing-statement.php");
                    $message = ob_get_contents();
                    ob_end_clean();

                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();                                           // Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                      // Gmail SMTP server
                        $mail->SMTPAuth   = true;                                  // Enable SMTP authentication
                        $mail->Username   = 'your_email@gmail.com';                // Your Gmail address
                        $mail->Password   = 'your_gmail_password';                 // Your Gmail password or App password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          // Use TLS
                        $mail->Port       = 587;                                   // Port for TLS

                        // Recipients
                        $mail->setFrom('your_email@gmail.com', 'MACWAS');
                        $mail->addAddress($to_email);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = $subject;
                        $mail->Body    = $message;

                        $mail->send();

                        // Output SweetAlert and redirect
                        echo '<script>
                        Swal.fire({
                            title: "Success!",
                            text: "Email sent successfully.",
                            icon: "success",
                            toast: true,
                            position: "top-right",
                            showConfirmButton: false,
                            timer: 3000
                        }).then(function() {
                            window.location.href = "index.php";
                        });
                        </script>';
                        exit();
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
                        }).then(function() {
                            window.location.href = "index.php";
                        });
                        </script>';
                    }
                }
            } else {
                header("location: index.php");
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
            }).then(function() {
                window.location.href = "index.php";
            });
            </script>'; 
            exit();
        }
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
} else {
    header("location: index.php");
    exit();
}

function getMinimumRates($link, $type){
    $myObj = new stdClass;
    $query = "SELECT * FROM minimum_rates WHERE type=?";
    if($stmt = mysqli_prepare($link, $query)){
        mysqli_stmt_bind_param($stmt, "s", $param_type);
        $param_type = $type;
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){
                $mrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $myObj->rate_x = $mrow['rate_x'];
                $myObj->rate_y = $mrow['rate_y'];
                $myObj->rate_z = $mrow['rate_z'];
            }
        }
        mysqli_stmt_close($stmt);
    }
    return $myObj;
}
?>
