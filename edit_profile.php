<?php
// Initialize the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Fetch user information
$sql = "SELECT name, barangay, email, phone, account_num, registration_num, meter_num FROM consumers WHERE id = ?";
if ($stmt = $link->prepare($sql)) {
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $stmt->bind_result($name, $barangay, $email, $phone, $account_num, $registration_num, $meter_num);
    $stmt->fetch();
    $stmt->close();
}

// Initialize variables for form submission
$new_name = $new_barangay = $new_email = $new_phone = $new_account_num = $new_registration_num = "";
$new_name_err = $new_barangay_err = $new_email_err = $new_phone_err = $new_account_num_err = $new_registration_num_err = "";
$success_message = false; // Variable to track success

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $new_name_err = "Please enter your name.";
    } else {
        $new_name = trim($_POST["name"]);
    }

    // Validate barangay
    if (empty(trim($_POST["barangay"]))) {
        $new_barangay_err = "Please enter your barangay.";
    } else {
        $new_barangay = trim($_POST["barangay"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $new_email_err = "Please enter your email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $new_email_err = "Invalid email format.";
    } else {
        $new_email = trim($_POST["email"]);
    }

    // Validate phone number
    if (empty(trim($_POST["phone"]))) {
        $new_phone_err = "Please enter your contact number.";
    } else {
        $new_phone = trim($_POST["phone"]);
    }

    // Validate account number
    if (empty(trim($_POST["account_num"]))) {
        $new_account_num_err = "Please enter your account number.";
    } else {
        $new_account_num = trim($_POST["account_num"]);
    }

    // Validate registration number
    if (empty(trim($_POST["registration_num"]))) {
        $new_registration_num_err = "Please enter your registration number.";
    } else {
        $new_registration_num = trim($_POST["registration_num"]);
    }

    // Check input errors before updating the database
    if (empty($new_name_err) && empty($new_barangay_err) && empty($new_email_err) && empty($new_phone_err) && empty($new_account_num_err) && empty($new_registration_num_err)) {
        // Prepare an update statement
        $sql = "UPDATE consumers SET name = ?, barangay = ?, email = ?, phone = ?, account_num = ?, registration_num = ? WHERE id = ?";

        if ($stmt = $link->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssi", $new_name, $new_barangay, $new_email, $new_phone, $new_account_num, $new_registration_num, $_SESSION["id"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Profile updated successfully
                $success_message = true;
            } else {
                // Show error message
                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>
                Swal.fire({
                    title: "Error!",
                    text: "Oops! Something went wrong. Please try again later.",
                    icon: "error",
                    toast: true,
                    position: "top-right",
                    showConfirmButton: false,
                    timer: 3000
                });
                </script>';
            }

            // Close statement
            $stmt->close();
        }
    }
    // Close connection
    $link->close();
}
?>

<style>
    body {
            background-image: url("tank.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;
            font-size: 18px;
        }
        .card-body {
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: rgba(173, 216, 230, 0.6);
            padding: 20px;
            backdrop-filter: blur(3px);
        }

        </style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="logo.png" type="image/icon type">
</head>
<body class="bg-light">
    <section class="vh-100" style="background-image: url tank.jpg">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-6 col-xl-5"> <!-- Minimized box size -->
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-3">
                        <h1 class="text-center fw-bold mb-4" style="font-family: Georgia, serif;">Edit Profile</h1>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="mb-4">
                                <label class="icon">👤<strong>Name:</strong></label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" readonly>
                                    <span class="text-danger"><?php echo $new_name_err; ?></span>
                                </div>
                                <div class="mb-4">
                                <label class="icon">📍</span><strong>Barangay:</strong></label>
                                <select type="text" class="form-control" name="barangay" value="<?php echo htmlspecialchars($barangay); ?>" required>>
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
                                <div class="mb-4">
                                    <label class="icon">📧<strong>Email:</strong></label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                    <span class="text-danger"><?php echo $new_email_err; ?></span>
                                </div>
                                 <div class="mb-4">
                                    <label class="icon">📞</span><strong>Contact Number:</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+63</span>
                                        </div>
                                        <input type="text" class="form-control" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>" required pattern="[9][0-9]{9}" maxlength="10" placeholder="9XXXXXXXXX" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                    </div>
                                    <span class="text-danger"><?php echo $new_phone_err; ?></span>
                                </div>
                                <div class="mb-4">
                                    <label class="icon">🔑<strong>Registration Number:</strong></label>
                                    <input type="text" class="form-control" name="registration_num" value="<?php echo htmlspecialchars($registration_num); ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label class="icon">🔢<strong>Account Number:</strong></label>
                                    <input type="text" class="form-control" name="account_num" value="<?php echo htmlspecialchars($account_num); ?>" required>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <input type="submit" value="Update Profile" class="btn btn-primary btn-lg">
                                    <a href="my_profile.php" class="btn btn-secondary btn-lg ms-2">Cancel</a>
                                </div>
                                <!-- Hidden field to indicate success -->
                                <input type="hidden" name="success" value="<?php echo $success_message ? '1' : '0'; ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Check if the hidden input indicates success
        const successInput = document.querySelector('input[name="success"]');
        if (successInput && successInput.value === '1') {
            Swal.fire({
                title: "Success!",
                text: "Your profile has been updated successfully.",
                icon: "success",
                toast: true,
                position: "top-right",
                showConfirmButton: false,
                timer: 3000
            }).then(() => {
                window.location.href = "my_profile"; // Redirect to profile page after showing the alert
            });
        }
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
    </script>
</body>
</html>
