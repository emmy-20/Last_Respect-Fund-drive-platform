<?php
session_start();
include 'db_connect.php';

$sql = "SELECT * FROM contributions";
$result = $conn->query($sql);

echo "<h2>Fund Contributions</h2>";
while ($row = $result->fetch_assoc()) {
    echo "Member ID: " . $row['member_id'] . " - Amount: " . $row['amount'] . "<br>";
}
?>