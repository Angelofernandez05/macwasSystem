<?php
include 'config.php';

// Fetch pending users with all relevant fields
$sql = "SELECT name, email, phone, barangay, account_num, registration_num, meter_num, type, id FROM pending_users";
$result = $link->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<tr>
            <td>' . htmlspecialchars($row['name']) . '</td>
            <td>' . htmlspecialchars($row['email']) . '</td>
            <td>' . htmlspecialchars($row['phone']) . '</td>
            <td>' . htmlspecialchars($row['barangay']) . '</td>
            <td>' . htmlspecialchars($row['account_num']) . '</td>
            <td>' . htmlspecialchars($row['registration_num']) . '</td>
            <td>' . htmlspecialchars($row['meter_num']) . '</td>
            <td>' . htmlspecialchars($row['type']) . '</td>
            <td>
                <button class="btn btn-success" onclick="acceptUser(' . $row['id'] . ')">Accept</button>
                <button class="btn btn-danger" onclick="declineUser(' . $row['id'] . ')">Decline</button>
            </td>
        </tr>';
    }
} else {
    echo '<tr><td colspan="9">No pending users found.</td></tr>';
}

$link->close();
?>
