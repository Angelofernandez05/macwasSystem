<?php
// Ensure the session is already started before including this file
// You don't need to call session_start() here if it's already started elsewhere
// session_start(); // Comment this out or remove it if it's already called elsewhere

// Ensure that user data is available
// For example, fetch from session variables
$user_name = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Guest';
?>
<style>
    .dropdown-toggle {
        color: white !important;
        display: flex;
        align-items: center; /* Align items vertically */
    }

    .dropdown-toggle img {
        width: 25px; /* Adjust the width of the profile picture */
        height: auto; /* Maintain aspect ratio */
        border-radius: 50%; /* Make it round */
        margin-right: 5px; /* Space between picture and name */
    }

    .dropdown-toggle h6 {
        margin-bottom: 0; /* Remove bottom margin for the name */
    }
</style>
<!-- userMenu.php -->

<div class="dropdown">
    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="plumber.png" alt="Profile Picture"> <!-- Replace with PHP to dynamically fetch profile picture -->
        <h6 class="mb-0">Hi, <?php echo $user_name; ?></h6>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="reset_password.php">Reset Your Password</a>
        <a class="dropdown-item" href="#" onclick="confirmLogout()">Sign Out</a>
    </div>
</div>

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logout.php';
            }
        });
        return false; // Prevent the default link behavior
    }
</script>
