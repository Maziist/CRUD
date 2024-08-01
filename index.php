<?php
session_start();
require_once './db/connect.php';
try {
    $conn->beginTransaction();
    $createsql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        prenom VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        codepostal INT NOT NULL
        )";

        $conn->exec($createsql);
   
} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&display=swap" rel="stylesheet">
    <title>CRUD</title>
</head>
<body>
    <header>
        <h1>CRUD</h1>
    </header>
    <main>
        <div id="container">
            <div id="incription">
        <h2>Inscription</h2>
        <form  action="inscription.php" method="post">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom">
            <label for="prenom">Prenom</label>
            <input type="text" name="prenom" id="prenom">
            <label for="email">Email</label>
            <input type="email" name="email" class="email">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" class="password">
            <label for codepost="codepostal">Code postal</label>
            <input type="number" name="codepostal" id="codepostal">
            <input type="submit" value="S'inscrire">
        </form>
        </div>
        <div id="connexion">
        <h2>Connexion</h2>
        <form  action="gestion.php" method="post">
            <label for="email">Email</label>
            <input type="email" name="email" class="email">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" class="password">
            <input type="submit" value="Se connecter">
        </form>
        </div>
        </div>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<p>' . $_SESSION['message'] . '</p>';
            unset($_SESSION['message']); // Supprimer le message après l'affichage
        }
        ?>
    </main>
</body>
</html>