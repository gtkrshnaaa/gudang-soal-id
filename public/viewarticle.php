<?php

// Mulai session
session_start();

require_once '../includes/dbconnect.php';

// Ambil artikel berdasarkan slug
$stmt = $conn->prepare("SELECT articles.*, 
    CASE
        WHEN articles.admin_id IS NOT NULL THEN admin.name
        WHEN articles.author_id IS NOT NULL THEN author.name
    END AS author_name,
    subjects.name AS subject_name
FROM articles
LEFT JOIN Admin admin ON articles.admin_id = admin.id
LEFT JOIN Author author ON articles.author_id = author.id
LEFT JOIN subjects ON articles.subject_id = subjects.id
WHERE articles.slug = :slug");
$stmt->bindParam(':slug', $_GET['slug']);
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

// Meningkatkan view count
$stmt = $conn->prepare("UPDATE articles SET view_count = view_count + 1 WHERE slug = :slug");
$stmt->bindParam(':slug', $_GET['slug']);
$stmt->execute();

// Ambil semua subjek
$stmt = $conn->prepare("SELECT * FROM subjects");
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);


// LOGIC IKLAN
// Cek jika adlink tidak kosong, lalu eksekusi logika iklan
if (!empty($article['adlink'])) {
    // logic iklan
    // Ambil session ID pengguna dari parameter URL
    $session_id = $_GET['session_id'];

    // Cek apakah session ID pengguna sama dengan session ID saat diredirect dari halaman perantara
    $redirected_from_perantara = isset($_SESSION[$session_id]) ? $_SESSION[$session_id] : null;
    $current_session_id = session_id();

    // Jika session ID pengguna tidak sama dengan session ID saat diredirect dari halaman perantara, arahkan ke link iklan
    if ($redirected_from_perantara === null) {
        // Tautan iklan yang sudah dipendekkan dari halaman perantara
        $iklanURL = $article['adlink'];

        // Menyiapkan script JavaScript untuk menampilkan popup dan menunggu sebelum mengarahkan ke halaman iklan
        echo "<script>
            // Menampilkan popup dengan pesan Lewati iklan sebelum membaca
            alert('Lewati iklan sebelum membaca, klik OK untuk lanjutkan.');
            </script>";

        // Redirect pengguna ke tautan iklan
        header("Refresh: 0.1; URL=$iklanURL");
        exit();
    }



    // Bersihkan session yang menandakan pengguna diredirect dari halaman perantara
    unset($_SESSION[$session_id]);
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo implode(' ', array_slice(explode(' ', strip_tags($article['content'])), 0, 5)) . '...'; ?> Gudang
        Soal ID</title>
    <meta name="description"
        content="<?php echo implode(' ', array_slice(explode(' ', strip_tags($article['content'])), 0, 100)) . '...'; ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        *,
        body,
        .container {
            background-color: #202124;
            color: #bdc1c6ba;
            border: none;
        }

        .card {
            border: none;
        }

        a {
            color: #8ab4f8;
        }
    </style>


</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container d-flex justify-content-between">
            <h3>Gudang Soal ID</h3>
            <a class="nav-link" href="index.php">Home</a>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3>Mata Pelajaran <?php echo $article['subject_name']; ?></h3>
                        <p><?php echo $article['view_count']; ?> Views &nbsp;&#183;&nbsp; By
                            <?php echo $article['author_name']; ?> &nbsp;&#183;&nbsp;
                            <?php echo $article['posting_date']; ?>
                        </p>
                        <p><?php echo $article['content']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <h5>Daftar Mata Pelajaran</h5>
                <ul>
                    <?php foreach ($subjects as $subject): ?>
                        <li><a
                                href="articlebysubject.php?subject_id=<?php echo $subject['id']; ?>"><?php echo $subject['name']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>