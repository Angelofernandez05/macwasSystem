<?php
// Initialize the session
session_start();
 


require_once "config.php";
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Complaints</title>
    <?php include 'includes/links.php'; ?>
</head>
<body>
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
    </style>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light-gradient bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='color: black; cursor: pointer; font-size: 2rem'></i>
                Complaints
            </span>
            <?php include 'includes/userMenu.php'; ?>
        </nav>

        <div class="container-fluid py-5">
            <?php
                $new_com_sql = "SELECT *, complaints.id AS complaint_id FROM complaints LEFT JOIN consumers ON complaints.consumer_id = consumers.id WHERE is_resolved = 0 ORDER BY complaints.date ASC;";
                $new_com_result = mysqli_query($link, $new_com_sql);
                $new_com_total = mysqli_num_rows($new_com_result);

                $resolved_com_sql = "SELECT *, complaints.id AS complaint_id FROM complaints LEFT JOIN consumers ON complaints.consumer_id = consumers.id WHERE is_resolved = 1 ORDER BY complaints.date DESC;";
                $resolved_com_result = mysqli_query($link, $resolved_com_sql);
                $resolved_com_total = mysqli_num_rows($resolved_com_result);
                
                // Close connection
                mysqli_close($link);
            ?>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new" aria-selected="true">
                        New <span class="badge badge-primary"><?php echo $new_com_total; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="resolved-tab" data-toggle="tab" href="#resolved" role="tab" aria-controls="resolved" aria-selected="false">
                        Resolved <span class="badge badge-primary"><?php echo $resolved_com_total; ?></span>
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active py-3" id="new" role="tabpanel" aria-labelledby="new-tab">
                    <?php
                        if($new_com_total > 0){
                            echo '<div class="row">';
                            while($row = mysqli_fetch_array($new_com_result)){ include 'includes/complaint-list.php'; }
                            echo '</div>';
                        }
                    ?>
                </div>
                <div class="tab-pane fade py-3" id="resolved" role="tabpanel" aria-labelledby="resolved-tab">
                    <?php
                        if($resolved_com_total > 0){
                            echo '<div class="row">';
                            while($row = mysqli_fetch_array($resolved_com_result)){ include 'includes/complaint-list.php'; }
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>
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
    <?php include 'includes/scripts.php'; ?>
</body>
</html>