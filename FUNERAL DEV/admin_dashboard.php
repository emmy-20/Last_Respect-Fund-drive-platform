<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css"> 
</head>
<body>
<?php include 'navbar.php'; ?>
    <h2>Admin Dashboard</h2>

    <div class="dashboard-container">
        <div class="dashboard-box"><a href="register_member.php">Register Member</a></div>
        <div class="dashboard-box"><a href="report_death.php">Report Death</a></div>
        <div class="dashboard-box"><a href="admin_approval.php">Approve Death Reports</a></div>
        <div class="dashboard-box"><a href="manage_members.php">Manage Members</a></div>
        <div class="dashboard-box"><a href="view_contributions.php">View Contributions</a></div>
        <div class="dashboard-box"><a href="add_notification.php">Add Notification</a></div>
    </div>
</body>
</html>
