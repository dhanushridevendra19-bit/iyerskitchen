<?php
$conn = mysqli_connect("localhost", "root", "", "iyerskitchen");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>