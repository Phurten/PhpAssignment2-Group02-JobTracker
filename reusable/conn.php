<?php
$host = "localhost";
$user = "root";
$pass = 'root'; 
$db = "jobtracker";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed");
}
?>
