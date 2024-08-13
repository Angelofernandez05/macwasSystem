<style>
    .dropdown-toggle {
        color: white !important;
    }
    
    .dropdown-toggle::after {
        color: white;
    }
</style>

<div class="dropdown">
    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span style="color: white;">Hi, <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="reset-password.php">Reset Your Password</a>
        <a class="dropdown-item" href="generate-security.php">Generate Security Password</a>
        <a class="dropdown-item" href="#" onclick="logoutConfirmation()">Sign Out</a>
    </div>
</div>

<script>
    function logoutConfirmation() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to logout script
                window.location.href = 'logout.php';
            }
        });
    }
</script>
