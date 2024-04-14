<?php
session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../../../login/admin/adminlogin.php");
    exit();
}

require_once '../../../includes/dbconnect.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Authors</title>
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
    <div class="container col-md-11 mt-5 mb-5">
        <h2>Manage Authors</h2>
        <a href="createauthor.php" class="btn btn-primary mb-3">Add New Author</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM Author");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['password']; ?></td>
                        <td>
                            <a href="editauthor.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="deleteauthor.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>