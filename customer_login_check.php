<?php
session_start();
include("db.php");

if (isset($_POST['customer_login'])) {
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM customers WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['customer'] = $row['id'];
            $_SESSION['customer_name'] = $row['name'];
            
            header("Location: index.php");
            exit();
        } else {
            header("Location: login.php?error=wrongpassword");
            exit();
        }
    } else {
        header("Location: login.php?error=notfound");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>