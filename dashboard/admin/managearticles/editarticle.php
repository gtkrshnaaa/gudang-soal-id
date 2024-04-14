<?php
session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../../../login/admin/adminlogin.php");
    exit();
}

require_once '../../../includes/dbconnect.php';

$article = null;

// Proses permintaan POST untuk update artikel
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $article_id = $_POST['id'];
    $content = $_POST['content'];
    $subject = $_POST['subject'];
    $slug = $_POST['slug']; // Menambahkan binding untuk slug
    $adlink = $_POST['adlink']; // Mengambil nilai adlink dari form

    // Update konten, subjek, dan slug artikel
    $stmt_update = $conn->prepare("UPDATE articles SET content = :content, subject_id = (SELECT id FROM subjects WHERE name = :subject LIMIT 1), slug = :slug, adlink = :adlink WHERE id = :id");
    $stmt_update->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt_update->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt_update->bindParam(':slug', $slug, PDO::PARAM_STR); // Menambahkan binding untuk slug
    $stmt_update->bindParam(':adlink', $adlink, PDO::PARAM_STR); // Menambahkan binding untuk adlink
    $stmt_update->bindParam(':id', $article_id, PDO::PARAM_INT);
    
    if ($stmt_update->execute()) {
        // Redirect ke halaman index setelah update berhasil
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Error updating article.');</script>";
    }
}

// Selalu proses permintaan GET untuk menampilkan artikel
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

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
        echo "<script>alert('Article not found.');</script>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Article</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
    <style>
        #editor {
            height: 600px;
        }
    </style>
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
        <h2>Edit Article</h2>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
            <div class="form-group">
                <label for="content">Content:</label>
                <div id="editor" style="height: 400px; border: 1px solid #ccc;"></div>
                <textarea id="hiddenInput" name="content" style="display: none;"><?php echo $article['content']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="slug">Slug:</label>
                <input type="text" class="form-control" id="slug" name="slug" value="<?php echo $article['slug']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="adlink">adlink:</label>
                <input type="text" class="form-control" id="adlink" name="adlink" value="<?php echo $article['adlink']; ?>">
            </div>
            <p><strong>adlink</strong> adalah link yang di pendekan dari ( https://namadomain.com/public/perantaraiklan.php?slug= ||| http://localhost/app/gudang-soal-id/public/perantaraiklan.php?slug= )</p>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" class="form-control" name="subject" value="<?php echo $article['subject_name']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Article</button>
        </form>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    ['link'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        // Set konten ke Quill editor
        var existingContent = document.getElementById('hiddenInput').value;
        quill.root.innerHTML = existingContent;

        quill.on('text-change', function() {
            var content = quill.root.innerHTML;
            var cleanedContent = content.replace(/<[^>]*>/g, ''); // Membersihkan tag HTML dari konten
            var words = cleanedContent.split(' ');
            var slug = words.slice(0, 100).join('-');
            document.getElementById('slug').value = slug;
            document.getElementById('hiddenInput').value = content;
            document.getElementById('slug').value = generateSlug(content);
        });

        // Fungsi untuk mengirim formulir dan slug ke server saat mengupdate artikel
        document.querySelector('form').addEventListener('submit', function() {
            var content = quill.root.innerHTML;
            var cleanedContent = content.replace(/<[^>]*>/g, ''); // Membersihkan tag HTML dari konten
            var words = cleanedContent.split(' ');
            var slug = words.slice(0, 100).join('-');
            document.getElementById('slug').value = slug;
            document.getElementById('hiddenInput').value = content;
        });
    </script>
</body>
</html>
