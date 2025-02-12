<?php
session_start();
include 'db_connect.php'; // Connect to the database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last_Respect Fund-Drive Platform</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <?php include 'navbar.php'; ?> <!-- Include the navigation bar -->

    <header>
        <h1>Welcome to the Last_Respect Fund-Drive Platform</h1>
    </header>

    <main>
        <section class="intro">
            <h2>Ensuring Community Support for Bereaved Families</h2>
            <p>This system helps the village keep track of contributions, manage registrations, and support bereaved families efficiently.</p>
        </section>

        <?php
        // Display a welcome message if the user is logged in
        if (isset($_SESSION['user_id'])) {
            echo "<p>Welcome, <strong>" . $_SESSION['user_name'] . "</strong>!</p>";
            echo "<p><a href='contribute.php'>Make a Contribution</a></p>";
        } else {
            echo "<p><a href='login.php'>Login</a> to access full features.</p>";
        }
        ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Village Contribution System</p>
    </footer>

</body>
</html>