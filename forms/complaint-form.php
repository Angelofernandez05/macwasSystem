<?php
// Start output buffering
ob_start();

// Check if a session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include config file
require_once "config.php";

// Check if $link is a valid MySQLi object
if (!$link instanceof mysqli) {
    die("Database connection failed.");
}

// Define variables and initialize with empty values
$message = "";
$message_err = "";

$url = htmlspecialchars($_SERVER["PHP_SELF"]);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate message
    if (empty(trim($_POST["message"]))) {
        $message_err = "Please enter a message.";
    } else {
        $message = trim($_POST["message"]);
    }

    $consumer_id = $_SESSION["id"];

    // Check input errors before inserting in database
    if (empty($message_err)) {
        // Prepare an insert or update statement
        if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
            $sql = "UPDATE complaints SET message=?, consumer_id=? WHERE id=?";
        } else {
            $sql = "INSERT INTO complaints (message, consumer_id) VALUES (?, ?)";
        }

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
                $id = trim($_GET["id"]);
                mysqli_stmt_bind_param($stmt, "ssi", $message, $consumer_id, $id);
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $message, $consumer_id);
            }

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: complaint.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Failed to prepare the SQL statement.";
        }
    }
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $url = htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $_GET["id"];

        // Get URL parameter
        $id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM complaints WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use a while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $message = $row["message"];
                } else {
                    // URL doesn't contain a valid id. Redirect to error page
                    header("location: complaint.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Failed to prepare the SQL statement.";
        }
    }
}

// Close connection after all operations are completed
mysqli_close($link);

// End output buffering and flush all output
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaint Form</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
</head>
<body>
    <form action="<?php echo $url; ?>" method="post">
        <div class="form-group">
            <label>Message</label>
            <textarea rows="6" required name="message" class="form-control <?php echo (!empty($message_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($message); ?></textarea>
            <span class="invalid-feedback"><?php echo htmlspecialchars($message_err); ?></span>
        </div>
        <input type="submit" class="btn btn-block btn-primary" value="<?php echo isset($_GET["id"]) && !empty($_GET["id"]) ? 'Update' : 'Submit' ?>">
        <?php
        if (isset($_GET["id"]) && !empty($_GET["id"])) {
            ?>
            <a class="btn btn-link btn-block" href="complaint.php">Cancel</a>
            <?php
        }
        ?>
    </form>
</body>
</html>
