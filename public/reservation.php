<?php

use App\App;
use App\DataBase;

require_once '../vendor/autoload.php';

$user = App::getAuth()->get_user();
App::getAuth()->requireRole('admin','user');

require_once '../functions/fonction.php';
$pdo = Database::connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        
        $nom = $_POST['form_nom'];
        $prenom = $_POST['form_prenom'];
        $email = $_POST['form_mail'];
        $tel = $_POST['form_tel'];
        $maison = $_POST['form_maison'];
        $arrivee = $_POST['form_arrivee'];
        $depart = $_POST['form_depart'];
        $nb_adulte = $_POST['form_nb_adulte'];
        $nb_mineur = $_POST['form_nb_mineur'];
        $chien = $_POST['form_nb_chien'];
        $prix = $_POST['form_prix'];
        $acompte = $_POST['form_acompte'];
        $commentaire = $_POST['form_commentaire'];
        $booking = $_POST['form_booking'];


        $stmt = $pdo->prepare(
            "INSERT INTO reservations 
            (nom, prenom, email, tel, maison, arrivee, depart, nb_adulte, nb_mineur, chien, prix, acompte, commentaire, booking) 
            VALUES 
            (:nom, :prenom, :email, :tel, :maison, :arrivee, :depart, :nb_adulte, :nb_mineur, :chien, :prix, :acompte, :commentaire, :booking)"
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
            'booking' => $booking

        ])) {
            throw new RuntimeException($stmt ? $stmt->errorInfo()[2] : $pdo->errorInfo()[2]);
        }
        header('location: ./index.php');
        exit();
    } catch (Exception $e) {
        dump($e->getMessage());
    }
}
$stmt_select = $pdo->prepare("SELECT * FROM reservations ORDER BY arrivee ");
$stmt_select->execute();
$reservations = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

$stmt_maisons= $pdo->query('SELECT * FROM maisons');
$maisons= $stmt_maisons->fetchAll(PDO::FETCH_ASSOC);




require_once './assets/elements/header.php';
?>


<div class="top-div">
    <button type="button" class="btn" id="modal-btn">
        Nouvelle réservation
    </button>
</div>

<div class="modal" id="modal">
    <div class="modal-content" id="modal-content">
        <div class="modal-header">
            <h5 class="section-title">Nouvelle réservation</h5>

        </div>
        <div class="modal-body">
            <!==================FORMULAIRE==========================!>
                <form class="new-res-form" id="form_new_reservation" action="reservation.php" method="post" role="form">
                    <div class="form-group">
                        <div class="form-item">
                            <select class="form-select" name="form_booking" id="form_booking" required>
                                <option value="1">Booking</option>
                                <option value="2">Direct</option>

                            </select>
                        </div>
                        <div class="form-item">
                            <input class="form-control" id="form_nom" type="text" name="form_nom" placeholder="Nom *" required>
                            <input class="form-control" id="form_prenom" type="text" name="form_prenom" placeholder="Prénom">
                        </div>
                        <!=================Contact=============================================================!>
                            <div class="form-item">

                                <input class="form-control" type="email" name="form_mail" id="form_mail" placeholder="email: *">

                                <input class="form-control" type="tel" name="form_tel" id="form_tel" placeholder="Tél:">

                            </div>
                    </div>
                    <!=================Choix maison=============================================================!>
                        <div class="form-group">
                            <div class="form-item">
                                <select class="form-select" name="form_maison" id="form_maison" required>
                                    <option disabled selected>Maison:</option>
                                    <?php foreach ($maisons as $maison) : ?>
                                        <option value="<?= $maison['name'] ?>"><?= ucfirst($maison['name']) ?></option>
                                    <?php endforeach ?>
                                </select>
                                <!=================DATES=============================================================!>

                                    <input class="form-control" type="date" name="form_arrivee" id="form_arrivee" onchange="selectUpdate(this)" placeholder="Date arrivée: *" required>

                                    <input class="form-control" type="date" name="form_depart" id="form_depart" placeholder="Date depart: *" required>

                            </div>
                        </div>


                        <!=================Personne / chien=============================================================!>
                            <div class="form-group">
                                <div class="form-item">
                                    <input class="form-control" type="text" name="form_nb_adulte" id="form_nb_adulte" placeholder="Nb de personne majeur">
                                    <input class="form-control" type="text" name="form_nb_mineur" id="form_nb_mineur" placeholder="Nb de personne mineur">
                                    <input class="form-control" type="text" name="form_nb_chien" id="form_nb_chien" placeholder="Nb chiens:">
                                </div>

                                <!================= prix non standard / Acompte=============================================================!>

                                    <div class="form-item">
                                        <input class="form-control" type="text" name="form_prix" id="form_prix" placeholder="prix:">
                                        <input class="form-control" type="text" name="form_acompte" id="form_acompte" placeholder="Acompte:">
                                    </div>
                            </div>
                            <!=================Commentaire=============================================================!>
                                <div class="form-group">
                                    <div>
                                        <textarea class="form-control" rows="2" name="form_commentaire" id="form_commentaire" placeholder="Commentaire"></textarea>
                                    </div>
                                </div>
                                <!=================Submit=============================================================!>
                                    <div class="form-group">
                                        <div class="form-item">
                                            <button type="button" class="btn btn-red" id="modal-btn-close">Annuler</button>
                                            <button class="btn form-btn" type="submit">Valider</button>
                                        </div>
                                    </div>
                </form>
        </div>
    </div>
</div>
<div class="section reservation" id="rechercher-reservation">
    <h3 class="section-title">Rechercher une reservation</h3>
    <div class="filter-group">
        <input type="text" id="filterInput" class="form-control" placeholder="Filtrer par nom">
    </div>
    <table class="reservation-table" id="myTable">
        <thead >
                <th>Nom</th>
                <th>Maison</th>
                <th>arrivée</th>
                <th>Départ</th>
                <th>Action</th>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation) : ?>
                <tr class="table-row">
                    <td class="td"><?= $reservation['nom'] ?></td>
                    <td class="td"><?= ucfirst($reservation['maison']) ?></td>
                    <td class="td"><?= dateFormat($reservation['arrivee']) ?></td>
                    <td class="td"><?= dateFormat($reservation['depart']) ?></td>
                    <td class="td-action"><?= generateReservationOperations($reservation['id']) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<script src="<?= DIRECTORY_SEPARATOR . 'assets/js/filter.js' ?>"></script>
<?php
require_once './assets/elements/footer.php';
?>