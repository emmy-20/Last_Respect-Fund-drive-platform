<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];

    $sql = "SELECT id FROM members WHERE phone='$phone' AND status='active'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $member_id = $row['id'];

        $sql = "INSERT INTO contributions (member_id, amount) VALUES ('$member_id', '$amount')";
        if ($conn->query($sql) === TRUE) {
            echo "Contribution recorded successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Member not found or deceased.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contribute</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>
    <h2>Make a Contribution</h2>
    <form method="post" action="contribute.php">
        Phone: <input type="text" name="phone" required><br>
        Amount: <input type="number" name="amount" required><br>
        <button type="submit">Contribute</button>
    </form>
</body>
</html>