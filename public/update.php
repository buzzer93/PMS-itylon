<?php

use App\DataBase;

require_once '../vendor/autoload.php';
require_once '../functions/auth.php';
login();
require_once '../functions/fonction.php';

$db = Database::connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
      
        $nom = $_POST['form_nom'];
        $prenom = $_POST['form_prenom'];
        $email = $_POST['form_mail'];
        $tel = $_POST['form_tel'];
        $maison = strtolower($_POST['form_maison']);
        $arrivee = $_POST['form_arrivee'];
        $depart = $_POST['form_depart'];
        $nb_adulte = $_POST['form_nb_adulte'];
        $nb_mineur = $_POST['form_nb_mineur'];
        $chien = $_POST['form_nb_chien'];
        $prix = $_POST['form_prix'];
        $acompte = $_POST['form_acompte'];
        $commentaire = $_POST['form_commentaire'];
        $booking = $_POST['form_booking'];
        $id = $_POST['id'];



        $stmt = $db->prepare(
            "UPDATE reservations SET 
            nom = :nom,
            prenom = :prenom,
            email = :email,
            tel = :tel,
            maison = :maison,
            arrivee = :arrivee,
            depart = :depart,
            nb_adulte = :nb_adulte,
            nb_mineur = :nb_mineur,
            chien = :chien,
            prix = :prix,
            acompte = :acompte,
            commentaire =:commentaire,
            booking =:booking
            WHERE 
            id = :id"
        );
        if (!$stmt || !$stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'tel' => $tel,
            'maison' => $maison,
            'arrivee' => $arrivee,
            'depart' => $depart,
            'nb_adulte' => $nb_adulte,
            'nb_mineur' => $nb_mineur,
            'chien' => $chien,
            'prix' => $prix,
            'acompte' => $acompte,
            'commentaire' => $commentaire,
            'booking' => $booking,
            'id' => $id
        ])) {
            throw new RuntimeException($stmt ? $stmt->errorInfo()[2] : $db->errorInfo()[2]);
        }
        // Redirect to index.php after successful update

        header('Location: ./index.php');
        usleep(200000);
        exit();
    } catch (Exception $e) {
        error_log($e->getMessage());
        // We're not starting a transaction, so there's no point in calling rollBack
    }
} else {
    // Retrieve reservation details for pre-filling the form
    $reservation_select = $db->prepare(
        "SELECT * 
     FROM reservations
     WHERE id = :id"
    );
    $reservation_select->execute(['id' => $_GET['id']]);
    $reservation = $reservation_select->fetch(PDO::FETCH_ASSOC);
    if (!$reservation) {
        // Redirect to an error page or handle the error as needed
        exit("reservation not found");
    }

    $nom = $reservation['nom'];
    $prenom = $reservation['prenom'];
    $email = $reservation['email'];
    $tel = $reservation['tel'];
    $maison = $reservation['maison'];
    $arrivee = $reservation['arrivee'];
    $depart = $reservation['depart'];
    $nb_adulte = $reservation['nb_adulte'];
    $nb_mineur = $reservation['nb_mineur'];
    $chien = $reservation['chien'];
    $prix = $reservation['prix'];
    $acompte = $reservation['acompte'];
    $commentaire = $reservation['commentaire'];
    $booking = $reservation['booking'];
    
}
$maisons_query = $db->query("SELECT * FROM maisons");

while ($maison_select = $maisons_query->fetch(PDO::FETCH_ASSOC)) {
    $maisons_select[] = $maison_select;
}
require_once './assets/elements/header.php';
?>

<main class="container admin">
    <section class="section">
        <h1 class="section-title">
            <strong>Modifier la réservation</strong>
        </h1>
        <h2><?= e($prenom); ?><?= e($nom); ?></h2>
        <!==================FORMULAIRE==========================!>

            <form class="new-res-form" id="form_new_reservation" action="update.php" method="post" role="form">
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
                <div class="form-group">
                    <div class="form-item">
                        <label for="form_booking">Type de Réservation :</label>
                        <select class="form-select" name="form_booking" id="form_booking" require_onced>
                            <option value=<?= e($booking) ?>><?php if ($booking === 1) {
                                                                    echo 'Booking';
                                                                } elseif ($booking === 2) {
                                                                    echo 'Directe';
                                                                } ?></option>
                            <option value="1">Booking</option>
                            <option value="2">Directe</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-item">
                        <label for="form_nom">Nom :</label>
                        <input class="form-control" id="form_nom" type="text" name="form_nom" value="<?= e($nom); ?>">
                        <label for="form_prenom">Prénom :</label>
                        <input class="form-control" id="form_prenom" type="text" name="form_prenom" value="<?= e($prenom); ?>">
                    </div>
                    <!-- Contact -->
                    <div class="form-item">
                        <label for="form_mail">Email :</label>
                        <input class="form-control" type="email" name="form_mail" id="form_mail" value="<?= e($email); ?>">
                        <label for="form_tel">Téléphone :</label>
                        <input class="form-control" type="tel" name="form_tel" id="form_tel" value="<?= e($tel); ?>">
                    </div>
                </div>
                <!-- Choix maison -->
                <div class="form-group">
                    <div class="form-item">
                        <label for="form_maison">Choix de la Maison :</label>
                        <select class="form-select" name="form_maison" id="form_maison" require_onced>
                            <option selected><?php echo e(ucfirst($maison)); ?></option>
                            <?php foreach ($maisons_select as $select) : ?>
                                <option value=<?= $select['name'] ?>><?= ucfirst($select['name']) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <!-- Dates -->
                    <div class="form-item">
                        <label for="form_arrivee">Date d'Arrivée :</label>
                        <input class="form-control" type="date" name="form_arrivee" id="form_arrivee" value="<?= e($arrivee); ?>" plrequire_onced>
                        <label for="form_depart">Date de Départ :</label>
                        <input class="form-control" type="date" name="form_depart" id="form_depart" value="<?= e($depart); ?>" require_onced>
                    </div>
                </div>
                <!-- Personne / chien -->
                <div class="form-group">
                    <div class="form-item">
                        <label for="form_nb_adulte">Personnes adultes:</label>
                        <input class="form-control" type="text" name="form_nb_adulte" id="form_nb_adulte" value="<?= e($nb_adulte); ?>">
                    </div>
                    <div class="form-item">
                        <label for="form_nb_mineur">Personnes mineurs:</label>
                        <input class="form-control" type="text" name="form_nb_mineur" id="form_nb_mineur" value="<?= e($nb_mineur); ?>">
                    </div>
                    <div class="form-item">
                        <label for="form_nb_chien">Nombre de Chiens :</label>
                        <input class="form-control" type="text" name="form_nb_chien" id="form_nb_chien" value="<?= e($chien); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <!-- Prix non standard / Acompte -->
                    <div class="form-item">
                        <label for="form_prix">Prix :</label>
                        <input class="form-control" type="text" name="form_prix" id="form_prix" value="<?= e($prix); ?>">
                    </div>
                    <div class="form-item">
                        <label for="form_acompte">Acompte :</label>
                        <input class="form-control" type="text" name="form_acompte" id="form_acompte" value="<?= e($acompte); ?>">
                    </div>
                </div>
                <!-- Commentaire -->
                <div class="form-group">
                    <label for="form_commentaire">Commentaire :</label>
                    <textarea class="form-control" form-groups="2" name="form_commentaire" id="form_commentaire"><?= e($commentaire); ?></textarea>
                </div>
                <!-- Submit -->
                <div class="form-group">
                    <div class="form-item">
                        <button class="btn btn-primary btn-lg" type="submit">Valider</button>
                    </div>
                </div>
            </form>

            <a class="btn btn-primary btn-sm " href="facture.php?id=<?= $_GET['id'] ?>">
                PrintFacture
            </a>
    </section>
</main>
<?php
require_once './assets/elements/footer.php';

?>