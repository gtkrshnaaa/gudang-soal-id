<?php
session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../../login/admin/adminlogin.php");
    exit();
}

$admin = $_SESSION['admin'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        *,
        body {
            background-color: #202124;
            color: #bdc1c6ba;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="index.php">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="manageauthors/index.php">Manage Authors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="managearticles/index.php">Manage Articles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="managemapel/index.php">Manage Mapel</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Welcome, <?php echo $admin['username']; ?>!</h2>
        <p>This is the admin dashboard.</p>
        <a href="../../login/admin/adminlogout.php" class="btn btn-danger">Logout</a>
    </div>
</body>

</html>