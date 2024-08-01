<?php
session_start();
require_once '../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $codepostal = $_POST['codepostal'];
    $password = $_POST['password'];

    // Validation des données (exemple)
    if (empty($password)) {
        echo "Le mot de passe ne peut pas être vide.";
        exit();
    }

    // Hasher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Préparer et exécuter la requête SQL
    try {
        $sql = "INSERT INTO users (nom, prenom, email, codepostal, password) VALUES (:nom, :prenom, :email, :codepostal, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':codepostal', $codepostal);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        if ($stmt->execute()) {
            // Succès
            $_SESSION['message'] = 'Nouvel utilisateur ajouté avec succès !';
        } else {
            // Échec
            $_SESSION['message'] = 'Une erreur est survenue lors de l\'ajout.';
        }
    
        header('Location: ../gestion.php');
        exit;
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>