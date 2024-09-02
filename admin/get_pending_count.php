<?php
header('Content-Type: application/json');
require_once "config.php";

// Fetch count of pending users
$pending_sql = "SELECT COUNT(*) as count FROM pending_users WHERE is_approved = 0";
$pending_result = mysqli_query($link, $pending_sql);

if ($pending_result) {
    $row = mysqli_fetch_assoc($pending_result);
    $pending_count = $row['count'];
} else {
    $pending_count = 0; // Default to 0 if there's an error
}

// Close connection
mysqli_close($link);

// Return the count as JSON
echo json_encode(['count' => $pending_count]);
?>
