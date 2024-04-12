<?php
session_start();

// Hapus session 'author'
unset($_SESSION['author']);

// Redirect ke halaman login
header("Location: authorlogin.php");
exit();
