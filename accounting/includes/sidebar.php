<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure you link to your existing stylesheet -->
    <style>
        /* Body Background Color */
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
        }

        .sidebar .nav-links li a:hover {
            background: gray; /* Hover effect for links */
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
                <a href="index.php">
                    <i class='bx bx-grid-alt' style="background: linear-gradient(45deg, #ff6f61, #f7b42c); -webkit-background-clip: text; color: green;"></i>
                    <span class="link_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="consumer.php">
                    <i class='bx bx-user-pin' style="background: linear-gradient(45deg, #ff6f61, #f7b42c); -webkit-background-clip: text; color: green;"></i>
                    <span class="link_name">Consumers</span>
                </a>
            </li>
            <li>
            </a>
                <ul class="sub-menu blank">
                <li><a class="link_name" href="reading.php">Bill</a></li>
                </ul>
            </li>
    </div>
</body>
</html>
