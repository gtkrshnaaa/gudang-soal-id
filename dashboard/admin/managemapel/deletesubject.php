<?php
session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../../../login/admin/adminlogin.php");
    exit();
}

require_once '../../../includes/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $subjectId = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM subjects WHERE id = :id");
    $stmt->bindParam(':id', $subjectId);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting subject.";
    }
}
?>