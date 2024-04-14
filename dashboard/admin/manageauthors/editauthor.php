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
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("UPDATE Author SET name = :name, username = :username, password = :password WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating author.";
    }
} else {
    $authorId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM Author WHERE id = :id");
    $stmt->bindParam(':id', $authorId);
    $stmt->execute();
    $author = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Author</title>
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
        <h2>Edit Author</h2>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $author['id']; ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $author['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $author['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" value="<?php echo $author['password']; ?>" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">Show</button>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Author</button>
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