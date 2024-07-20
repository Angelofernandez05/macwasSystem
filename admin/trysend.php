<?php
include('dbcon.php');
// Start the session
session_start();

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

// Connect to database and get upcoming events
// $pdo = new PDO("mysql:host=localhost;dbname=mydb", "username", "password");
$stmt = $conn->prepare("SELECT * FROM upcoming_events WHERE start_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 DAY)");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Server settings
$mail->SMTPDebug = 0;                      //Enable verbose debug output
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = 'smtp.gmail.com';                    //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = 'Sender email';                     //SMTP username
$mail->Password   = 'app password';                               //SMTP password
$mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
$mail->Port       = 587;                                   //TCP port to connect to

 // Disable certificate verification
 $mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

// Create a table to display the list of upcoming events
$event_table = '<table style="font-family: Arial, sans-serif; border-collapse: collapse; width: 100%;">';
$event_table .= '<thead style="background-color: #ddd; text-align: left;"><tr><th style="padding: 12px; border: 1px solid #ddd;">Event Name</th><th style="padding: 12px; border: 1px solid #ddd;">Date</th></tr></thead><tbody>';
foreach ($events as $event) {
    $event_name = $event['title'];
    $event_date = date('F j, Y', strtotime($event['start_date']));
    $event_table .= '<tr><td style="padding: 12px; border: 1px solid #ddd;">' . $event_name . '</td><td style="padding: 12px; border: 1px solid #ddd;">' . $event_date . '</td></tr>';
}
$event_table .= '</tbody></table>';

// Set up the email message using the event table
$email_subject = 'Upcoming Events';
$email_body = '<p style="font-family: Arial, sans-serif; font-size: 16px;">Here are the upcoming events:</p>' . $event_table;

try {
    // Set up the email recipient
    $mail->setFrom('Sender Email', 'Event Judging Management System');
    $mail->addAddress($_SESSION['email']);

    // Set up the email message
    $mail->isHTML(true);
    $mail->Subject = $email_subject;
    $mail->Body = $email_body;

    // Send the email
    $mail->send();

    // Output a success message
    echo "Push notification sent successfully for upcoming events.<br>";
} catch (Exception $e) {
    // Output an error message if the email couldn't be sent
    echo "Push notification could not be sent for upcoming events. Error: " . $mail->ErrorInfo . "<br>";
}

?>
