<?php
session_start();
include 'db_connect.php';
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch fines for the logged-in user
$query = "SELECT missed_contributions, fine_amount FROM fines WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$fine = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Fines</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>
    <main>
        <h2>My Fines</h2>
        <?php if ($fine): ?>
            <p>You have missed <strong><?php echo $fine['missed_contributions']; ?></strong> contributions.</p>
            <p>Total Fine Amount: <strong>KES <?php echo number_format($fine['fine_amount'], 2); ?></strong></p>
            <form action="pay_fine.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <button type="submit">Pay Fine via MPesa</button>
            </form>
        <?php else: ?>
            <p>You have no fines.</p>
        <?php endif; ?>
    </main>
</body>
</html>