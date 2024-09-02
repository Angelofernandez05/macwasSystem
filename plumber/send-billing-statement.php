<?php
// Database connection
require_once "config.php";

// Check if an ID is provided in the URL
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);
    
    // Adjusted SQL query to match your table structure
    $sql = "SELECT consumers.name, consumers.barangay, consumers.account_num, readings.reading_date, readings.current_reading 
            FROM consumers 
            LEFT JOIN readings ON consumers.id = readings.consumer_id 
            WHERE consumers.id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $id;

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                // Data is fetched and stored in $row for use later in the HTML below
            } else {
                echo "No records matching your query were found.";
            }
        } else {
            echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
    }
} else {
    echo "ID parameter is missing or empty."; // Debugging output
    header("location: consumer.php");
    exit();
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Order</title>
    <?php include 'includes/links.php'; ?>
    <style>
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .mb-0 {
            margin-bottom: 0;
        }
        .pt-5 {
            padding-top: 5rem;
        }
        .pt-4 {
            padding-top: 4rem;
        }
        .pt-1 {
            padding-top: 1rem;
        }
        .font-size-lg {
            font-size: 18px;
        }
        .font-weight-bold {
            font-weight: bold;
        }
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
        }
        .checkbox-group div {
            flex: 1 0 30%;
            padding: 5px;
        }
        .signature-section {
            margin-top: 30px;
        }
        .signature-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .signature-item label {
            flex: 1;
            font-size: 16px; /* Adjust as needed */
            margin-right: 10px;
            white-space: nowrap;
        }
        .signature-item input {
            border: none;
            border-bottom: 1px solid #000; /* Line style under the text */
            width: 100%; /* Full width to align with label */
            box-sizing: border-box;
            padding: 5px 0;
            margin: 0;
            font-size: 16px; /* Adjust font size to match the design */
        }
        .signature-item input:focus {
            outline: none; /* Remove the default outline when focused */
        }

        /* Font size for section headers */
        .signature-section-header {
            font-size: 20px; /* Adjust as needed */
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <div class="container pt-5">
        <div class="text-center">
            <img class="img-fluid" src="logo.png" alt="" width=170>
            <p class="text-uppercase text-center mb-0"><strong>madridejos community waterworks system</strong></p>
            <p class="text-uppercase text-center">
            <label for="date-finished"><strong>JOB ORDER</strong></label>
            </p>
        </div>
        <p class="text-right">Date: <?php echo date_format(date_create($row['due_date'] ?? ''), 'F j, Y'); ?></p>
    
        <div>
            <div class="row">
                <div class="col-md-6">
                    <p class="font-size-lg mb-0"><small class="text-muted">Consumer:</small> <?php echo htmlspecialchars($row['name'] ?? ''); ?></p>
                    <p class="font-size-lg mb-0"><small class="text-muted">Address:</small> <?php echo htmlspecialchars($row['barangay'] ?? ''); ?></p>
                </div>
                <div class="col-md-6">
                    <p class="font-size-lg mb-0"><small class="text-muted">TAPSTAND:</small></p>
                    <p class="font-size-lg mb-0"><small class="text-muted">METER NO./READING:</small> <?php echo htmlspecialchars($row['meter_num'] ?? ''); ?></p>
                </div>
            </div>
        </div>

        <!-- Combined Nature of Work -->
        <div class="pt-4">
            <p class="font-size-lg mb-2">
            <label for="date-finished">Nature of Work:</label>
            </p>
            <div class="checkbox-group">
                <div><input type="checkbox" /> Installation</div>
                <div><input type="checkbox" /> Change/Repairs Meter</div>
                <div><input type="checkbox" /> Reconnection</div>
                <div><input type="checkbox" /> Billing Concerns</div>
                <div><input type="checkbox" /> Pipe Leaking</div>
                <div><input type="checkbox" /> Disconnection</div>
                <div><input type="checkbox" /> Others</div>
            </div>
        </div>

        <!-- Consumer Section -->
        <div class="signature-section">
            <label for="consumer-signature">CONSUMER</label>
            <div class="signature-item">
                <label for="consumer-signature">SIGNATURE:</label>
                <div class="line"></div>
            </div>
        </div>
        
        <div class="signature-section">
            <div class="row">
                <div class="col-md-6 signature-item">
                    <label for="worked-by">Worked By:</label>
                    <div class="line"></div>
                </div>
                <div class="col-md-6 signature-item">
                    <label for="approved-by">Approved:</label>
                    <div class="line"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 signature-item">
                    <label for="date-worked">Date Worked:</label>
                    <div class="line"></div>
                </div>
                <div class="col-md-6 signature-item">
                    <label for="date-finished">Date/Time Finished:</label>
                    <div class="line"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 signature-item">
                    <label for="remarks">Remarks:</label>
                    <div class="line"></div>
                </div>
            </div>
        </div>

        <!-- Print Script -->
        <script type="text/javascript">
            window.onload = function() {
                window.print();
            };
        </script>
    </div>
</body>
</html>
