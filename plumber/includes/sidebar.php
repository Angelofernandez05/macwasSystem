<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure you link to your existing stylesheet -->
    <style>
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
                <a href="complaint.php" class="position-relative">
                    <i class='bx bx-message-rounded-dots' style="background: linear-gradient(45deg, #ff6f61, #f7b42c); -webkit-background-clip: text; color: green;"></i>
                    <span id="complaint-notification-badge" class="badge badge-danger position-absolute top-0 start-100 translate-middle"
                          style="transform: translate(130%, -50%); font-size: 0.60rem;">
                        <!-- Complaint badge count will be updated by JavaScript -->
                    </span>
                    <span class="link_name">Complaints</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Include JavaScript at the end of the body -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        function updateNotificationBadge(endpoint, badgeId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', endpoint, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var badge = document.getElementById(badgeId);
                    badge.textContent = response.count > 0 ? response.count : '';
                    badge.style.display = response.count > 0 ? 'inline-block' : 'none';
                }
            };
            xhr.send();
        }   
        // Update complaints badge
        updateNotificationBadge('get_complaint_count.php', 'complaint-notification-badge');
    }); 
    </script>
</body>
</html>
