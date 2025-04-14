<?php
session_start();
include 'db_connect.php';

// Only allow admins to access this page
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle the approval/rejection action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['report_id']) && isset($_POST['action'])) {
        $report_id = $_POST['report_id'];
        $action = $_POST['action'];

        if (in_array($action, ['approve', 'reject'])) {
            $stmt = $conn->prepare("UPDATE death_reports SET approval_status = ? WHERE id = ?");
            $stmt->bind_param("si", $action, $report_id);
            $stmt->execute();
            $stmt->close();
        }
    }
    // After action, reload the page to show updated results
    header("Location: admin_approval_dashboard.php");
    exit;
}

// Fetch all pending death reports for review
$result = $conn->query("SELECT * FROM death_reports WHERE approval_status = 'pending' ORDER BY report_date DESC");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Approval Dashboard</title>
    <style>
        .report-card {
            border: 1px solid #ccc;
            padding: 15px;
            margin: 10px 0;
            background-color: #f9f9f9;
        }
        .report-card img {
            max-width: 200px;
            margin-top: 10px;
        }
        button {
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
        }
        .approve-btn {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .reject-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h2>Pending Death Reports for Approval</h2>

<?php
// Display each pending report
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='report-card'>";
        if ($row['deceased_id']) {
            echo "<strong>Registered Member ID:</strong> {$row['deceased_id']}<br>";
        } else {
            echo "<strong>Deceased Name:</strong> {$row['deceased_name']}<br>";
        }
        echo "<strong>Reported by User ID:</strong> {$row['user_id']}<br>";
        echo "<strong>Relationship:</strong> {$row['relationship']}<br>";
        echo "<img src='{$row['photo']}' alt='Deceased Photo'><br>";
        echo "<form method='POST' action=''>
                <input type='hidden' name='report_id' value='{$row['id']}'>
                <button type='submit' name='action' value='approve' class='approve-btn'>Approve</button>
                <button type='submit' name='action' value='reject' class='reject-btn'>Reject</button>
              </form>";
        echo "</div>";
    }
} else {
    echo "<p>No pending death reports at the moment.</p>";
}
?>

</body>
</html>
