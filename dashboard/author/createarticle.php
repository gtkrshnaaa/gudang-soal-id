<?php
session_start();

// Periksa apakah author sudah login
if (!isset($_SESSION['author'])) {
    header("Location: ../../login/author/authorlogin.php");
    exit();
}

require_once '../../includes/dbconnect.php';

// Ambil daftar mata pelajaran dari database
$stmt_subjects = $conn->query("SELECT * FROM subjects");
$subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $content = $_POST['content'];
        $subject_id = $_POST['subject'];
        $slug = $_POST['slug']; // Tambahkan ini untuk mendapatkan nilai slug dari form

        $author_id = $_SESSION['author']['id'];
        $author_type = 'author'; // Menandai bahwa penulis adalah author

        $stmt = $conn->prepare("INSERT INTO articles (slug, content, subject_id, author_id, author_type) VALUES (:slug, :content, :subject_id, :author_id, :author_type)");
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT); // Menambahkan tipe parameter untuk author_id
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
    <div class="container mt-5 mb-5">
        <h2>Add New Article</h2>
        <form id="articleForm" action="" method="post">
            
            <div class="form-group">
                <label for="slug">Slug:</label>
                <input type="text" class="form-control" id="slug" name="slug" readonly>
            </div>
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