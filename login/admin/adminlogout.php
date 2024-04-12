<?php
session_start();

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke halaman login admin
header("Location: adminlogin.php");
exit();
?>
