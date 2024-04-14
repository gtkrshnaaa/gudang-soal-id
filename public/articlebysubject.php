<?php
require_once '../includes/dbconnect.php';

// Ambil ID subjek dari parameter URL
$subject_id = $_GET['subject_id'];

// Ambil semua artikel dengan ID subjek yang sama
$stmt = $conn->prepare("SELECT articles.*, 
    CASE
        WHEN articles.admin_id IS NOT NULL THEN admin.name
        WHEN articles.author_id IS NOT NULL THEN author.name
    END AS author_name,
    subjects.name AS subject_name,
    articles.view_count,
    articles.posting_date
FROM articles
LEFT JOIN Admin admin ON articles.admin_id = admin.id
LEFT JOIN Author author ON articles.author_id = author.id
LEFT JOIN subjects ON articles.subject_id = subjects.id
WHERE articles.subject_id = :subject_id");
$stmt->bindParam(':subject_id', $subject_id);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil semua subjek
$stmt = $conn->prepare("SELECT * FROM subjects");
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Index</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        *,
        body,
        .container {
            background-color: #202124;
            color: #bdc1c6;
            border: none;
        }

        .form-control {
            background-color: #3131318e;
            border: none;
            border-radius: 20px;
        }

        input[type="text"]::placeholder {
            color: #bdc1c66b;
        }

        input[type="text"]:focus {
            border-color: #343434;
            box-shadow: 0 0 5px #25252580;
            background-color: #3131318e;
            color: #bdc1c6;
        }

        .card {
            border: none;
        }

        a {
            color: #8ab4f8;
        }

        @media only screen and (max-width: 800px) {
            .respnsf {
                display: flex;
                flex-direction: column-reverse;
            }

            .subject-title {
                display: none;
            }

            .respnsf-subject {
                max-width: 90vw;
                display: flex;
                flex-direction: row;
                gap: 30px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }


            .respnsf-subject li {
                flex: 0 0 auto;
            }

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

    <!-- pencarian berdasarkan isi seluruh konten dan nama subjek -->
    <div class="container mt-5">
        <input type="text" id="search" class="form-control" placeholder="Cari materi atau mata pelajaran">
    </div>

    <div class="container mt-5">
        <div class="row respnsf">
            <div class="col-md-8">
                <?php foreach ($articles as $article): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                Mata Pelajaran <?php echo $article['subject_name']; ?>
                            </h5>
                            <p>
                                <?php echo $article['view_count']; ?> Views &nbsp;&#183;&nbsp; By
                                <?php echo $article['author_name']; ?> &nbsp;&#183;&nbsp;
                                <?php echo $article['subject_name']; ?>
                            </p>
                            <a href="viewarticle.php?slug=<?php echo $article['slug']; ?>" style="font-size: 25px;">
                                <?php echo implode(' ', array_slice(explode(' ', strip_tags($article['content'])), 0, 5)) . '...'; ?>
                            </a>
                            <p class="card-text">
                                <?php echo $article['posting_date']; ?> &nbsp;&#183;&nbsp;
                                <?php echo implode(' ', array_slice(explode(' ', strip_tags($article['content'])), 0, 100)) . '...'; ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-4 mt-4">
                <h5 class="subject-title">Daftar Mata Pelajaran</h5>
                <ul class="respnsf-subject">
                    <?php foreach ($subjects as $subject): ?>
                        <li>
                            <a href="articlebysubject.php?subject_id=<?php echo $subject['id']; ?>"><?php echo $subject['name']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('search').addEventListener('input', function () {
            var search = this.value.toLowerCase();
            var cards = document.querySelectorAll('.card');
            cards.forEach(function (card) {
                var content = card.textContent.toLowerCase();
                if (content.indexOf(search) === -1) {
                    card.style.display = 'none';
                } else {
                    card.style.display = 'block';
                }
            });
        });
    </script>
</body>

</html>