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
    <title>Settings</title>
    <?php include 'includes/links.php'; ?>
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

    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                Settings
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container-fluid py-5">
            <a href="new-minrate.php" class="btn btn-primary btn-sm mb-3"><i class='bx bx-plus' ></i> New</a>
            
            <?php
            // Include config file
            require_once "config.php";
            
            // Attempt select query execution
            $sql = "SELECT * FROM minimum_rates";
            if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-striped">';
                        echo "<thead>";
                            echo "<tr>";
                                // echo "<th>#</th>";
                                echo "<th>Type</th>";
                                echo "<th>Rate X</th>";
                                echo "<th>Rate Y</th>";
                                echo "<th>Rate Z</th>";
                                echo "<th>Action</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = mysqli_fetch_array($result)){

                            echo "<tr>";
                            if($row['type'] == 'Commercial'){
                                echo '<td><a class="text-danger" href="reading.php?consumer_id='. $row['id'] .'">'. $row['type'] .'</a></td>';
                            }else{
                                echo '<td><a class="text-success" href="reading.php?consumer_id='. $row['id'] .'">'. $row['type'] .'</a></td>';
                            }
                                echo "<td>" . $row['rate_x'] . "</td>";
                                echo "<td>" . $row['rate_y'] . "</td>";
                                echo "<td>" . $row['rate_z'] . "</td>";
                                echo "<td>";
                                    // echo '<a href="read.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                    echo '<a href="update-minrates.php?id='. $row['id'] .'" class="mr-2" title="Update Record" data-toggle="tooltip"><i class="bx bxs-pencil" ></i></a>';
                                    // echo '<a onclick="javascript:confirmationDelete($(this));return false;" href="delete-minrate.php?id='. $row['id'] .'" class="mr-2" title="Delete Record" data-toggle="tooltip"><i class="bx bxs-trash-alt" ></i></a>';
                                    echo '<a href="#" class="deleteButton" title="Delete Record" data-toggle="tooltip"><i class="bx bxs-trash-alt" data-id="'.$row['id'].'"></i></a>';
                                echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";                            
                    echo "</table>";
                    echo '</div>';
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
            mysqli_close($link);
            ?>
        </div>
    </section>

    <?php include 'includes/scripts.php'; ?>
    <script>
    // Hide the alert after 3 seconds
    setTimeout(function(){
        var alert = document.querySelector('.alert');
        if (alert) {
        alert.style.display = 'none';
        }
    }, 3000);

    const deleteButton = document.querySelectorAll('.deleteButton');
// console.log(viewButtons);
deleteButton.forEach(button => {
  button.addEventListener('click', function(e) {
    e.preventDefault();

    const id = this.dataset.id;

    Swal.fire({
      title: `Are you sure want to delete this record?`,
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
        window.location.href = `delete-minrate.php?id=${id}`;
      }
    });
  });
});

    </script>
</body>
</html>