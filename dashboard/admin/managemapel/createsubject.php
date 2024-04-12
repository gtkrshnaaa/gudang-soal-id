<?php
session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../../../login/admin/adminlogin.php");
    exit();
}

require_once '../../../includes/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    $stmt = $conn->prepare("INSERT INTO subjects (name) VALUES (:name)");
    $stmt->bindParam(':name', $name);

    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error adding subject.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Subject</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../../../layout/adminnavbar.php'; ?>
    <div class="container mt-5">
        <h2>Add New Subject</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Subject</button>
        </form>
    </div>
</body>
</html>