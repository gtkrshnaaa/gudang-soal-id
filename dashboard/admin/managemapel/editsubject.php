<?php
session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../../../login/admin/adminlogin.php");
    exit();
}

require_once '../../../includes/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];

    $stmt = $conn->prepare("UPDATE subjects SET name = :name WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating subject.";
    }
} else {
    $subjectId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE id = :id");
    $stmt->bindParam(':id', $subjectId);
    $stmt->execute();
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Subject</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Subject</h2>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $subject['id']; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $subject['name']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Subject</button>
        </form>
    </div>
</body>
</html>