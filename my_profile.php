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
} else {
    // Default values in case of failure
    $name = '';
    $barangay = '';
    $email = '';
    $phone = '';
    $account_num = '';
    $registration_num = '';
    $meter_num = '';
}

// Handle image upload
$upload_error = "";
$upload_success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_image"])) {
    $target_dir = "uploads/"; // Folder where images will be saved
    $original_file_name = basename($_FILES["profile_image"]["name"]);
    $imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));
    $unique_file_name = uniqid('', true) . '.' . $imageFileType; // Create a unique file name
    $target_file = $target_dir . $unique_file_name; // New target file path
    $allowed_types = ["jpg", "jpeg", "png", "gif"];

    // Check if the uploaded file is an image
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check === false) {
        $upload_error = "File is not an image.";
    }

    // Check file size (5MB limit)
    if ($_FILES["profile_image"]["size"] > 5000000) {
        $upload_error = "Sorry, your file is too large.";
    }

    // Allow certain file formats
    if (!in_array($imageFileType, $allowed_types)) {
        $upload_error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }

    // Check if there are any errors, and if not, move the file
    if (empty($upload_error)) {
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            // Save the file path in the database
            $sql = "UPDATE consumers SET profile_image = ? WHERE id = ?";
            if ($stmt = $link->prepare($sql)) {
                $stmt->bind_param("si", $target_file, $_SESSION["id"]);
                if ($stmt->execute()) {
                    $upload_success = "Profile image uploaded successfully.";
                } else {
                    $upload_error = "Error updating profile image in database.";
                }
                $stmt->close();
            }
        } else {
            $upload_error = "Sorry, there was an error uploading your file.";
        }
    }
}

// Close connection
$link->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="logo.png" type="image/icon type">
    <style>
        body {
            background-image: url("tank.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;
            font-size: 18px;
        }
        .card {
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: rgba(173, 216, 230, 0.2);
            padding: 20px;
            backdrop-filter: blur(3px);
        }
        h1 {
            font-size: 2.5rem;
        }
        strong {
            font-size: 1.2rem;
        }
        .profile-image-container {
            position: relative;
            display: inline-block;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%; /* Make the image circular */
            object-fit: cover; /* Cover the container without stretching */
        }
        .change-photo-button {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 123, 255, 0.8);
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .change-photo-button:hover {
            background-color: rgba(0, 123, 255, 1);
        }
    </style>
</head>
<body class="bg-light">
    <section class="vh-100" style=" background-image: url tank.jpg";>
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-4">
                            <h1 class="text-center fw-bold mb-2"></h1>
                            <div class="text-center mb-4 profile-image-container">
                                <?php if (isset($profile_image) && !empty($profile_image)): ?>
                                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="profile-image"> <!-- User's uploaded image -->
                                <?php else: ?>
                                    <img src="sam.jpg.jpg" alt="Default Profile Image" class="profile-image"> <!-- Placeholder image -->
                                <?php endif; ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                    <input type="file" name="profile_image" class="d-none" id="profile_image" required onchange="this.form.submit();">
                                    <!-- <button type="button" class="btn change-photo-button" onclick="document.getElementById('profile_image').click()">Change Photo</button> -->
                                </form>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <p><span class="icon">👤</span><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                                    <p><span class="icon">📍</span><strong>Barangay:</strong> <?php echo htmlspecialchars($barangay); ?></p>
                                    <p><span class="icon">📧</span><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                                    <p><span class="icon">📞</span><strong>Contact Number:</strong> <?php echo htmlspecialchars($phone); ?></p>
                                    </div>
                                <div class="col-md-6">
                                    <p><span class="icon">🔢</span><strong>Account Number:</strong> <?php echo htmlspecialchars($account_num); ?></p>
                                    <p><span class="icon">🔑</span><strong>Registration Number:</strong> <?php echo htmlspecialchars($registration_num); ?></p>
                                    <p><span class="icon">⚡</span><strong>Meter Number:</strong> <?php echo htmlspecialchars($meter_num); ?></p>
                                </div>
                            </div>
                                </div>

                            <?php if (!empty($upload_error)): ?>
                                <div class="alert alert-danger mt-3"><?php echo $upload_error; ?></div>
                            <?php elseif (!empty($upload_success)): ?>
                                <div class="alert alert-success mt-3"><?php echo $upload_success; ?></div>
                            <?php endif; ?>

                            <div class="d-flex justify-content-center">
                                <a href="edit_profile.php" class="btn btn-primary btn-lg">Edit Profile</a>
                                <a href="index.php" class="btn btn-secondary btn-lg ms-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
        </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>
</body>
</html>
