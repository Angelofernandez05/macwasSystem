<!DOCTYPE html>

<?php
// Initialize the session
ob_start();
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if(!isset($_GET["consumer_id"]) || empty(trim($_GET["consumer_id"]))){
    header("location: consumer.php");
    exit;
}else{
    require_once "config.php";

    // Get URL parameter
    $id =  trim($_GET["consumer_id"]);
        
    // Prepare a select statement
    $sql3 = "SELECT * FROM consumers WHERE id = ?";
    if($stmt3 = mysqli_prepare($link, $sql3)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt3, "i", $param_id);
        
        // Set parameters
        $param_id = $id;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt3)){
            $result = mysqli_stmt_get_result($stmt3);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name = $row["name"];
                $barangay = $row["barangay"];
                $account_num = $row["account_num"];
                $registration_num = $row["registration_num"];
                $meter_num = $row["meter_num"];
                $type = $row["type"];
            } else{
                // URL doesn't contain valid id. Redirect to error page
                // header("location: consumer.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    
    // Close statement
    mysqli_stmt_close($stmt3);
}
?>
 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill</title>
    <?php include 'includes/links.php'; ?>
    <style>
         .table-responsive {
    overflow-x: auto;
    /* Ensure the container is responsive */
}

.table-custom {
    width: 100%; /* Ensure the table takes full width */
    max-width: 1300px; /* You can adjust this value */
    margin: 0 auto; /* Center the table */
    margin-left: 140px;
}

.table thead th {
            background-color: #f8f9fa; /* Light background color for table header */
            border-bottom: 2px solid #dee2e6; /* Slightly thicker border for header bottom */
        }

.table-custom th,
.table-custom td {
    white-space: nowrap; /* Prevents content from wrapping */
    text-align: center; /* Center align text */
}

        .alert {
            font-size: 14px;
            padding: 8px 12px;
            text-align: center;
            margin: 10px;
            max-width: 600px;
            position: fixed;
            top: 50px;
            right: 10px;
            z-index: 9999;
        }
        .table-custom {
        width: 100%; /* Full width */
        table-layout: auto; /* Allow columns to resize based on content */
        }
        body{
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
        }
        .navbar-light-gradient {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-bottom: 2px solid black !important;
            height: 60px;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                Bill
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container-fluid py-5">
            <div class="row w-100">
                <div class="col-12 col-lg-9">
                    <!-- <a target="_blank" href="print-reading.php?consumer_id=<?php echo $_GET["consumer_id"] ?>" class="btn btn-primary btn-sm mb-3"><i class='bx bxs-printer'></i> Print</a> -->
                
                    <?php
                    // Include config file
                    // require_once "config.php";
                    $idn = "";
                    
                    // Attempt select query execution
                    $id = $_GET["consumer_id"];
                    $sql = "SELECT *, (present - previous) as used FROM readings WHERE consumer_id = $id ";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-striped table-custom">';
                            echo '<div class="table-responsive">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Due Date</th>";
                                        echo "<th>Date</th>";
                                        echo "<th>Present</th>";
                                        echo "<th>Previous</th>";
                                        echo "<th>Used</th>";
                                        echo "<th>Reference No.</th>";
                                        echo "<th>Screenshot</th>";
                                        echo "<th>Status</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                echo '</div>';
                                while($row = mysqli_fetch_array($result)){
                                    $status = 'Pending';
                                    $idn = $row['id'];

                                if($row['status'] == 1){
                                    $status = 'PAID';
                                }else if($row['status'] == 2){
                                    $status = 'Waiting for approval';
                                }
                                    echo "<tr>";
                                        echo "<td class='text-uppercase'>".date_format(date_create($row['due_date']), 'F j, Y')."</td>";
                                        echo "<td class='text-uppercase'>".date_format(date_create($row['reading_date']), 'F j, Y')."</td>";
                                        echo "<td>" . number_format((float)$row['present'], 2, '.', '') . "</td>";
                                        echo "<td>" . number_format((float)$row['previous'], 2, '.', '') . "</td>";
                                        echo "<td>" . number_format((float)$row['used'], 2, '.', '') . "</td>";
                                        echo "<td>" .$row['ref'] . "</td>";
                                        ?>
                                        <td>
                                        <?php if(!empty($row["screenshot"])) {
                                        echo '<img width="100px" height="100px" src="../uploads/'.$row["screenshot"] .'">';
                                        } ?>
                                        
                                    </td>
                                        <?php
                                        echo "<td>" . $status . "</td>";
                                        echo "<td class='d-flex align-items-center' style='gap: 0.3rem'>";
                                            ?>
                                                <!-- <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class='bx bx-mail-send'></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                        <?php
                                                            // echo '<a target="_blank" href="send-billing-statement.php?id='. $row['id'] .'" class="dropdown-item" title="Print Billing Statement" data-toggle="tooltip">Billing Statement</a>';
                                                            // echo '<a target="_blank" href="send-notice.php?id='. $row['id'] .'" class="dropdown-item" title="Print Billing Statement" data-toggle="tooltip">Notice of Disconnection</a>';
                                                        ?>
                                                    </div>
                                                </div> -->
                                            <?php
                                            // echo '<a target="_blank" href="sendMail.php?consumer_id='.$_GET["consumer_id"].'&id='. $row['id'] .'" class="mr-2" title="Send Billing Statement" data-toggle="tooltip"><i class="bx bx-sm bx-mail-send"></i></a>';
                                            //echo '<a target="_blank" href="print-reading.php?id='. $row['id'] .'" title="Print Billing Statement" data-toggle="tooltip"><i class="bx bxs-printer"></i></a>';
                                            echo '<a target="_blank" href="print-reading.php?id='. $row['id'] .'" title="Print Billing Statement" data-toggle="tooltip"><i class="bx bxs-printer"></i></a>';
                                           // echo '<a href="reading.php?consumer_id='.$_GET["consumer_id"].'&id='. $row['id'] .'" title="Update Record" data-toggle="tooltip"><i class="bx bxs-pencil" ></i></a>';
                                            // echo '<a onclick="javascript:confirmationDelete($(this));return false;" href="delete-reading.php?consumer_id='.$_GET["consumer_id"].'&id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><i class="bx bxs-trash-alt" ></i></a>';
                                           // echo '<a href="#" class="deleteButton" title="Delete Record" data-toggle="tooltip" data-cid="'.$_GET["consumer_id"].'" data-id="'. $row['id'] .'"><i class="bx bxs-trash-alt"></i></a>';
                                            echo '<a href="#" class="confirmButton" title="Payment" data-toggle="tooltip" data-cid="'.$_GET["consumer_id"].'" data-id="'. $row['id'] .'"><i class="bx bx-money ml-3"></i></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            // echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
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
                        // echo '<div class="alert alert-danger"><em>Oops! Something went wrong. Please try again later.</em></div>';
                        echo '<script>
                        Swal.fire({
                        title: "Error!",
                        text: "Oops! Something went wrong. Please try again later.",
                        icon: "error",
                        toast: true,
                        position: "top-right",
                        showConfirmButton: false,
                        timer: 3000
                        })
                        </script>';
                    }

                    // Close connection
                    // mysqli_close($link);
                    ?>
                </div>
               
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
        <script>
    // Hide the alert after 3 seconds
    // setTimeout(function(){
    //     var alert = document.querySelector('.alert');
    //     if (alert) {
    //     alert.style.display = 'none';
    //     }
    // }, 3000);

const confirmButton = document.querySelectorAll('.confirmButton');
// console.log(viewButtons);
confirmButton.forEach(button => {
  button.addEventListener('click', function(e) {
    e.preventDefault();

    const consumer_id = this.dataset.cid;
    const id = this.dataset.id;

    console.log(`${consumer_id}:${id}`);

    Swal.fire({
      title: `Are you sure want to paid this?`,
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, do it!',
      cancelButtonText: 'No, cancel',
      allowOutsideClick: false, // Prevents closing when clicking outside the dialog box
      allowEscapeKey: false // Prevents closing when pressing the escape key
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirect to the desired page
        // window.location.href = `status-consumer.php?id=${id}&status=${status}`;
        window.location.href = `payment-reading.php?consumer_id=${consumer_id}&id=${id}`;
      }
    });
  });
});

    </script>
</body>
</html>