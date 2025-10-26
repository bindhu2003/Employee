<?php
include('database.php'); // Make sure this file contains your mysqli connection $conn

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id']);
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $designation = trim($_POST['designation']);
    $gender = trim($_POST['gender']);
    $address = trim($_POST['address']);
    $mobile = trim($_POST['mobile']);

    // Validation
    if ($id === "" || $name === "") $errors[] = "ID and Name are required.";
    if (!is_numeric($age) || $age <= 0) $errors[] = "Provide a valid age.";
    if (!preg_match('/^[0-9]{7,15}$/', $mobile)) $errors[] = "Provide a valid mobile number.";

    // Picture upload
    $pictureData = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['picture']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading file.";
        } else {
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['picture']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowed)) {
                $errors[] = "Only JPG, PNG, GIF allowed.";
            } elseif ($_FILES['picture']['size'] > 2 * 1024 * 1024) {
                $errors[] = "Image must be <= 2MB.";
            } else {
                $pictureData = file_get_contents($_FILES['picture']['tmp_name']);
            }
        }
    }

    // Insert into database if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO employee (id, name, age, designation, gender, picture, address, mobile) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $null = NULL;
        $stmt->bind_param("ssissbss", $id, $name, $age, $designation, $gender, $null, $address, $mobile);

        if ($pictureData !== null) {
            $stmt->send_long_data(5, $pictureData);
        }

        if ($stmt->execute()) {
            $success = "Employee added successfully.";
        } else {
            $errors[] = "Execute failed: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navi-container">
    <div class="orga-name">
        <marquee behavior="scroll" direction="left">NRSC Task1</marquee>
    </div>
    <ul class="menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="form.php">Form</a></li>
        <li><a href="view.php">View</a></li>
    </ul>
</nav>

<div class="container">
    <h2>Add Employee</h2>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err) echo "<li>" . htmlspecialchars($err) . "</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Employee ID</label>
        <input type="text" name="id" value="<?= isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '' ?>">

        <label>Name</label>
        <input type="text" name="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">

        <label>Age</label>
        <input type="number" name="age" value="<?= isset($_POST['age']) ? htmlspecialchars($_POST['age']) : '' ?>">

        <label>Designation</label>
        <input type="text" name="designation" value="<?= isset($_POST['designation']) ? htmlspecialchars($_POST['designation']) : '' ?>">

        <label>Gender</label>
        <label><input type="radio" name="gender" value="Male" <?= (isset($_POST['gender']) && $_POST['gender']=='Male') ? 'checked' : '' ?>> Male</label>
        <label><input type="radio" name="gender" value="Female" <?= (isset($_POST['gender']) && $_POST['gender']=='Female') ? 'checked' : '' ?>> Female</label>

        <label>Picture (JPG/PNG/GIF, max 2MB)</label>
        <input type="file" name="picture">

        <label>Address</label>
        <textarea name="address"><?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?></textarea>

        <label>Mobile</label>
        <input type="text" name="mobile" value="<?= isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : '' ?>">

        <input type="submit" value="Submit">
    </form>
</div>
</body>
</html>
