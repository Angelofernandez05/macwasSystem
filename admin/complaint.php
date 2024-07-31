<?php
// Initialize the session
session_start();
 


require_once "config.php";
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaints</title>
    <?php include 'includes/links.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <section class="home-section">
        <nav class="navbar navbar-light bg-white border-bottom">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
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

    <?php include 'includes/scripts.php'; ?>
</body>
</html>