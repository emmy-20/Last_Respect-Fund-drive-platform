<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $reported_by = $_POST['reported_by'];
    $date_of_death = $_POST['date_of_death'];

    $sql = "SELECT id FROM members WHERE phone='$phone' AND status='active'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $member_id = $row['id'];

        $sql = "INSERT INTO death_reports (member_id, date_of_death, reported_by) VALUES ('$member_id', '$date_of_death', '$reported_by')";
        $conn->query($sql);

        $sql = "UPDATE members SET status='deceased' WHERE id='$member_id'";
        $conn->query($sql);

        echo "Death reported successfully!";
    } else {
        echo "Member not found or already deceased.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report Death</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>
    <h2>Report a Death</h2>
    <form method="post" action="deaths.php">
        Phone: <input type="text" name="phone" required><br>
        Date of Death: <input type="date" name="date_of_death" required><br>
        Reported By: <input type="text" name="reported_by" required><br>
        <button type="submit">Report</button>
    </form>
</body>
</html>