<?php
session_start();

// Periksa apakah author sudah login
if (!isset($_SESSION['author'])) {
    header("Location: ../../login/author/authorlogin.php");
    exit();
}

require_once '../../includes/dbconnect.php';

// Ambil daftar artikel dengan informasi penulis dari database
$stmt_articles = $conn->prepare("SELECT articles.*, 
    CASE
        WHEN articles.admin_id IS NOT NULL THEN admin.name
        WHEN articles.author_id IS NOT NULL THEN author.name
    END AS author_name,
    subjects.name AS subject_name
FROM articles
LEFT JOIN Admin admin ON articles.admin_id = admin.id
LEFT JOIN Author author ON articles.author_id = author.id
LEFT JOIN subjects ON articles.subject_id = subjects.id
WHERE articles.author_id = :author_id");
$stmt_articles->bindParam(':author_id', $_SESSION['author']['id'], PDO::PARAM_INT);
$stmt_articles->execute();
$articles = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Articles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        *,
        body {
            background-color: #202124;
            color: #bdc1c6ba;
        }

        .wide-table {
            width: 100%;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="index.php">Author Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link" href="../../login/author/authorlogout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container col-md-11 mt-5">
        <h2>Manage Articles</h2>
        <a href="createarticle.php" class="btn btn-primary mb-3">Add Article</a>
        <div class="table-responsive">
            <table class="table wide-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Content</th>
                        <th>Subject</th>
                        <th>Views</th>
                        <th>Date</th>
                        <th>Author</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article) { ?>
                        <tr>
                            <td><?php echo $article['id']; ?></td>
                            <td><?php echo implode(' ', array_slice(explode(' ', strip_tags($article['content'])), 0, 5)) . '...'; ?>
                            </td>
                            <td><?php echo $article['subject_name']; ?></td>
                            <td><?php echo $article['view_count']; ?></td>
                            <td><?php echo $article['posting_date']; ?></td>
                            <td><?php echo $article['author_name']; ?></td>
                            <td>
                                <a href="viewarticle.php?id=<?php echo $article['id']; ?>" class="btn btn-info">View</a>
                                <a href="editarticle.php?id=<?php echo $article['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="deletearticle.php?id=<?php echo $article['id']; ?>"
                                    class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>