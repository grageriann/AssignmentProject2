<?php
session_start();
require_once("settings.php");

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($username) || empty($password)) {
        $error_msg = "Please enter both username and password.";
    } else {
        $query = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION["authenticated"] = true;
            $_SESSION["manager_user"] = $user["username"];
            header("Location: manage.php");
            exit();
        } else {
            $error_msg = "Invalid username or password.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-card { max-width: 400px; margin: 100px auto; padding: 25px; border: 1px solid #ccc; border-radius: 8px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .alert-error { color: #d32f2f; font-weight: bold; margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
    </style>
</head>
<body>
<main>
    <div class="login-card">
        <h2>HR Manager Gateway</h2>
        <?php if (!empty($error_msg)): ?>
            <p class="alert-error"><?php echo htmlspecialchars($error_msg); ?></p>
        <?php endif; ?>
        
        <form action="login.php" method="post">
            <label for="username">Manager Username:</label>
            <input type="text" id="username" name="username" required style="width:100%; padding:8px; margin-bottom:15px; box-sizing: border-box;">
            
            <label for="password">Security Credentials:</label>
            <input type="password" id="password" name="password" required style="width:100%; padding:8px; margin-bottom:15px; box-sizing: border-box;">
            
            <input type="submit" value="Authenticate Session" style="width:100%; background:#1a73e8; color:white; border:none; padding:10px; cursor:pointer; font-weight:bold; border-radius:4px;">
        </form>
        <p style="text-align: center; margin-top: 15px;"><a href="index.php">Return Home</a></p>
    </div>
</main>
</body>
</html>