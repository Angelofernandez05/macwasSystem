<?php
header('Content-Type: application/json');
require_once "config.php";

// Fetch count of complaints
$complaint_sql = "SELECT COUNT(*) as count FROM complaints WHERE is_resolved = 0";
$complaint_result = mysqli_query($link, $complaint_sql);

if ($complaint_result) {
    $row = mysqli_fetch_assoc($complaint_result);
    $complaint_count = $row['count'];
} else {
    $complaint_count = 0; // Default to 0 if there's an error
}

// Close connection
mysqli_close($link);

// Return the count as JSON
echo json_encode(['count' => $complaint_count]);
?>
