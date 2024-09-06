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
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name = $row["name"];
                $barangay = $row["barangay"];
                $account_num = $row["account_num"];
                $registration_num = $row["registration_num"];
                $meter_num = $row["meter_num"];
                $type = $row["type"];
            } else{
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
    <title>Accounting Bill</title>
    <?php include 'includes/links.php'; ?>
    <style>
    .table-custom {
        width: 100%; /* Full width */
        table-layout: auto; /* Columns adjust based on content */
        margin: 0 auto;
        max-width: 100%; /* Ensure the table uses the full width */
    }

    .table-custom th, .table-custom td {
        text-align: center; /* Center align text */
        padding: 10px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal; /* Allow text wrapping */
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

    /* Remove unnecessary margin on the table */
    .container-fluid {
        padding-left: 0;
        padding-right: 0;
    }

    .table-custom img {
        max-width: 100px; /* Limit image size to fit within the cell */
        height: auto;
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
                
                    <?php
                    $idn = "";
                    $id = $_GET["consumer_id"];
                    $sql = "SELECT *, (present - previous) as used FROM readings WHERE consumer_id = $id ";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-striped table-custom">';
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
                                        echo '<img src="../uploads/'.$row["screenshot"] .'">';
                                        } ?>
                                        
                                    </td>
                                        <?php
                                        echo "<td>" . $status . "</td>";
                                        echo "<td class='d-flex align-items-center' style='gap: 0.3rem'>";
                                            echo '<a href="#" class="confirmButton" title="Payment" data-toggle="tooltip" data-cid="'.$_GET["consumer_id"].'" data-id="'. $row['id'] .'"><i class="bx bx-money btn btn-danger btn-sm mb-3"></i></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            echo '</div>'; // Close table-responsive
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
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
        <script>
    const confirmButton = document.querySelectorAll('.confirmButton');
    confirmButton.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const consumer_id = this.dataset.cid;
        const id = this.dataset.id;

        Swal.fire({
          title: `Are you sure want to pay this?`,
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, do it!',
          cancelButtonText: 'No, cancel',
          allowOutsideClick: false,
          allowEscapeKey: false
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = `payment-reading.php?consumer_id=${consumer_id}&id=${id}`;
          }
        });
      });
    });
    </script>
</body>
</html>
