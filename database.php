<?php
$host = "localhost";
$user = "root";
$password = "Bindhu@2208"; // your MySQL password
$dbname = "Organization";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
