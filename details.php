<?php
include('database.php');

if (!isset($_GET['id']) || trim($_GET['id']) === '') die("Invalid request.");

$id = $_GET['id'];
$sql = "SELECT * FROM employee WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) die("Employee not found.");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Employee Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<!-- <nav class="navi-container">
    <div class="orga-name">
        <marquee behavior="scroll" direction="left">NRSC Task1</marquee>
    </div>
    <ul class="menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="form.php">Form</a></li>
        <li><a href="view.php">View</a></li>
    </ul>
</nav> -->

<div class="container">
    <h2>Employee Details</h2>

    <?php if (!empty($row['picture'])): ?>
        <?php $img = base64_encode($row['picture']); ?>
        <img class="profile" src="data:image/*;base64,<?= $img ?>" alt="Profile">
    <?php endif; ?>

    <p><strong>ID:</strong> <?=htmlspecialchars($row['id'])?></p>
    <p><strong>Name:</strong> <?=htmlspecialchars($row['name'])?></p>
    <p><strong>Age:</strong> <?=htmlspecialchars($row['age'])?></p>
    <p><strong>Designation:</strong> <?=htmlspecialchars($row['designation'])?></p>
    <p><strong>Gender:</strong> <?=htmlspecialchars($row['gender'])?></p>
    <p><strong>Address:</strong> <?=nl2br(htmlspecialchars($row['address']))?></p>
    <p><strong>Mobile:</strong> <?=htmlspecialchars($row['mobile'])?></p>

    <p><a href="vie
