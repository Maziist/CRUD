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
        $_SESSION['success_message'] = "Nouvel utilisateur ajouté avec succès !";
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
if (isset($_SESSION['success_message'])) {
    echo "<p class='success-message'>" . $_SESSION['success_message'] . "</p>";
    unset($_SESSION['success_message']); // Clear the message after displaying
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/gestion.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda+SC:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&display=swap" rel="stylesheet">
<title>CRUD</title>
</head>
<body>
    <header>
    <h1>Gestion des Utilisateurs</h1>
    </header>
    <main>
       
        <a id="return" href="index.php">Retour</a>
        <a id="add" href="ajout.php">Ajouter un utilisateur</a>
        <?php if (empty($users)): ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php else: ?>
            <div id="container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Code Postal</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <?php if ($edit_mode && $edit_user['id'] == $user['id']): ?>
                            <form id="edit-form" method="POST" action="">
                                <td><?php echo $user['id']; ?></td>
                                <td><input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                                <td><input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                                <td><input type="email" name="email" value="<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                                <td><input type="text" name="codepostal" value="<?php echo htmlspecialchars($user['codepostal'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                                <td>
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button id="enregistrer" type="submit" name="edit_user">Enregistrer</button>
                                    <a id="cancel" href="gestion.php">Annuler</a>
                                </td>
                            </form>
                        <?php else: ?>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['nom'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($user['prenom'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($user['codepostal'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <a id="edit" href="gestion.php?edit=<?php echo $user['id']; ?>">Modifier</a>
                                <a id="delete" href="gestion.php?delete=<?php echo $user['id']; ?>">Supprimer</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>