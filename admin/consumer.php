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
                <title>Admin Consumers</title>
                <?php include 'includes/links.php'; ?>
                <!-- DataTables CSS -->
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
                <style>

                    body{
                        background: linear-gradient(135deg, #e0eafc, #cfdef3);
                    }

                    .navbar-light-gradient {
                        background: linear-gradient(135deg, #36d1dc, #5b86e5);
                        color: white;
                        border-bottom: 2px solid black !important;
                        margin-left: 10px;
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
                    .table thead th {
                        background-color: #f8f9fa; /* Light background color for table header */
                        border-bottom: 2px solid #dee2e6; /* Slightly thicker border for header bottom */
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
                            <i class='bx bx-menu mr-3' style='color: black; cursor: pointer; font-size: 2rem'></i>
                            Consumers
                        </span>
                        <?php include 'includes/userMenu.php'; ?>
                    </nav>

                    <div class="container-fluid py-5">
                        <a href="new-consumer.php" class="btn btn-primary btn-sm mb-3"><i class='bx bx-plus'></i> New</a>
                        
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
                                                echo '<a href="update-consumer.php?id='. $consumer_id.'" class="mr-2" title="Update Record" data-toggle="tooltip"><i class="bx bxs-pencil btn btn-success btn-sm mb-3"></i></a>';
                                                echo '<a href="delete-consumer.php" class="deleteButton" title="Delete Record" data-toggle="tooltip" data-id="'.$consumer_id.'"><i class="bx bxs-trash-alt btn btn-danger btn-sm mb-3"></i></a>';
                                                if($row['status'] == 0){
                                                    echo '<a href="#" class="viewButton" title="Enable Record" data-toggle="tooltip" data-id="'.$consumer_id.'" data-status="1"><i class="bx bx-show btn btn-success btn-sm mb-3 btn-sm ml-2"></i></a>';
                                                }else{
                                                    // echo '<a href="#" class="viewButton" title="Disable Record"  data-toggle="tooltip" data-id="'.$consumer_id.'" data-status="0"><i class="bx bx-hide btn btn-warning btn-sm mb-3 btn-sm ml-2"></i></a>';
                                                }
                                                // Added action for reading
                                                //echo '<a href="reading.php?consumer_id='. $consumer_id .'" class="mr-2" title="Reading  " data-toggle="tooltip"><i class="bx bx-book-open btn btn-info btn-sm mb-3 ml-2"></i></a>';
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

                document.querySelectorAll('.deleteButton').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.getAttribute('data-id');
                        Swal.fire({
                            title: `Are you sure you want to delete this record?`,
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'No, cancel',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `delete-consumer.php?id=${id}`;
                            }
                        });
                    });
                });

                document.querySelectorAll('.viewButton').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.getAttribute('data-id');
                        const status = this.getAttribute('data-status');
                        const action = status === '0' ? 'disable' : 'enable';

                        Swal.fire({
                            title: `Are you sure you want to ${action} this record?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: `Yes, ${action} it!`,
                            cancelButtonText: 'No, cancel',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `view-consumer.php?id=${id}&status=${status}`;
                            }
                        });
                    });
                });
                </script>
            </body>
            </html>
