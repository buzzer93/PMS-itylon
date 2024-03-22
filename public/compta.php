<?php

use App\DataBase;
use App\Facture;

require_once '../vendor/autoload.php';
require_once '../functions/auth.php';
login();
require_once '../functions/fonction.php';
$datetime = new DateTime();
if (isset($_GET['month_select'])) {
    $datetime = new DateTime($_GET['month_select']);
} 

$date_select = $datetime;
$date_select_formated = $datetime->format('Y-m');

$startOfMonth = $date_select->modify('first day of this month')->format('Y-m-d');
// Obtenir la fin du mois
$endOfMonth = $date_select->modify('last day of this month')->format('Y-m-d');

$db = Database::connect();
$reservation_select = $db->prepare("SELECT * FROM reservations WHERE (arrivee < :endOfMonth AND arrivee > :startOfMonth) OR (depart < :endOfMonth AND depart > :startOfMonth)");
$reservation_select->execute([':startOfMonth' => $startOfMonth, ':endOfMonth' => $endOfMonth]);
$reservations = $reservation_select->fetchAll(PDO::FETCH_ASSOC);
$factures = [];
$totals = [
    'total_sejour' => 0,
    'total_taxe' => 0,
    'total_menage' => 0,
    'total_nuits' => 0,
    'total_all' => 0
];
foreach ($reservations as $reservation) {
    // Récupération des données de la table 'maisons'
    $maison_select = $db->prepare("SELECT * FROM maisons WHERE name = :name");
    $maison_select->execute([':name' => $reservation['maison']]);
    $maison = $maison_select->fetch(PDO::FETCH_ASSOC);

    // Récupération des données de la table 'periods'
    $periods_select = $db->prepare("SELECT * FROM periods WHERE (:arrivee < end_date AND :arrivee > start_date) OR (:depart < end_date AND :depart > start_date)");
    $periods_select->execute([':arrivee' => $reservation['arrivee'], ':depart' => $reservation['depart']]);
    $periods = $periods_select->fetchAll(PDO::FETCH_ASSOC);
    

    // Création de l'objet Facture et calcul des prix
    $facture = new Facture(new DateTime(), $reservation, $maison, $periods);

    $factures[] = $facture->getMonthData($date_select);
}
foreach ($factures as $facture) {
    $totals['total_sejour'] += $facture['sejour'];
    $totals['total_taxe'] += $facture['taxe'];
    $totals['total_menage'] += $facture['menage'];
    $totals['total_nuits'] += $facture['nuits'];
}
$totals['total_all'] += $totals['total_sejour'] + $totals['total_taxe'] + $totals['total_menage'];



require_once './assets/elements/header.php';
?>

<div class="section compta">
    <h3 class="section-title">comptabilité</h3>
    <form class="filter-group" action="" method="get">
        <input class="form-control" type="month" name="month_select" id="month_select" value="<?$date_select_formated?>" >
        <button type="submit" class="btn">afficher</button>
    </form>
    <table class="compta-table" id="myTable">
        <thead>
            <th class="table-head">Nom</th>
            <th class="table-head">Sejour</th>
            <th class="table-head">Taxe</th>
            <th class="table-head">Menage</th>
            <th class="table-head">Nuits</th>
        </thead>
        <tbody>
            <?php foreach ($factures as $facture) : ?>
                <tr class="table-row">
                    <td class="td"><?= ucfirst($facture['nom']) ?></td>
                    <td class="td"><?= $facture['sejour'] ?></td>
                    <td class="td"><?= $facture['taxe'] ?></td>
                    <td class="td"><?= $facture['menage'] ?></td>
                    <td class="td"><?= $facture['nuits'] ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr class="table-row">
                <th scope="row" class="table-head"> Total </th>
                <td class="td"><?= $totals['total_sejour'] ?></td>
                <td class="td"><?= $totals['total_taxe'] ?></td>
                <td class="td"><?= $totals['total_menage'] ?></td>
                <td class="td"><?= $totals['total_nuits'] ?></td>
            </tr>
            <tr class="table-row">
                <th scope="row" class="table-head"> </th>
                <th scope="row" class="table-head">CA du Mois</th>
                <td class="td"><?= $totals['total_all'] ?></td>
                <td class="td"> </td>
                <td class="td"></td>
                <td class="td"></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php
require_once './assets/elements/footer.php';

?>