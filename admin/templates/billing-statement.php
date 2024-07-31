<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill</title>
    <style>
        .flex{
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div style="max-width: 500px; margin: auto;">
        <div style="text-align: center">
            <img class="img-fluid" src="logo.png" alt="" width=150>
            <p style="margin: 0;" >MADRIDEJOS COMMUNITY WATERWORKS SYSTEM</p>
            <p class="text-uppercase text-center">
                <small class="text-muted">MUNICIPALITY OF MADRIDEJOS</small><br />
                <small class="text-muted">MADRIDEJOS, CEBU</small>
            </p>
        </div>
        <p style="text-align: right;">Due Date: <?php echo date_format(date_create($row['due_date']), 'F j, Y'); ?></p>
        
        <div>
           <div class="row">
               <div class="col-md-6">
                   <p style="margin: 0">Name:</small><?php echo $row['name']; ?></p>
                   <p style="margin: 0">Address:</small><?php echo $row['barangay']; ?></p>
                   <p style="margin: 0">Account No:</small><?php echo $row['account_num']; ?></p>
                   <p style="margin: 0">Registration No:</small><?php echo $row['registration_num']; ?></p>
                   <p style="margin: 0">Meter No:</small><?php echo $row['meter_num']; ?></p>
                   <p style="margin: 0">Type:</small><?php echo $row['type']; ?></p>

                   <table style="margin-top: 1rem;">
                       <thead>
                           <tr>
                               <th class="text-center">Date</th>
                               <th class="text-center"colspan="2">Reading</th>
                               <th class="text-center">Used (cu.m.)</th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr><td></td><td class="text-center">Present</td><td class="text-center">Previous</td><td></td></tr>
                           <tr>
                               <td class="text-uppercase"><?php echo date_format(date_create($row['reading_date']), "F"); ?></td>
                               <td><?php echo $row['present']; ?></td>
                               <td><?php echo $row['previous']; ?></td>
                               <td><?php echo number_format((float)$row['used'], 2, '.', ''); ?></td>
                           </tr>
                       </tbody>
                   </table>
               </div>

               <div style="margin-top: 1rem;">
                   <div class="flex">
                       <div class="col-md-4">First <?php echo (int)$x; ?> cu.m.</div>
                       <div class="col-md-4">P<?php echo number_format($rate_x, 2, '.', ''); ?></div>
                       <div class="col-md-4 text-right"><?php echo number_format($x_value, 2, '.', ''); ?></div>
                   </div>
                    <?php
                    if($y_value > 0){
                        ?>
                       <div class="flex">
                           <div class="col-md-4"><?php echo $y; ?></div>
                           <div class="col-md-4">P<?php echo number_format($rate_y, 2, '.', ''); ?>/cu.m</div>
                           <div class="col-md-4 text-right"><?php echo number_format($y_value, 2, '.', ''); ?></div>
                       </div>
                       <?php
                    }
                    ?>
                    <?php
                    if($z_value > 0){
                        ?>
                       <div class="flex">
                           <div class="col-md-4"><?php echo (int)$z; ?></div>
                           <div class="col-md-4">P<?php echo number_format($rate_z, 2, '.', ''); ?>/cu.m</div>
                           <div class="col-md-4 text-right"><?php echo number_format($z_value, 2, '.', ''); ?></div>
                       </div>
                       <?php
                    }
                    ?>
                    <?php
                    if($over_due > 0){
                        ?>
                       <div class="flex">
                           <div class="col-md-9">Overdue:</div>
                           <div class="col-md-3 text-right"><?php echo number_format($over_due, 2, '.', ''); ?></div>
                       </div>
                       <?php
                    }
                    ?>
                   <div class="flex">
                       <div class="col-md-9">TOTAL CURRENT CHARGES:</div>
                       <div class="col-md-3 text-right"><?php echo number_format($total, 2, '.', ''); ?></div>
                   </div>
               </div>
           </div>
            
            <p>Paying this bill after due date will be charge P20.00. Failure to pay (15) days after the due date is subject for disconnection without prior notice.</p>
            <p><strong>Note: Authorized collector of payments will be at MCC every 17th day of the month</strong></p>
        </div>
    </div>
</body>
</html>