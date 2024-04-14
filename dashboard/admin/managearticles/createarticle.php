<?php
session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../../../login/admin/adminlogin.php");
    exit();
}

require_once '../../../includes/dbconnect.php';

// Ambil daftar mata pelajaran dari database
$stmt_subjects = $conn->query("SELECT * FROM subjects");
$subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $content = $_POST['content'];
        $subject_id = $_POST['subject'];
        $slug = $_POST['slug'];
        $adlink = $_POST['adlink']; // Mengambil nilai adlink dari form

        $admin_id = $_SESSION['admin'];
        $author_type = 'admin'; 

        $stmt = $conn->prepare("INSERT INTO articles (slug, content, adlink, subject_id, admin_id, author_type) VALUES (:slug, :content, :adlink, :subject_id, :admin_id, :author_type)");
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':adlink', $adlink); // Mengikat nilai adlink ke dalam pernyataan SQL
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
        $stmt->bindParam(':author_type', $author_type);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            throw new Exception("Error adding article.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>



<!DOCTYPE html>
<html>
<head>
    <title>Add New Article</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
    <style>
        #editor {
            height: 600px; /* Atur ketinggian editor di sini */
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
    <div class="container mt-5 mb-5">
        <h2>Add New Article</h2>
        <form id="articleForm" action="" method="post">
            
            <div class="form-group">
                <label for="slug">Slug:</label>
                <input type="text" class="form-control" id="slug" name="slug" readonly>
            </div>
            <div class="form-group">
                <label for="adlink">adlink:</label>
                <input type="text" class="form-control" id="adlink" name="adlink">
            </div>
            <p><strong>adlink</strong> adalah link yang di pendekan dari ( https://namadomain.com/public/perantaraiklan.php?slug= ||| http://localhost/app/gudang-soal-id/public/perantaraiklan.php?slug= )</p>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <select class="form-control" id="subject" name="subject" required>
                    <?php foreach ($subjects as $subject) { ?>
                        <option value="<?php echo $subject['id']; ?>"><?php echo $subject['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="content">Content:</label>
                <div id="editor"></div>
                <textarea id="hiddenInput" name="content" style="display: none;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Article</button>
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
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        quill.on('text-change', function() {
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