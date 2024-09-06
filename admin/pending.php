<?php
// Initialize the session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if the user is logged in, if not then redirect to the login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <?php include 'includes/links.php'; ?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
        }
        .navbar-light-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-bottom: 2px solid black !important;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='color: black; cursor: pointer; font-size: 2rem'></i>
                Pending Consumers
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container-fluid py-5">
            <?php
            // Include config file
            require_once "config.php";

            // Attempt select query execution
            $sql = "SELECT * FROM pending_users";
            if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                    echo '<div class="table-responsive">';
                    echo '<table id="pendingTable" class="display table table-striped" style="width:100%">';
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>Name</th>";
                                echo "<th>Email</th>";
                                echo "<th>Phone</th>";
                                echo "<th>Barangay</th>";
                                echo "<th>Account No.</th>";
                                echo "<th>Registration No.</th>";
                                echo "<th>Meter No.</th>";
                                echo "<th>Type</th>";
                                echo "<th>Action</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = mysqli_fetch_array($result)){
                            $consumer_id = $row['id'];
                            echo "<tr>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['phone'] . "</td>";
                                echo "<td>" . $row['barangay'] . "</td>";
                                echo "<td>" . $row['account_num'] . "</td>";
                                echo "<td>" . $row['registration_num'] . "</td>";
                                echo "<td>" . $row['meter_num'] . "</td>";
                                echo "<td>" . $row['type'] . "</td>";
                                echo "<td>";
                                    echo '<a href="process_user.php?action=accept&id='. $consumer_id.'" class="mr-2" title="Approve Record" data-toggle="tooltip"><i class="bx bxs-check-circle btn btn-success btn-sm mb-3"></i></a>';
                                    echo '<a href="process_user.php?action=decline&id='. $consumer_id.'" class="mr-2" title="Decline Record" data-toggle="tooltip"><i class="bx bxs-x-circle btn btn-danger btn-sm mb-3"></i></a>';
                                    echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";                            
                    echo "</table>";
                    echo '</div>';
                    mysqli_free_result($result);
                } else{
                    echo '<script>
                            Swal.fire({
                            title: "Info!",
                            text: "No pending consumers found.",
                            icon: "info",
                            toast: true,
                            position: "top-right",
                            showConfirmButton: false,
                            timer: 3000
                            })
                        </script>';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_close($link);
            ?>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
    // Initialize DataTable for pending consumers
    $(document).ready(function() {
        $('#pendingTable').DataTable({
            "paging": true,
            "searching": true,
            "info": true
        });
    });
    function acceptUser(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to accept this user.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Yes, accept it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `process_user.php?action=accept&id=${userId}`;
                }
            });
        }

        function declineUser(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to decline this user.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#28a745',
                confirmButtonText: 'Yes, decline it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `process_user.php?action=decline&id=${userId}`;
                }
            });
        }
    </script>
</body>
</html>
    </script>
</body>
</html>
