<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="icon" href="logo.png" type="image/icon type">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        body {
            background-image: url("account.webp");
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: 200vh;
        }
        .alert {
            font-size: 14px;
            padding: 8px 12px;
            text-align: center;
            margin: 10px;
            max-width: 600px;
            position: fixed;
            top: 40px;
            right: 10px;
            z-index: 9999;
        }
        .form-outline {
            position: relative;
        }
        .form-outline .fa-eye, .form-outline .fa-eye-slash {
            position: absolute;
            right: 20px;
            top: 45px;
            cursor: pointer;
            margin-top: 10px;
        }
        .container {
            max-width: 800vh;
        }
        .signup-box {
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: rgba(173, 216, 230, 0.0);
            padding: 20px;
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="bg-light">

<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center h-100">    
            <div class="col-md-7 col-lg-5 col-xl-5">
                <div class="signup-box">
                    <p class="text-center mb-5">
                        <img src="logo.png" alt="Admin-Icon" style="width: 250px; height: 200px;">
                    </p>
                    <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">
                        <img src="accountant.png" alt="plumber Icon" style="width: 60px; height: 60px;">
                    </p>
                    <!-- Signup Form -->
                    <form id="signupForm">
                        <!-- Name input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form1Example13"><i class="bi bi-person-circle"></i> <strong>Name</strong></label>
                            <input type="text" id="form1Example13" class="form-control form-control-lg py-3" name="name" autocomplete="off" placeholder="Enter your name" style="border-radius:25px ;">
                            <span class="invalid-feedback" id="name_err"></span>
                        </div>

                        <!-- Username input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form1Example14"><i class="bi bi-person-circle"></i><strong> Username</strong></label>
                            <input type="text" id="form1Example14" class="form-control form-control-lg py-3" name="username" autocomplete="off" placeholder="Enter your username" style="border-radius:25px ;">
                            <span class="invalid-feedback" id="username_err"></span>
                        </div>

                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form1Example15"><i class="bi bi-envelope-fill"></i><strong>Email</strong></label>
                            <input type="email" id="form1Example15" class="form-control form-control-lg py-3" name="email" autocomplete="off" placeholder="Enter your email" style="border-radius:25px ;">
                            <span class="invalid-feedback" id="email_err"></span>
                        </div>

                        <!-- Phone input -->
                        <div class="form-outline mb-4">
                            <label><strong>Contact No. </strong><small class="text-muted"><strong>(9123456789)</strong></small></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text py-3 mt-1" id="phone">+63</span>
                                </div>
                                <input required pattern="[9][0-9]{9,10}" maxlength="11" aria-describedby="phone" required type="text" name="phone" class="form-control form-control-lg py-3" style="border-radius:25px ;" placeholder="Enter your phone number">
                            </div>
                            <span class="invalid-feedback" id="phone_err"></span>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form1Example23"><i class="bi bi-lock-fill"></i> <strong>Password</strong></label>
                            <input type="password" id="form1Example23" class="form-control form-control-lg py-3" name="password" autocomplete="off" placeholder="Enter your password" style="border-radius:25px ;">
                            <span toggle="#form1Example23" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                            <span class="invalid-feedback" id="password_err"></span>
                        </div>

                        <!-- Confirm Password input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form1Example24"><i class="bi bi-lock-fill"></i> <strong>Confirm Password</strong></label>
                            <input type="password" id="form1Example24" class="form-control form-control-lg py-3" name="confirm_password" autocomplete="off" placeholder="Re-enter your password" style="border-radius:25px ;">
                            <span toggle="#form1Example24" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                            <span class="invalid-feedback" id="confirm_password_err"></span>
                        </div>

                        <!-- Submit button -->
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-lg py-3 px-5" style="border-radius:25px; width:100%;">Signup</button>
                        </div>
                        <br>
                        <div class="form-group">
                        <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="terms_and_conditions.html" target="_blank">Terms and Conditions</a>
                        </label>
                    </div>

                        <!-- Login link -->
                        <p class="text-center mt-4">Already have an account? <a href="login.php">Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function () {
    $('#signupForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'signup_process.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                } else {
                    $('#name_err').text(response.name_err);
                    $('#username_err').text(response.username_err);
                    $('#email_err').text(response.email_err);
                    $('#phone_err').text(response.phone_err);
                    $('#password_err').text(response.password_err);
                    $('#confirm_password_err').text(response.confirm_password_err);
                    if (response.signup_err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.signup_err,
                            confirmButtonText: 'OK'
                        });
                    }
                }
            }
        });
    });

    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
});
</script>
<script>
        // Disable right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, and Ctrl+U
        document.addEventListener('keydown', function(e) {
            if (e.keyCode == 123) { // F12
                e.preventDefault();
            }
            if (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74)) { // Ctrl+Shift+I or J
                e.preventDefault();
            }
            if (e.ctrlKey && e.keyCode == 85) { // Ctrl+U
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
