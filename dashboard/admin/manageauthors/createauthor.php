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
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fungsi untuk menambahkan author baru
    function addAuthor($name, $username, $password) {
        global $conn;
        
        $stmt = $conn->prepare("INSERT INTO Author (name, username, password) VALUES (:name, :username, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        
        return $stmt->execute();
    }

    if (addAuthor($name, $username, $password)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error adding author.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Author</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Author</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">Show</button>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Author</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>
</html>