<?php
include 'config.php';

if (isset($_GET['action']) && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $action = $_GET['action'];
    $userId = $_GET['id'];

    if ($action === 'accept') {
        // Fetch user details
        $sql = "SELECT id, name, email, phone, barangay, account_num, registration_num, meter_num, type, password FROM pending_users WHERE id = ?";
        $stmt = $link->prepare($sql);
        
        if (!$stmt) {
            die('Prepare failed: ' . htmlspecialchars($link->error));
        }
        
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user) {
            // Hash the password before inserting into the consumers table
            $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);

            // Insert into consumers table
            $sql = "INSERT INTO consumers (name, email, phone, account_num, registration_num, meter_num, type, barangay, password, registration_date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $link->prepare($sql);

            if (!$stmt) {
                die('Prepare failed: ' . htmlspecialchars($link->error));
            }
            
            $stmt->bind_param('sssssssss', $user['name'], $user['email'], $user['phone'], $user['account_num'], 
                                          $user['registration_num'], $user['meter_num'], $user['type'], $user['barangay'], $hashed_password);
            $stmt->execute();
            $stmt->close();

            // Delete from pending_users table
            $sql = "DELETE FROM pending_users WHERE id = ?";
            $stmt = $link->prepare($sql);
            
            if (!$stmt) {
                die('Prepare failed: ' . htmlspecialchars($link->error));
            }
            
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->close();

            // Success notification
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Success</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
            </head>
            <body>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        title: "Success!",
                        text: "User has been accepted successfully.",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "pending.php";
                        }
                    });
                </script>
            </body>
            </html>';
            exit;
        } else {
            // User not found notification
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Error</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
            </head>
            <body>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        title: "Error!",
                        text: "User not found.",
                        icon: "error",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "pending.php";
                        }
                    });
                </script>
            </body>
            </html>';
            exit;
        }
    } elseif ($action === 'decline') {
        // Delete from pending_users table
        $sql = "DELETE FROM pending_users WHERE id = ?";
        $stmt = $link->prepare($sql);
        
        if (!$stmt) {
            die('Prepare failed: ' . htmlspecialchars($link->error));
        }
        
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();

        // Success notification
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Success</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        </head>
        <body>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    title: "Success!",
                    text: "User has been declined successfully.",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "pending.php";
                    }
                });
            </script>
        </body>
        </html>';
        exit;
    }
}

$link->close();
?>
