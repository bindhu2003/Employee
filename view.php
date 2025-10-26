<?php
include('database.php');
$sql = "SELECT id, name, designation FROM employee ORDER BY name";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Employee List</title>
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
<nav class="navi-container">
        <div class="orga-name">
            <marquee behavior="scroll" direction="left">NRSC task1</marquee>
        </div>
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="form.php">Form</a></li>
            <li><a href="view.php">View</a></li>
        </ul>
    </nav>
<div class="container">
    <h2>Employees</h2>
    <?php if (empty($rows)): ?>
        <p>No employees found. <a href="form.php">Add one</a>.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Designation</th><th>Details</th></tr>
            </thead>
            <tbody>
                <?php foreach($rows as $r): ?>
                <tr>
                    <td><?=htmlspecialchars($r['id'])?></td>
                    <td><?=htmlspecialchars($r['name'])?></td>
                    <td><?=htmlspecialchars($r['designation'])?></td>
                    <td><a href="details.php?id=<?=urlencode($r['id'])?>">Details</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
