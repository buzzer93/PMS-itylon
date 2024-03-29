<?php

use App\App;
use App\DataBase;

require_once '../vendor/autoload.php';

// Vérifie l'authentification de l'utilisateur
$user = App::getAuth()->get_user();
App::getAuth()->requireRole('admin');

// Inclusion des fonctions utilitaires
require_once '../functions/fonction.php';
require_once './assets/elements/header.php';

// Traitement de la suppression si le formulaire est soumis
if (!empty($_POST)) {
    $id = checkInput($_POST['id']);
    $pdo = Database::connect();
    
    // Préparation de la requête de suppression dans la base de données
    $statement = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $statement->execute(array($id));
    
    // Redirection vers la page principale après suppression
    header('location: ./index.php');
    exit();
}
?>
<div class="container admin">
    <div class="row">
        <h1><strong>Supprimer la réservation</strong></h1>
        <br>
        <form class="form" action="delete.php" role="form" method="post">
            <br>
            <!-- Champ caché contenant l'ID de la réservation à supprimer -->
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
            
            <!-- Message de confirmation de suppression -->
            <p class="container alert alert-warning">Êtes-vous sûr de vouloir supprimer ?</p>
            
            <!-- Boutons de confirmation et d'annulation -->
            <div class="container form-actions">
                <button type="submit" class="btn btn-red">Oui</button>
                <a class="btn" href="index.php">Non</a>
            </div>
        </form>
    </div>
</div>

<?php
require_once './assets/elements/footer.php';
?>
