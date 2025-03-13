<?php
require_once 'config/database.php';
require_once 'classes/Database.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    
    try {
        $stmt->execute([$username, $password, $email]);
        $_SESSION['message'] = "Registration successful!";
        header("Location: login.php");
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = "Registration failed. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    ?>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Register">
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
