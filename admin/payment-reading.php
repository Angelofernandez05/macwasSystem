<?php
// Process payment operation after confirmation
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $consumer_id = $_GET['consumer_id'];
    require_once "config.php";

    // Prepare an update statement
    $sql = "UPDATE readings SET status=?, date_paid=? WHERE id=?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $param_status, $param_date_paid, $param_id);

        // Set parameters
        $param_status = 1; // Set status to PAID
        $param_date_paid = date('Y-m-d H:i:s'); // Set the current date
        $param_id = $_GET["id"];

        if (mysqli_stmt_execute($stmt)) {
            // Payment successful. Show success message and redirect
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Payment Success</title>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        title: "Success!",
                        text: "Payment recorded successfully.",
                        icon: "success",
                        toast: true,
                        position: "center",
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.href = "reading.php?consumer_id=' . $consumer_id . '";
                    });
                </script>
            </body>
            </html>';
            exit();
        } else {
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Error</title>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        title: "Error!",
                        text: "Oops! Something went wrong. Please try again later.",
                        icon: "error",
                        toast: true,
                        position: "top-right",
                        showConfirmButton: false,
                        timer: 3000
                    });
                </script>
            </body>
            </html>';
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
} else {
    header("location: reading.php?consumer_id=$consumer_id");
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Error</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: "Error!",
                text: "URL doesn\'t contain ID parameter.",
                icon: "error",
                toast: true,
                position: "top-right",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    </body>
    </html>';
    exit();
}
?>
