<?php
session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../../../login/admin/adminlogin.php");
    exit();
}

require_once '../../../includes/dbconnect.php';

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Meningkatkan jumlah view artikel
    $stmt_increase_view = $conn->prepare("UPDATE articles SET view_count = view_count + 1 WHERE id = :id");
    $stmt_increase_view->bindParam(':id', $article_id, PDO::PARAM_INT);
    $stmt_increase_view->execute();

    // Ambil artikel berdasarkan ID
    $stmt_article = $conn->prepare("SELECT articles.*, 
        CASE
            WHEN articles.admin_id IS NOT NULL THEN admin.name
            WHEN articles.author_id IS NOT NULL THEN author.name
        END AS author_name,
        subjects.name AS subject_name
    FROM articles
    LEFT JOIN Admin admin ON articles.admin_id = admin.id
    LEFT JOIN Author author ON articles.author_id = author.id
    LEFT JOIN subjects ON articles.subject_id = subjects.id
    WHERE articles.id = :id");
    $stmt_article->bindParam(':id', $article_id, PDO::PARAM_INT);
    $stmt_article->execute();
    $article = $stmt_article->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        echo "Article not found.";
        exit();
    }

    // Ambil jumlah view artikel
    $stmt_view = $conn->prepare("SELECT view_count FROM articles WHERE id = :id");
    $stmt_view->bindParam(':id', $article_id, PDO::PARAM_INT);
    $stmt_view->execute();
    $view_count = $stmt_view->fetchColumn();

    if ($view_count) {
        $article['view_count'] = $view_count;
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Article</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="../index.php">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../manageauthors/index.php">Manage Authors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../managearticles/index.php">Manage Articles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../managemapel/index.php">Manage Mapel</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>View Article</h2>
        <p>Author: <?php echo $article['author_name']; ?></p>
        <p>Subject: <?php echo $article['subject_name']; ?></p>
        <p>Content: <?php echo $article['content']; ?></p>
        <p>Views: <?php echo $article['view_count']; ?></p>
    </div>
</body>
</html>