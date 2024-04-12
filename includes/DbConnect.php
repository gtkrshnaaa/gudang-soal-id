<?php
require_once 'DbConfig.php';

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo " "; 
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
