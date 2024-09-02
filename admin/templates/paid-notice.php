<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill</title>
    <?php include 'includes/links.php'; ?>
    <?php
        $status = 'Pending';

        if($row['status'] == 1){
            $status = 'PAID';
        }else if($row['status'] == 2){
            $status = 'Waiting for approval';
        }
    ?>
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
                <h5>MUNICIPALITY OF MADRIDEJOS</h5>
                <h4>MADRIDEJOS, CEBU</h4>
                <h3>OFFICIAL RECEIPT</h3>
            </div>

            <div class="mt-3">
                <p style="margin: 0"><small class="text-muted mr-2">Name:</small><?php echo $row['name']; ?></p>
                <p style="margin: 0"><small class="text-muted mr-2">Meter No.:</small><?php echo $row['meter_num']; ?></p>
            </div>
            <div class="mt-3">
                <p><small class="text-muted mr-2">Remarks:</small><?php echo $status ?></p>
            </div>
            <div class="mt-3">
            <p class="font-weight-bold">Date of Due: <?php echo date("F j, Y", strtotime($row['due_date']) ); ?></p>
                <p class="font-weight-bold">Date of Paid: <?php echo date("F j, Y", strtotime($row['date_paid']) ); ?></p>
            </div>
            <div class="mt-3">
                <p>We've recieved your payment. Don't delete this message as it will serve as your official receipt</p>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
      window.onload = function() { window.print(); }
    </script>
</body>
</html>