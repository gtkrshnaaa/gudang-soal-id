<?php
session_start();

// Periksa apakah author sudah login
if (!isset($_SESSION['author'])) {
    header("Location: ../../login/author/authorlogin.php");
    exit();
}

require_once '../../includes/dbconnect.php';

// Ambil artikel yang akan diedit
$article = null;
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

// Ambil daftar mata pelajaran dari database
$stmt_subjects = $conn->query("SELECT * FROM subjects");
if ($stmt_subjects) {
    $subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Error fetching subjects: " . $conn->errorInfo()[2];
    // Atau tambahkan penanganan kesalahan lainnya sesuai kebutuhan Anda
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    try {
        $article_id = $_POST['id'];
        $content = $_POST['content'];
        $subject = $_POST['subject'];
        $slug = $_POST['slug']; // Menambahkan binding untuk slug

        // Update konten, subjek, dan slug artikel
        $stmt_update = $conn->prepare("UPDATE articles SET content = :content, subject_id = (SELECT id FROM subjects WHERE name = :subject LIMIT 1), slug = :slug WHERE id = :id");
        $stmt_update->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt_update->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt_update->bindParam(':slug', $slug, PDO::PARAM_STR); // Menambahkan binding untuk slug
        $stmt_update->bindParam(':id', $article_id, PDO::PARAM_INT);

        if ($stmt_update->execute()) {
            // Redirect ke halaman index setelah update berhasil
            header("Location: index.php");
            exit();
        } else {
            throw new Exception("Error updating article.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
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
    <div class="container mt-5 mb-5">
        <h2>Edit Article</h2>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
            <div class="form-group">
                <label for="content">Content</label>
                <div id="editor" style="height: 600px; border: #bdc1c6ba 1px solid;"></div>
                <textarea id="hiddenInput" name="content"
                    style="display: none;"><?php echo $article['content']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control bg-dark" id="slug" name="slug"
                    value="<?php echo $article['slug']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <select class="form-control bg-dark" id="subject" name="subject" required>
                    <?php foreach ($subjects as $subject) { ?>
                    <option value="<?php echo $subject['name']; ?>" <?php if
                        ($subject['name']==$article['subject_name']) echo 'selected="selected"' ; ?>>
                        <?php echo $subject['name']; ?>
                    </option>
                    <?php } ?>
                </select>
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

        quill.on('text-change', function () {
            var content = quill.root.innerHTML;
            var cleanedContent = content.replace(/<[^>]*>/g, ''); // Membersihkan tag HTML dari konten
            var words = cleanedContent.split(' ');
            var slug = words.slice(0, 100).join('-');
            document.getElementById('slug').value = slug;
            document.getElementById('hiddenInput').value = content;
            document.getElementById('slug').value = generateSlug(content);
        });

        // Fungsi untuk mengirim formulir dan slug ke server saat mengupdate artikel
        document.querySelector('form').addEventListener('submit', function () {
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