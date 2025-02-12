<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    
    if ($_POST['action'] == 'signup') {
        $name = $_POST['name'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (name, phone, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $hashed_password);
        $success = $stmt->execute() ? "Signup successful! You can now log in." : "Signup failed. Phone number may already be registered.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $user_name, $hashed_password);
        $stmt->fetch();
        
        if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid phone number or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login & Signup</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>
    <form method="post">
        <input type="hidden" name="action" value="login">
        <label>Phone:</label>
        <input type="text" name="phone" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>

    <h2>Signup</h2>
    <form method="post">
        <input type="hidden" name="action" value="signup">
        <label>Name:</label>
        <input type="text" name="name" required>
        <label>Phone:</label>
        <input type="text" name="phone" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Signup</button>
    </form>
</body>
</html>
