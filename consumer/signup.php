<?php
// Database connection
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $barangay = mysqli_real_escape_string($link, $_POST['barangay']);
    $account_num = mysqli_real_escape_string($link, $_POST['account_num']);
    $registration_num = mysqli_real_escape_string($link, $_POST['registration_num']);
    $meter_num = mysqli_real_escape_string($link, $_POST['meter_num']);
    $type = mysqli_real_escape_string($link, $_POST['type']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $status = mysqli_real_escape_string($link, $_POST['status']);

    // Handle empty meter_num
    if (empty($meter_num) && empty($account_num) && empty($registration_num)) {
        $meter_num = NULL;
        $account_num = NULL;
        $registration_num = NULL;
    }

    // Validate phone number
    if (!preg_match('/^\d{1,11}$/', $phone)) {
        $error_msg = "Phone number must be up to 11 digits.";
        $alert_type = 'error';
    }elseif (strlen($password) <= 8) {
        $error_msg = "Password must be greater than 8 characters.";
        $alert_type = 'error';
    } else {
        // Check if email already exists
        $sql = "SELECT id FROM consumers WHERE email = '$email'";
        $result = mysqli_query($link, $sql);

        if (mysqli_num_rows($result) > 0) {
            $error_msg = "Error: Email already exists.";
            $alert_type = 'error';
        } else {
            // Insert the new user into pending_users table
            $sql = "INSERT INTO pending_users (name, barangay, account_num, registration_num, meter_num, type, email, phone, password, status) 
                    VALUES ('$name', '$barangay', '$account_num', '$registration_num', " . ($meter_num === NULL ? 'NULL' : "'$meter_num'") . ", '$type', '$email', '$phone', '$password', '$status')";

            if (mysqli_query($link, $sql)) {
                $error_msg = "Registration successful. Awaiting admin approval.";
                $alert_type = 'success';
            } else {
                $error_msg = "Error: " . mysqli_error($link);
                $alert_type = 'error';
            }
        }
        mysqli_close($link);
    }

    // Output JavaScript for SweetAlert
    if (isset($alert_type)) {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Signup</title>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
            <style>
                .custom-swal {
                    max-width: 800px;
                    width: 90%;
                    height: auto;
                }
                .custom-swal .swal2-popup {
                    border-radius: 80px;
                    padding: 70px;
                    box-sizing: border-box;
                }
                .custom-swal .swal2-title {
                    font-size: 1.5rem;
                }
                .custom-swal .swal2-content {
                    font-size: 1.2rem;
                }
                .custom-swal .swal2-icon {
                    font-size: 3rem;
                }
                .custom-swal .swal2-confirm {
                    height: 50px;
                }
            </style>
        </head>
        <body>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                window.onload = function() {
                    Swal.fire({
                        title: "' . ($alert_type == 'success' ? 'Success!' : 'Error!') . '",
                        text: "' . $error_msg . '",
                        icon: "' . $alert_type . '",
                        toast: true,
                        position: "center",
                        showConfirmButton: false,
                        timer: 3000
                    }).then(function() {
                         window.location.href = "login.php";
                    });
                };
            </script>
        </body>
        </html>';
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
         body {
            background-image: url("image/tank.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;
        }   
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: 600;
        }
        .btn-primary {
            height: 40px; /* Increased height */
            font-size: 18px;
        }
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            margin-top: 15px;
        }
        .card {
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: rgba(173, 216, 230, 0.2); /* Light blue with some transparency */
            padding: 20px; /* Add padding for content inside the card */
            backdrop-filter: blur(5px); /* Optional: Adds a blur effect to the background of the card */
        }

        .card-body {
            padding: 1rem;
        }

        .container {
            max-width: 900px;
            margin-left: 30px; /* Adjust this value to move the form further left */
             margin-top: 70px;
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
<body>
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
                    <p class="text-center mb-4">
                            <img src="image/logo.png" alt="Admin-Icon" style="width: 200px; height: 150px;">
                        </p>   
            <h2 class="text-center mb-4"><strong>Sign up</strong></h2>
            <form action="signup.php" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Personal Information -->
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="barangay">Barangay:</label>
                            <select class="form-control" id="barangay" name="barangay" required>
                                <option value="">Select Brgy</option>
                                <option value="Bunakan">Bunakan</option>
                                <option value="Kangwayan">Kangwayan</option>
                                <option value="Kaongkod">Kaongkod</option>
                                <option value="Kodia">Kodia</option>
                                <option value="Maalat">Maalat</option>
                                <option value="Malbago">Malbago</option>
                                <option value="Mancilang">Mancilang</option>
                                <option value="Pili">Pili</option>
                                <option value="Poblacion">Poblacion</option>
                                <option value="San Agustin">San Agustin</option>
                                <option value="Tabagak">Tabagak</option>
                                <option value="Talangnan">Talangnan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="account_num">Account Number:</label>
                            <input type="text" class="form-control" id="account_num" name="account_num" value="" readonly>
                        </div>
                        <div class="form-group">
                            <label for="registration_num">Registration Number:</label>
                            <input type="text" class="form-control" id="registration_num" name="registration_num" oninput="validateRegistrationNum(this)" value="" readonly>
                        </div>
                        <div class="form-group">
                            <label for="meter_num">Meter Number:</label>
                            <input type="text" class="form-control" id="meter_num" name="meter_num" value="" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Contact Information -->
                        <div class="form-group position-relative">
                            <label for="type">Type:</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="commercial">Commercial</option>
                                <option value="residential">Residential</option>
                                <option value="residential">Institution</option>
                            </select>
                        </div>
                        <div class="form-group position-relative">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group position-relative">
                            <label>Contact No. <small class="text-muted">(9123456789)</small></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="phone">+63</span>
                                </div>
                                <input type="text" class="form-control" id="phone" name="phone" required pattern="[9][0-9]{9}" maxlength="10" oninput="validatePhone(this)" aria-describedby="phone">
                            </div>
                        </div>
                        <div class="form-group position-relative">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            <span class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggle-icon"></i>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-3">Register</button>
            </form>
        </div>
    </div>

    <!-- Font Awesome for password eye icon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-icon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

       
                        
        function validatePhone(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value.length > 11) {
                input.value = input.value.slice(0, 11); // Truncate input to 11 characters
            }
        }

        function validateRegistrationNum(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>
</body>
</html>
`