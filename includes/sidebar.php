<div class="sidebar bg-light border-right">
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
                <a href="index.php">
                    <i class='bx bx-grid-alt' ></i>
                    <span class="link_name">Dashboard</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="index.php">Dashboard</a></li>
                </ul>
            </li>
            <li>
                <a href="reading.php">
                <i class='bx bxs-file'></i>
                <div class="d-inline-flex align-items-center">
                <span class="link_name">Bill</span>
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
                        echo '<span class="translate-middle badge rounded-pill badge-danger ml-2">' . $num . '</span>';
                    } else {
                        // Handle the case when $num is 0
                    }
                }

                ?>
                </div>
                </a>
                <ul class="sub-menu blank">
                <li><a class="link_name" href="reading.php">Bill</a></li>
                </ul>
            </li>

            <li>
                <a href="complaint.php">
                    <i class='bx bx-message-rounded-dots'></i>
                    <span class="link_name">Complaints</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="complaint.php">Complaints</a></li>
                </ul>
            </li>

        </ul>
    </div>