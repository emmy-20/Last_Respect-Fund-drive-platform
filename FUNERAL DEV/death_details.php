<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['death_id'])) {
    echo "No death ID provided.";
    exit;
}

$death_id = $_GET['death_id'];

$stmt = $conn->prepare("
    SELECT 
        d.deceased_name,
        d.date_of_death,
        d.deadline,
        du.full_name AS deceased_member_name,
        au.full_name AS affected_member_name,
        au.id_number AS affected_id_number,
        au.phone_number AS affected_phone
    FROM deaths d
    LEFT JOIN users du ON d.deceased_user_id = du.id
    LEFT JOIN users au ON d.affected_user_id = au.id
    WHERE d.id = ?
");
$stmt->bind_param("i", $death_id);
$stmt->execute();
$result = $stmt->get_result();
$death = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Death Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<h2>Death Details</h2>

<?php if ($death): ?>
    <div class="death-details-card">
        <div class="death-info">
            <p><strong>Deceased Name:</strong>
                <?php 
                    if (!empty($death['deceased_member_name'])) {
                        echo htmlspecialchars($death['deceased_member_name']) . " (Registered Member)";
                    } else {
                        echo htmlspecialchars($death['deceased_name']) . " (Not Registered)";
                    }
                ?>
            </p>

            <p><strong>Date of Death:</strong> <?= htmlspecialchars($death['date_of_death']); ?></p>
            <p><strong>Contribution Deadline:</strong> <?= htmlspecialchars($death['deadline']); ?></p>
        </div>

        <div class="affected-member">
            <p><strong>Affected Member:</strong></p>
            <?php if (!empty($death['affected_member_name'])): ?>
                <ul>
                    <li><strong>Name:</strong> <?= htmlspecialchars($death['affected_member_name']); ?></li>
                    <li><strong>ID Number:</strong> <?= htmlspecialchars($death['affected_id_number']); ?></li>
                    <li><strong>Phone Number:</strong> <?= htmlspecialchars($death['affected_phone']); ?></li>
                </ul>
            <?php else: ?>
                <p>Unknown</p>
            <?php endif; ?>
        </div>

        <div class="contribute-action">
            <a href="contribute.php?death_id=<?= $death_id; ?>" class="btn">Contribute Now</a>
        </div>
    </div>
<?php else: ?>
    <p>No information found for this record.</p>
<?php endif; ?>

