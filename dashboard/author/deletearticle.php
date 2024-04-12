<?php
session_start();

// Periksa apakah author sudah login
if (!isset($_SESSION['author'])) {
    header("Location: ../../login/author/authorlogin.php");
    exit();
}

require_once '../../includes/dbconnect.php';

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Hapus artikel berdasarkan ID
    $stmt_delete = $conn->prepare("DELETE FROM articles WHERE id = :id");
    $stmt_delete->bindParam(':id', $article_id, PDO::PARAM_INT);
    
    if ($stmt_delete->execute()) {
        // Redirect ke halaman index setelah penghapusan berhasil
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting article.";
    }
} else {
    echo "Invalid request.";
    exit();
}
?>