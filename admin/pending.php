<?php
    // Initialize the session
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    require_once "config.php";

    // Fetch pending users
    $pending_sql = "SELECT id, name, email, phone, barangay, account_num, registration_num, meter_num, type FROM pending_users";
    $pending_result = mysqli_query($link, $pending_sql);

    // Count pending users
    $pending_count = mysqli_num_rows($pending_result);

    // Close connection
    mysqli_close($link);
    ?>      

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Pending Consumers</title>
        <?php include 'includes/links.php'; ?>
        <link rel="icon" href="logo.png" type="image/icon type">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <style>
            body {
                background: linear-gradient(135deg, #e0eafc, #cfdef3);
            }
            .navbar-light-gradient {
                background: linear-gradient(135deg, #36d1dc, #5b86e5);
                color: white;
                border-bottom: 2px solid black !important;
                margin-left: 10px;
            }
            .main-content {
                margin-left: 250px; /* Adjust this based on the sidebar width */
            }
            .table-container {
                margin: 20px auto;
                max-width: 1200px;
            }
            .action-buttons {
                display: flex;
                gap: 5px;
            }
            .action-buttons button {
                margin: 0; /* Remove any default margin */
                flex: none; /* Prevent buttons from stretching */
            }
            .table-container {
                margin: 20px auto;
                max-width: 1500px;
            }
            .table {
                width: 100%;
                border-collapse: collapse; /* Ensures borders between cells are merged */
            }
            .table th, .table td {
                border: 1px solid #dee2e6; /* Light gray border color for table cells */
                padding: 8px; /* Add padding to table cells */
            }
            .table thead th {
                background-color: #f8f9fa; /* Light background color for table header */
                border-bottom: 4px solid #dee2e6; /* Slightly thicker border for header bottom */
            }
            .table tbody tr:nth-child(even) {
                background-color: #f2f2f2; /* Light gray background for even rows */
            }
            .table tbody tr:hover {
                background-color: #e9ecef; /* Slightly darker background on hover */
            }
            .table-container h2 {
                margin-bottom: 20px;
            }
            .sidebar .badge {
                font-size: 0.75rem; /* Smaller font size for the badge */
                padding: 1px 4px; /* Adjust padding */
            }       

        </style>
    </head>
    <body>
        <?php include 'includes/sidebar.php'; ?>

        <section class="home-section">
            <nav class="navbar navbar-light-gradient bg-white border-bottom">
                <span class="navbar-brand mb-0 h1 d-flex align-items-center">
                    <i class='bx bx-menu mr-3' style='cursor: pointer; font-size: 2rem'></i>
                        Pending Consumer
                </span>
                <?php include 'includes/userMenu.php'; ?>
            </nav>

            <div class="container-fluid py-5">
                <div class="table-container">
                    <h2 class="text-center">Pending Consumers</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Barangay</th>
                                <th>Account No.</th>
                                <th>Registration No.</th>
                                <th>Meter No.</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="pendingUsersTable">
                            <?php if ($pending_count > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($pending_result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($row['barangay']); ?></td>
                                        <td><?php echo htmlspecialchars($row['account_num']); ?></td>
                                        <td><?php echo htmlspecialchars($row['registration_num']); ?></td>
                                        <td><?php echo htmlspecialchars($row['meter_num']); ?></td>
                                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                                        <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-success btn-sm" onclick="acceptUser(<?php echo $row['id']; ?>)">Accept</button>
                                            <button class="btn btn-danger btn-sm" onclick="declineUser(<?php echo $row['id']; ?>)">Decline</button>
                                        </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">No pending users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <?php include 'includes/scripts.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function acceptUser(userId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to accept this user.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, accept it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `process_user.php?action=accept&id=${userId}`;
                    }
                });
            }

            function declineUser(userId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to decline this user.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#28a745',
                    confirmButtonText: 'Yes, decline it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `process_user.php?action=decline&id=${userId}`;
                    }
                });
            }
        </script>
    </body>
    </html>
