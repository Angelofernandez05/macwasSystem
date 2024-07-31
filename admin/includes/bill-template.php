<div>
    <div class="text-center">
        <img class="img-fluid" src="logo.png" alt="" width=150>
        <p class="text-uppercase text-center mb-0">madridejos community waterworks system</p>
        <p class="text-uppercase text-center">
            <small class="text-muted">municipality of madridejos</small><br />
            <small class="text-muted">madridejos, cebu</small>
        </p>
    </div>
    <p class="text-right">Due Date: <?php echo date_format(date_create($reading_date), 'F'); ?> 25, <?php echo date_format(date_create($reading_date), 'Y'); ?></p>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="mb-0">Name</p>
                    <p class="mb-0">Address: </p>
                    <p class="mb-0">Account No.: </p>
                    <p class="mb-0">Registration No.: </p>
                    <p class="mb-0">Meter No.: </p>
                    <p class="mb-0">Type: </p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0"><?php echo $name; ?></p>
                    <p class="mb-0"><?php echo $barangay; ?></p>
                    <p class="mb-0"><?php echo $account_num; ?></p>
                    <p class="mb-0"><?php echo $registration_num; ?></p>
                    <p class="mb-0"><?php echo $meter_num; ?></p>
                    <p class="mb-0"><?php echo $type; ?></p>
                </div>
            </div>

            <div>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th class='text-center'>Date</th>
                            <th class='text-center'colspan='2'>Reading</th>
                            <th class='text-center'>Used (cu.m.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td></td><td class='text-center'>Present</td><td class='text-center'>Previous</td><td></td></tr>
                    <tr>
                        <td class='text-uppercase'><?php echo date_format(date_create($reading_date), 'F'); ?></td>
                        <td><?php echo $present; ?></td>
                        <td><?php echo $previous; ?></td>
                        <td><?php echo number_format((float)$row['used'], 2, '.', ''); ?></td>
                    </tr>
                    </tbody>                            
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <?php
                $rate_first_ten = $type === 'Commercial' ? 180 : 130;
                $rate_second_ten = $type === 'Commercial' ? 20 : 15;
                $rate = $type === 'Commercial' ? 25 : 18;

                $val1 = 0;
                $val2 = 0;
                $val3 = 0;
                
                if($row['used'] >= 20){
                    $val1 = number_format((float)($rate_first_ten), 2, '.', '');
                    $val2 = number_format((float)($rate_second_ten*10), 2, '.', '');
                    $val3 = number_format((float)($rate*($row['used'] - 20)), 2, '.', '');
                }else if($row['used'] >= 10){
                    $val1 = number_format((float)($rate_first_ten), 2, '.', '');
                    $val2 = number_format((float)($rate_second_ten*($row['used'] - 10)), 2, '.', '');
                }else{
                    $val1 = number_format((float)($rate_first_ten*$row['used']), 2, '.', '');
                }

                $total = number_format((float)($val1 + $val2 + $val3), 2, '.', '');
                
                echo '<div class="row">';
                    echo '<div class="col-md-4">First '.($row["used"] >= 10 ? 10 : $row["used"]).' cu.m.</div>';
                    echo '<div class="col-md-4">P'.$rate_first_ten.'.00</div>';
                    echo '<div class="col-md-4 text-right">'.$val1.'</div>';
                echo '</div>';
                if($val2 > 0){
                    echo '<div class="row">';
                        echo '<div class="col-md-4">'.number_format((float)($row['used'] >= 20 ? 10 : $row['used']-10), 2, '.', '').'</div>';
                        echo '<div class="col-md-4">P'.$rate_second_ten.'.00/cu.m.</div>';
                        echo '<div class="col-md-4 text-right">'.$val2.'</div>';
                    echo '</div>';
                }
                if($val3 > 0){
                    echo '<div class="row">';
                        echo '<div class="col-md-4">'.number_format((float)($row['used']-20), 2, '.', '').'</div>';
                        echo '<div class="col-md-4">P'.$rate.'.00/cu.m.</div>';
                        echo '<div class="col-md-4 text-right">'.$val3.'</div>';
                    echo '</div>';
                }
                echo '<div class="row mt-2">';
                    echo '<div class="col-md-8 text-uppercase"><strong>total consumption cost</strong></div>';
                    echo '<div class="col-md-4 text-right"><strong>'.$total.'</strong></div>';
                echo '</div>';
            ?>
        </div>
    </div>
    <p>Paying this bill after due date will be charge P20.00. Failure to pay (15) days after the due date is subject for disconnection without prior notice.</p>
    <p><strong>Note: Authorized collector of payments will be at MCC every 17th day of the month</strong></p>
</div>