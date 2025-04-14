<?php
session_start();
require_once 'db_connect.php';

// Only members can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

$member_id = $_SESSION['user_id'];

// Get notifications for the member
$sql = "SELECT message, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Welcome, <?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Member'; ?>!</h2>

    <h3>Your Notifications</h3>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>
                    <p><strong>Notification:</strong> {$row['message']}</p>
                    <p><em>Received on: {$row['created_at']}</em></p>
                    <hr>
                </div>";
        }
    } else {
        echo "<p>No new notifications.</p>";
    }
    ?>

</body>
</html>
