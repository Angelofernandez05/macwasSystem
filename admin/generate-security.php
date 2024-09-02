<?php
// session_start();

// if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
//     header("location: login.php");
//     exit;
// }

require_once "config.php";
 
$new_generated_password = generateRandomPassword();

$password = password_hash($new_generated_password, PASSWORD_DEFAULT);

$sql = "UPDATE security SET password = ? WHERE id = ?";
        
if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
    $param_password = $new_generated_password;
    $param_id = 1;
            
    if(mysqli_stmt_execute($stmt)){
        // Send new password to user's email
        $user_email = 'macwasobs.notify@gmail.com';
        $new_password = $new_generated_password; // use the new generated password
        
        $to_email = $user_email;
        $subject = "MACWAS Security Password";
        $message = 'Your new password is: ' . $new_password;

        $headers = array(
            "MIME-Version" => "1.0",
            "Content-type" => "text/html;charset=UTF-8",
            "From" => "MACWAS"
        );

        $send = mail($to_email, $subject, $message, $headers);
                    
        // echo ($send ? '<div class="alert alert-success" role="alert">Email sent successfully.</div>' : '<div class="alert alert-danger" role="alert">There was an error.</div>');
    
        // Redirect to login page
        if($send){
            include('index.php');
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
            exit();
        }else{
            echo '<script>
            Swal.fire({
            title: "Error!",
            text: "There was an error.",
            icon: "error",
            toast: true,
            position: "top-right",
            showConfirmButton: false,
            timer: 3000
            })
            </script>';
        }
    }
}

function generateRandomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $password = array(); // Declare $password as an array
    $alpha_length = strlen($alphabet) - 1; // Put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alpha_length);
        $password[] = $alphabet[$n];
    }
    return implode($password); // Turn the array into a string
}
?>
