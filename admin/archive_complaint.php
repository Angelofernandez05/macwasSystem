<?php
session_start();
require_once "config.php";

// Check if form is submitted and complaint_id is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complaint_id'])) {
    $complaint_id = mysqli_real_escape_string($link, $_POST['complaint_id']);

    // Perform SQL UPDATE to archive the complaint
    $archive_sql = "UPDATE complaints SET is_archived = 1 WHERE id = $complaint_id";

    if (mysqli_query($link, $archive_sql)) {
        // Redirect back to the complaints page or show a success message
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        // Handle errors
        echo "Error archiving complaint: " . mysqli_error($link);
    }

    mysqli_close($link);
} else {
    echo "Invalid request.";
}
?>
