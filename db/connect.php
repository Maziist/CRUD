<?php
$servername = "localhost";
$user = "root";
$pass = "";
$dbname = "crud"; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=crud","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    die();
}

?>
