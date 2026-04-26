<?php
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Hardcoded check
    if ($username == 'admin' && $password == '1234') {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid login! Use admin/1234";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" value="admin"><br><br>
        <input type="password" name="password" placeholder="Password" value="1234"><br><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>