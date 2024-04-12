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
</head>

<body>
    <?php include '../../layout/adminnavbar.php'; ?>
    <div class="container mt-5">
        <h2>Welcome, <?php echo $admin['username']; ?>!</h2>
        <p>This is the admin dashboard.</p>
        <a href="../../login/admin/adminlogout.php" class="btn btn-danger">Logout</a>
    </div>
</body>

</html>