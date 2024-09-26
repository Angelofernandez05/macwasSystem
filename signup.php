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
        exit();
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
            background-image: url("tank.jpg");
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
            height: 40px;
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
            background-color: rgba(173, 216, 230, 0.3);
            padding: 20px;
            backdrop-filter: blur(5px);
        }
        .container {
            max-width: 900px;
            margin-top: 50px;
        }
        .form-control {
            border-radius: 15px;
        }
        .btn {
            border-radius: 30px;
            font-weight: 600;
        }
       
        @media (max-width: 768px) {
            .container {
                margin-top: 500px;
            }

            .card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<section class="vh-100 d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="card">
            <div class="card-body text-center">
                <p class="text-center mb-4">
                    <img src="logo.png" alt="Admin-Icon" style="width: 200px; height: 150px;">
                </p>
                <h2 class="text-center mb-4"><strong>Sign up</strong></h2>
                <form action="signup.php" method="post">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <!-- Personal Information -->
                            <div class="form-group">
                            <label class="icon">👤<strong>Name:</strong></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                            <label class="icon">📍</span><strong>Barangay:</strong></label>
                                <select class="form-control" id="barangay" name="barangay" required>
                                    <option value="">Select Brgy</option>
                                    <option value="Poblacion">Poblacion</option>
                                    <option value="Tugas">Tugas</option>
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
                            <label class="icon">🔢<strong>Account Number:</strong></label>
                                <input type="text" class="form-control" id="account_num" name="account_num" readonly>
                            </div>
                            <div class="form-group">
                            <label class="icon">🔑<strong>Registration Number:</strong></label>
                                <input type="text" class="form-control" id="registration_num" name="registration_num" readonly>
                            </div>
                            <div class="form-group">
                                <label for="meter_num">Meter Number:</label>
                                <input type="text" class="form-control" id="meter_num" name="meter_num" readonly>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <!-- Contact Information -->
                            <div class="form-group position-relative">
                                <label for="type">Type:</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="commercial">Commercial</option>
                                    <option value="residential">Residential</option>
                                    <option value="institution">Institution</option>
                                </select>
                            </div>
                            <div class="form-group position-relative">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group position-relative">
                                <label>Contact No.</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+63</span>
                                    </div>
                                    <input type="text" class="form-control" id="phone" name="phone" required pattern="[9][0-9]{9}" maxlength="10">
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
    </div>
</section>

<!-- Font Awesome for password eye icon -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('keydown', function (e) {
        // Disable F12
        if (e.key === 'F12') {
            e.preventDefault();
        }
        // Disable Ctrl + Shift + I
        if (e.ctrlKey && e.shiftKey && e.key === 'I') {
            e.preventDefault();
        }
    });

    // Disable right-click
    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
    });
    function togglePassword() {
        var password = document.getElementById("password");
        var icon = document.getElementById("toggle-icon");
        if (password.type === "password") {
            password.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            password.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>
</body>
</html>
