<?php
// Démarre une session PHP
session_start();
// Inclut le fichier de connexion à la base de données
require_once './db/connect.php';

// Initialise les variables pour le mode d'édition
$edit_mode = false;
$edit_user = null;

// Gère la modification d'un utilisateur
if (isset($_POST['edit_user'])) {
    // Récupère les données du formulaire
    $id = $_POST['user_id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $codepostal = $_POST['codepostal'];

    try {
        // Prépare et exécute la requête SQL pour mettre à jour l'utilisateur
        $modifiesql = $conn->prepare("UPDATE users SET nom = :nom, prenom = :prenom, email = :email, codepostal = :codepostal WHERE id = :id");
        $modifiesql->bindParam(':nom', $nom);
        $modifiesql->bindParam(':prenom', $prenom);
        $modifiesql->bindParam(':email', $email);
        $modifiesql->bindParam(':codepostal', $codepostal);
        $modifiesql->bindParam(':id', $id);
        $modifiesql->execute();

        // Redirige vers la page de gestion après la modification
        header('Location: gestion.php');
        exit();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// Gère la suppression d'un utilisateur
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        // Prépare et exécute la requête SQL pour supprimer l'utilisateur
        $deletesql = $conn->prepare("DELETE FROM users WHERE id = :id");
        $deletesql->bindParam(':id', $id);
        $deletesql->execute();

        // Redirige vers la page de gestion après la suppression
        header('Location: gestion.php');
        exit();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// Gère l'affichage du formulaire d'édition
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = $_GET['edit'];
    
    // Récupère les informations de l'utilisateur à éditer
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $edit_id);
    $stmt->execute();
    $edit_user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupère tous les utilisateurs pour l'affichage
try {
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching users: " . $e->getMessage();
    $users = [];
}

?>