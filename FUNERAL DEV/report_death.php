<?php
session_start();

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';

$success = '';
$error = '';

// Fetch all active members for dropdowns
$members = [];
$result = $conn->query("SELECT id, full_name FROM users WHERE status = 'active'");
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $is_registered = $_POST['is_registered'];
    $affected_user_id = $_POST['affected_user_id'];
    $date_of_death = $_POST['date_of_death'];

    if ($is_registered === 'yes') {
        $deceased_user_id = $_POST['deceased_user_id'];

        // Update status of deceased
        $stmt = $conn->prepare("UPDATE users SET status = 'deceased' WHERE id = ?");
        $stmt->bind_param("i", $deceased_user_id);
        $stmt->execute();
        $stmt->close();

        // Get full name of deceased
        $res = $conn->query("SELECT full_name FROM users WHERE id = $deceased_user_id");
        $deceased_name = $res->fetch_assoc()['full_name'];
        $relationship = null;
    } else {
        $deceased_user_id = NULL;
        $deceased_name = trim($_POST['deceased_name']);
        $relationship = trim($_POST['relationship']);
    }

    // Insert into deaths table
    $stmt = $conn->prepare("INSERT INTO deaths (deceased_user_id, deceased_name, affected_user_id, date_of_death, relationship)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiss", $deceased_user_id, $deceased_name, $affected_user_id, $date_of_death, $relationship);

    if ($stmt->execute()) {
        $death_id = $stmt->insert_id;

        // Create contribution records for all active members
        $contrib_members = $conn->query("SELECT id FROM users WHERE status = 'active'");
        $stmt_c = $conn->prepare("INSERT INTO contributions (user_id, death_id, status) VALUES (?, ?, 'pending')");
        while ($m = $contrib_members->fetch_assoc()) {
            $stmt_c->bind_param("ii", $m['id'], $death_id);
            $stmt_c->execute();
        }
        $stmt_c->close();

        $success = "Death reported successfully and contribution requests created.";
    } else {
        $error = "Error reporting death: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Report Death</title>
    <script>
        function toggleDeceasedInput(val) {
            if (val === 'yes') {
                document.getElementById('registered_user').style.display = 'block';
                document.getElementById('unregistered_input').style.display = 'none';
            } else {
                document.getElementById('registered_user').style.display = 'none';
                document.getElementById('unregistered_input').style.display = 'block';
            }
        }
    </script>
</head>
<body>
<?php include 'navbar.php'; ?>
    <h2>Report a Death</h2>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php elseif ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Is the deceased registered?</label><br>
        <select name="is_registered" onchange="toggleDeceasedInput(this.value)" required>
            <option value="">--Select--</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br><br>

        <div id="registered_user" style="display:none;">
            <label>Select Deceased Member:</label><br>
            <select name="deceased_user_id">
                <?php foreach ($members as $m): ?>
                    <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['full_name']); ?></option>
                <?php endforeach; ?>
            </select><br><br>
        </div>

        <div id="unregistered_input" style="display:none;">
        <label>Deceased Full Name:</label><br>
    <input type="text" name="deceased_name"><br><br>

    <label>Relationship to Affected Member:</label><br>
    <input type="text" name="relationship"><br><br>
        </div>

        <label>Affected Member:</label><br>
        <select name="affected_user_id" required>
            <?php foreach ($members as $m): ?>
                <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['full_name']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Date of Death:</label><br>
        <input type="date" name="date_of_death" required><br><br>

        <button type="submit">Submit Report</button>
    </form>
</body>
</html>
