<?php
session_start();

try {
    require_once '../../includes/dbconnect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Periksa apakah username dan password valid
        $stmt = $conn->prepare("SELECT * FROM Author WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $author = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($author) {
            // Jika valid, set session 'author' dan redirect ke halaman index
            $_SESSION['author'] = $author;
            header("Location: ../../dashboard/author/index.php");
            exit();
        } else {
            // Jika tidak valid, tampilkan pesan error
            echo "Invalid username or password.";
        }
    }
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>