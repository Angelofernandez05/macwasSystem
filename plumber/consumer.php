<?php
// Initialize the session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consumers</title>
    <?php include 'includes/links.php'; ?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
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
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
        }
        .navbar-light-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-bottom: 2px solid black !important;
            height: 60px;
        }
        .table thead th {
            background-color: #f8f9fa; /* Light background color for table header */
            border-bottom: 2px solid #dee2e6; /* Slightly thicker border for header bottom */
        }
        .icon-size {
            font-size: 1rem; /* Adjust the size to be smaller */
        }
        .icon-margin {
            margin-right: 0.7rem; /* Adjust margin to ensure consistent spacing */
        }
        .btn-sm .bx-mail-send {
            font-size: .8rem; /* Adjust the size of the mail-send icon */
        }
        .dropdown-toggle::after {
            display: none; /* Remove the default dropdown arrow */
        }
    </style>
</head>
<body>
    <!-- Confirmation modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to perform this action?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="confirm" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                Consumers
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container-fluid py-5">
            <?php
            // Include config file
            require_once "config.php";
            $consumer_id = "";
            $status = "";
            // Attempt select query execution
            $sql = "SELECT * FROM consumers";
            if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                    echo '<div class="table-responsive">';
                    echo '<table id="consumerTable" class="display table table-striped" style="width:100%">';
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
                            $email = $row['email'];
                            $phone = "+63".$row['phone'];
                            if(empty($row['email'])){
                                $email = 'N/A';
                            }
                            $consumer_id = $row['id'];
                            $status = $row['status'];
                            $rid = "";
                            $sql2 = "SELECT * FROM readings WHERE consumer_id = $consumer_id";
                            if($result2 = mysqli_query($link, $sql2)){
                                if(mysqli_num_rows($result2) > 0){
                                    while($row2 = mysqli_fetch_array($result2)){
                                        $rid = $row2['id'];
                                    }
                                }
                            }
                            echo "<tr>";
                            if($row['status'] == 0){
                                echo '<td><a class="text-danger" href="reading.php?consumer_id='. $consumer_id .'&id='.$rid.'">'. $row['name'] .'</a></td>';
                            }else{
                                echo '<td><a class="text-success" href="reading.php?consumer_id='. $consumer_id .'&id='.$rid.'">'. $row['name'] .'</a></td>';
                            }

                            echo "<td>" . $email . "</td>";
                            echo "<td>" . $phone . "</td>";
                            echo "<td>" . $row['barangay'] . "</td>";
                            echo "<td>" . $row['account_num'] . "</td>";
                            echo "<td>" . $row['registration_num'] . "</td>";
                            echo "<td>" . $row['meter_num'] . "</td>";
                            echo "<td>" . $row['type'] . "</td>";
                            echo "<td>";
                                // Added action for reading
                                echo '<a href="reading.php?consumer_id='. $consumer_id .'" class="mr-2" title="Reading" data-toggle="tooltip"><i class="bx bx-book-open btn btn-info btn-sm mb-1 ml-2"></i></a>';
                                
                                // Added bx-mail-send dropdown
                                echo '<div class="dropdown d-inline-block">';
                                echo '<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $consumer_id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                echo '<i class="bx bx-mail-send"></i>';
                                echo '</button>';
                                echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton' . $consumer_id . '">';
                                echo '<a target="_blank" href="send-billing-statement.php?id='. $row['id'] .'" class="dropdown-item" title="Job Order" data-toggle="tooltip">Job Order</a>';
                                echo '<a target="_blank" href="send-notice-disconnection.php?id='. $row['id'] .'" class="dropdown-item" title="Notice of Disconnection" data-toggle="tooltip">Notice of Disconnection</a>';
                                echo '</div>';
                                echo '</div>'; // Close dropdown
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
                                text: "No records were found.",
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
    // Initialize DataTable
    $(document).ready(function() {
        $('#consumerTable').DataTable({
            "paging": true,
            "searching": true,
            "info": true
        });
    });

    // Hide the alert after 3 seconds
    setTimeout(function(){
        var alert = document.querySelector('.alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 3000);
    </script>
</body>
</html>
