<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Admin WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Admin berhasil login
        session_start();
        $_SESSION['admin'] = ['username' => $username]; // Simpan data admin dalam session
        header("Location: ../../dashboard/admin/index.php");
        exit();
    } else {
        // Admin gagal login
        echo "Login gagal. Username atau password salah.";
    }
} else {
    // Jika bukan metode POST, redirect ke halaman login
    header("Location: adminlogin.php");
    exit();
}
?>