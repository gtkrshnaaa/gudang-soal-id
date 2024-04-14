<?php
// Mulai session
session_start();

// Ambil session ID pengguna
$session_id = session_id();

// Ambil slug halaman detail artikel dari parameter URL
$slug_halaman_detail = $_GET['slug'] ?? '';

// Set session untuk menyimpan slug halaman detail artikel dengan menggunakan session ID sebagai kunci
$_SESSION[$session_id]['slug_halaman_detail'] = $slug_halaman_detail;


// Lakukan redirect setelah 3 detik dengan menyertakan session ID dan slug sebagai parameter
header("Refresh: 3; URL=viewarticle.php?session_id=$session_id&slug=$slug_halaman_detail");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
</head>
<body>
    <p>Redirecting to the article...</p>
</body>
</html>