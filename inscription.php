<?php
session_start();
require_once './db/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $codepostal = $_POST['codepostal'];

try {
    $insertsql = $conn->prepare("INSERT INTO users(nom, prenom, email, password, codepostal) VALUES (:nom, :prenom, :email, :password, :codepostal)");
    $insertsql->bindParam(':nom', $nom);
    $insertsql->bindParam(':prenom', $prenom);
    $insertsql->bindParam(':email', $email);
    $insertsql->bindParam(':password', $password);
    $insertsql->bindParam(':codepostal', $codepostal);
    
    if ($insertsql->execute()) {
        // Succès
        $_SESSION['message'] = 'Inscription réussie !';
    } else {
        // Échec
        $_SESSION['message'] = 'Une erreur est survenue lors de l\'inscription.';
    }

    header('Location: ./index.php');
    exit;
} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    echo "Erreur : " . $e->getMessage();
} } else {
    header('Location: ./index.php');
    exit();
}
?>
