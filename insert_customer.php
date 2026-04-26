<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['contact']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    
    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM customers WHERE email='$email'");
    
    if (mysqli_num_rows($check) > 0) {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Error</title>
            <style>
                body { font-family: Arial; background: linear-gradient(135deg, #d35400, #e67e22); height: 100vh; display: flex; justify-content: center; align-items: center; }
                .message { background: white; padding: 30px; border-radius: 10px; text-align: center; max-width: 400px; }
                .error { color: red; }
                a { background: #d35400; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; margin: 10px; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='message'>
                <h2 class='error'>❌ Email already registered!</h2>
                <p>This email is already in use. Please use a different email.</p>
                <a href='form.html'>Try Again</a>
                <a href='login.php'>Login</a>
            </div>
        </body>
        </html>";
        exit();
    }
    
    // Insert new customer with password
    $sql = "INSERT INTO customers (name, email, phone, dob, gender, password, created_at) 
            VALUES ('$name', '$email', '$phone', '$dob', '$gender', '$password', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Success</title>
            <style>
                body { font-family: Arial; background: linear-gradient(135deg, #d35400, #e67e22); height: 100vh; display: flex; justify-content: center; align-items: center; }
                .message { background: white; padding: 30px; border-radius: 10px; text-align: center; max-width: 400px; }
                .success { color: green; }
                a { background: #d35400; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; margin: 10px; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='message'>
                <h2 class='success'>✅ Registration Successful!</h2>
                <p>Thank you for registering with Iyer's Kitchen.</p>
                <a href='login.php'>Login Now</a>
                <a href='form.html'>Register Another</a>
            </div>
        </body>
        </html>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>