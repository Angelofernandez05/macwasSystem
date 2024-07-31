<?php
require_once "config.php";

$sql = "SELECT id FROM consumers";
$result = mysqli_query($link, $sql);
$data = array();

while($row = mysqli_fetch_assoc($result)){
    $consumer_id = $row['id'];

    // Fetch bill data for this consumer
    $bill_sql = "SELECT amount, DATE_FORMAT(date_paid, '%M') as month FROM readings WHERE consumer_id = $consumer_id AND status = 1 ORDER BY date_paid";
    $bill_result = mysqli_query($link, $bill_sql);

    $billData = array();
    $billMonths = array();

    while($bill_row = mysqli_fetch_assoc($bill_result)){
        $billData[] = (int)$bill_row['amount'];
        $billMonths[] = $bill_row['month'];
    }

    $data[] = array(
        'id' => $consumer_id,
        'billData' => $billData,
        'billMonths' => $billMonths
    );
}

echo json_encode($data);

// Close connection
mysqli_close($link);
?>
