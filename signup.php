<?php
session_start();
include 'config.php'; // Make sure this includes your database connection

// Check if form is submitted
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

    // Validate phone number
    if (!preg_match('/^\d{1,11}$/', $phone)) {
        $error_msg = "Phone number must be up to 11 digits.";
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
        window.onload = function() {
            Swal.fire({
                title: "Error!",
                text: "' . $error_msg . '",
                icon: "error",
                toast: true,
                position: "center",
                showConfirmButton: false,
                timer: 3000
            });
        };
        </script>';
    } else {
        // Check if meter number already exists
        $sql = "SELECT id FROM consumers WHERE meter_num = '$meter_num'";
        $result = mysqli_query($link, $sql);

        if (mysqli_num_rows($result) > 0) {
            $error_msg = "Error: Meter number already exists.";
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
            window.onload = function() {
                Swal.fire({
                    title: "Error!",
                    text: "' . $error_msg . '",
                    icon: "error",
                    toast: true,
                    position: "center",
                    showConfirmButton: false,
                    timer: 3000
                });
            };
            </script>';
        } else {
            // Insert the new user
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO consumers (name, barangay, account_num, registration_num, meter_num, type, email, phone, password, status) 
                    VALUES ('$name', '$barangay', '$account_num', '$registration_num', '$meter_num', '$type', '$email', '$phone', '$password_hashed', '$status')";

            if (mysqli_query($link, $sql)) {
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>
                window.onload = function() {
                    Swal.fire({
                        title: "Success!",
                        text: "Registration successful. Redirecting to login page.",
                        icon: "success",
                        toast: true,
                        position: "center",
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function() {
                        window.location.href = "login.php";
                    });
                };
                </script>';
                exit();
            } else {
                $error_msg = "Error: " . mysqli_error($link);
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>
                window.onload = function() {
                    Swal.fire({
                        title: "Error!",
                        text: "' . $error_msg . '",
                        icon: "error",
                        toast: true,
                        position: "center",
                        showConfirmButton: false,
                        timer: 2000
                    });
                };
                </script>';
            }
        }

        mysqli_close($link);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 15px;
            background-color: rgba(173, 216, 230, 0.6); /* Light blue with transparency */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
            color: #000; /* Black text color */
            max-width: 800px; /* Adjust as needed */
            width: 100%;
            height: 75vh;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: 600;
        }
        .btn-primary {
            height: 50px; /* Increased height */
            font-size: 18px;
        }
        .custom-swal {
            max-width: 800px; /* Set the maximum width */
            width: 90%; /* Make it responsive */
            height: auto; /* Auto height based on content */
        }
        .custom-swal .swal2-popup {
            border-radius: 80px; /* Rounded corners */
            padding: 70px; /* Adjust padding */
            box-sizing: border-box; /* Include padding and border in the width and height */
        }
        .custom-swal .swal2-title {
            font-size: 1.5rem; /* Adjust title font size */
        }
        .custom-swal .swal2-content {
            font-size: 1.2rem; /* Adjust content font size */
        }
        .custom-swal .swal2-icon {
            font-size: 3rem; /* Adjust icon size */
        }
        .custom-swal .swal2-confirm {
            height: 50px; /* Adjust button height */
        }
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-5">Signup</h2>
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
                            <input type="text" class="form-control" id="account_num" name="account_num" required>
                        </div>
                        <div class="form-group">
                            <label for="registration_num">Registration Number:</label>
                            <input type="text" class="form-control" id="registration_num" name="registration_num" required>
                        </div>
                        <div class="form-group">
                            <label for="meter_num">Meter Number:</label>
                            <input type="text" class="form-control" id="meter_num" name="meter_num" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Contact Information -->
                        <div class="form-group position-relative">
                            <label for="type">Type:</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="commercial">Commercial</option>
                                <option value="residential">Residential</option>
                            </select>
                        </div>
                        <div class="form-group position-relative">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group position-relative">
                            <label for="phone">Phone:</label>
                            <input type="text" class="form-control" id="phone" name="phone" required oninput="validatePhone(this)">
                        </div>
                        <div class="form-group position-relative">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>


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
        if (input.value.length > 11) {
            input.value = input.value.slice(0, 11); // Truncate input to 11 characters
        }
    }
    </script>
</body>
</html>
