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


    <style>
        *,
        body {
            background-color: #202124;
            color: #bdc1c6ba;
        }

        .form-control {
            background-color: #3131318e;
            color: #bdc1c6ba;
            border: none;
        }

        input[type="text"]:focus {
            border-color: #343434;
            box-shadow: 0 0 5px #25252580;
            background-color: #3131318e;
            color: #bdc1c6ba;
        }
    </style>

</head>
<body>
    <div class="container mt-5">
        <h2>Add New Subject</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Subject</button>
        </form>
    </div>
</body>
</html>