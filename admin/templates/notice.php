<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill</title>
    <?php include 'includes/links.php'; ?>
</head>
<body>
    <div class="container pt-5">
        <div style="max-width: 500px; margin: auto;">
            <div style="text-align: center">
                <img class="img-fluid" src="logo.png" alt="" width=150>
                <p style="margin: 0;" >MADRIDEJOS COMMUNITY WATERWORKS SYSTEM</p>
                <p class="text-uppercase text-center">
                    <small class="text-muted">MUNICIPALITY OF MADRIDEJOS</small><br />
                    <small class="text-muted">MADRIDEJOS, CEBU</small>
                </p>
                <h5>NOTICE OF DISCONNECTION</h5>
            </div>

            <div class="mt-3">
                <p style="margin: 0"><small class="text-muted mr-2">Name:</small><?php echo $row['name']; ?></p>
                <p style="margin: 0"><small class="text-muted mr-2">Address:</small><?php echo $row['barangay']; ?></p>
                <p style="margin: 0"><small class="text-muted mr-2">Meter No.:</small><?php echo $row['meter_num']; ?></p>
            </div>
            <div class="mt-3">
                <p><small class="text-muted mr-2">Remarks:</small>NO PAYMENT</p>
            </div>
            <div class="mt-3">
                <p class="font-weight-bold">Date of Disconnection: <?php echo date("F j, Y", strtotime($row['due_date'] ." +15 day") ); ?></p>
            </div>
            <div class="mt-3">
                <p>Please pay the above billing month/s before the disconnection date. Thank you</p>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
      window.onload = function() { window.print(); }
    </script>
</body>
</html>