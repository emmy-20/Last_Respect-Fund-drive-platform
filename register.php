<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $nyumbakumi = $_POST['nyumbakumi'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $status = 'active'; // Default status

    if ($age < 18) {
        echo "Error: Age must be 18 or older.";
        exit();
    }

    $sql = "INSERT INTO users (name, nyumbakumi, age, phone, password, status) VALUES ('$name', '$nyumbakumi', '$age', '$phone', '$password', '$status')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>
    <h2>Register</h2>
    <form method="post" action="register.php">
        Name: <input type="text" name="name" required><br>
        Nyumba Kumi: <input type="text" name="nyumbakumi" required><br>
        Age: <input type="number" name="age" min="18" required><br>
        Phone: <input type="text" name="phone" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
