<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure you link to your existing stylesheet -->
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
        }

        .sidebar .nav-links li a:hover {
            background: gray; /* Hover effect for links */
        }

        /* Add styling for the icon container and badge positioning */
        .icon-container {
            position: relative;
            display: inline-block;
        }
       .badge {
            position: absolute;
            top: 7px;  /* Adjust vertical position */
            right: 45px; /* Adjust horizontal position */
            background-color: #dc3545; /* Badge color */
            color: white;
            padding: 3px 6px;
            border-radius: 50%;
            font-size: 8px;
            
        }
    </style>
</head>
<body>
    <div class="sidebar bg-light-gradient border-right close">
        <div class="m-auto">
            <img class="img-fluid" src="logo.png" alt="">
            <p class="text-uppercase text-center mb-0">madridejos community waterworks system</p>
            <p class="text-uppercase text-center">
                <small class="text-muted">municipality of madridejos</small><br />
                <small class="text-muted">madridejos, cebu</small>
            </p>
        </div>

        <ul class="nav-links">
            <li>
                <a href="index">
                    <i class='bx bx-grid-alt' style="background: linear-gradient(45deg, #ff6f61, #f7b42c); -webkit-background-clip: text; color: green;"></i>   
                    <span class="link_name">Dashboard</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="index"></a></li>
                </ul>
            </li>
            <li>
                <a href="reading.php">
                    <!-- Icon container for the Bill icon and badge -->
                    <div class="icon-container">
                        <i class='bx bxs-file' style="background: linear-gradient(45deg, #ff6f61, #f7b42c); -webkit-background-clip: text; color: green;"></i>
                        
                        <!-- PHP logic for the badge -->
                        <?php
                        // Include config file
                        require_once "config.php";
                        
                        $id = $_SESSION["id"];
                        $pending = 0;
                        $sql = "SELECT *, (present - previous) as used FROM readings WHERE consumer_id = $id and status = $pending";
                        $result = mysqli_query($link, $sql);

                        if (!$result) {
                            // Query execution failed, handle the error
                            echo "Error executing query: " . mysqli_error($link);
                        } else {
                            $num = mysqli_num_rows($result);
                            if ($num > 0) {
                                echo '<span class="badge">' . $num . '</span>';
                            }
                        }
                        ?>
                    </div>

                    <span class="link_name">Bill</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="reading.php"></a></li>
                </ul>
            </li>

            <li>
                <a href="complaint.php">
                    <i class='bx bx-message-rounded-dots' style="background: linear-gradient(45deg, #ff6f61, #f7b42c); -webkit-background-clip: text; color: green;"></i>
                    <span class="link_name">Complaints</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="complaint.php"></a></li>
                </ul>
            </li>
        </ul>
    </div>
</body>
</html>
