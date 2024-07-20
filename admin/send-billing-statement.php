<?php
// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Include config file
require_once "config.php";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Get URL parameter
    $id =  trim($_GET["id"]);
    // $consumer_id =  trim($_GET["consumer_id"]);

    $sql = "SELECT *, (present - previous) as used, readings.status as reading_status FROM readings LEFT JOIN consumers ON consumers.id = readings.consumer_id WHERE readings.id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = $id;
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $myObj = getMinimumRates($link, $row['type']);

                if (is_object($myObj)) {
                    $rate_x = $myObj->rate_x;
                    $rate_y = $myObj->rate_y;
                    $rate_z = $myObj->rate_z;
                } else {
                    // handle the case where $obj is not an object
                }

                // $rate_x = $row['type'] === 'Commercial' ? 180 : 130;
                // $rate_y = $row['type'] === 'Commercial' ? 20 : 15;
                // $rate_z = $row['type'] === 'Commercial' ? 25 : 18;

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
                }else if((int)$row['used'] >= 10){
                    $z = (int)$row['used'] - 10;
                }
                
                $y_value = (float)$rate_y * $y;
                $z_value = (float)$rate_z * $z;
                $total = $x_value + $y_value + $z_value + $over_due;
                
                if(isset($row['email']) && !empty($row['email'])){
                    $to_email = $row['email'];
                    $subject = "MACWAS Billing Statement";

                    $headers = array(
                        "MIME-Version" => "1.0",
                        "Content-type" => "text/html;charset=UTF-8",
                        "From" => "MACWAS"
                    );

                    ob_start();
                    include("templates/billing-statement.php");
                    $message = ob_get_contents();
                    ob_get_clean();

                    $send = mail($to_email, $subject, $message, $headers);
                    
                    // echo ($send ? '<div class="alert alert-success" role="alert">Email sent successfully.</div>' : '<div class="alert alert-danger" role="alert">There was an error.</div>');
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
            } else{
                header("location: index.php");
                exit();
            }
        } else{
            // echo '<div class="alert alert-danger" role="alert">Oops! Something went wrong. Please try again later.</div>';
            include('index.php');
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
}else{
    header("location: index.php");
    exit();
}

function getMinimumRates($link, $type){
    $myObj = new stdClass;
    $query = "Select * from minimum_rates where type=?";
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