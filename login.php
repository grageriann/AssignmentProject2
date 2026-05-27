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
        // Query the database exactly as it was built by database.sql
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            // DIRECT TEXT CHECK: Verifies your plain text input against the plain text string 'admin'
            if ($password === $user["password"]) {
                $_SESSION["authenticated"] = true;
                $_SESSION["manager_user"] = $user["username"];

                header("Location: manage.php");
                exit();
            } else {
                $error_msg = "Invalid username or password.";
            }
        } else {
            $error_msg = "Invalid username or password.";
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($conn);

// Header template configurations
$pageTitle = "Manager Login | G06 Agency";
$bodyId = "login-page";
include_once("header.inc");
?>

<main class="page-container">
    <div class="login-card" style="max-width: 400px; margin: 50px auto; padding: 25px; border: 1px solid #ccc; border-radius: 8px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2>HR Manager Gateway</h2>
        <?php if (!empty($error_msg)): ?>
            <p class="alert-error" style="color: #d32f2f; font-weight: bold; margin-bottom: 15px;"><?php echo htmlspecialchars($error_msg); ?></p>
        <?php endif; ?>
        
        <form action="login.php" method="post">
            <label for="username" style="display: block; margin-bottom: 5px; font-weight: bold;">Manager Username:</label>
            <input type="text" id="username" name="username" required style="width:100%; padding:8px; margin-bottom:15px; box-sizing: border-box;">
            
            <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Security Credentials:</label>
            <input type="password" id="password" name="password" required style="width:100%; padding:8px; margin-bottom:15px; box-sizing: border-box;">
            
            <input type="submit" value="Authenticate Session" style="width:100%; background:#1a73e8; color:white; border:none; padding:10px; cursor:pointer; font-weight:bold; border-radius:4px;">
        </form>
        <p style="text-align: center; margin-top: 15px;"><a href="index.php">Return Home</a></p>
    </div>
</main>

<?php include_once("footer.inc"); ?>