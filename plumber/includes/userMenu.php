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
    }
    
    .dropdown-toggle::after {
        color: white;
    }
</style>
<!-- userMenu.php -->
<div class="dropdown">
    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
