<?php

use App\App;
use App\DataBase;
use App\Maison;
use App\Period;
use App\Saison;

require_once '../vendor/autoload.php';
require_once '../functions/fonction.php';
$user = App::getAuth()->get_user();
App::getAuth()->requireRole('admin');

$pdo = Database::connect();
$saisons_select = $pdo->query("SELECT DISTINCT saison FROM periods");
$saisons = array(); // Tableau qui contiendra la liste des saisons
$periods_list = array();
while ($saison = $saisons_select->fetch(PDO::FETCH_ASSOC)) {

    // Requête pour récupérer les periods de la saison en cours de traitement
    $periods_select = $pdo->prepare('SELECT * FROM periods WHERE saison = :saison');
    $periods_select->bindParam(':saison', $saison['saison']);
    $periods_select->execute();

    while ($period = $periods_select->fetch(PDO::FETCH_ASSOC)) {
        $periods = array(); // Tableau qui contiendra la liste des periods de la saison
        $periods[] = new Period(
            $period['name'],
            $period['start_date'],
            $period['end_date'],
            $period['saison']
        );
        $saisons[] = new Saison($saison['saison'], $periods);
    }
}

$pdo = Database::connect();
$stmt_select = $pdo->query("SELECT * FROM maisons");
$maisons_data = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
foreach ($maisons_data as $maison_data) {
    $maison = new Maison(
        $maison_data['id'],
        $maison_data['name'],
        $maison_data['basse_saison'],
        $maison_data['moyenne_saison'],
        $maison_data['haute_saison'],
        $maison_data['tres_haute_saison'],
        $maison_data['menage']
    );
    $maisons[] = $maison;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérer les données du formulaire
        $basseSaisons = $_POST['basseSaison'];
        $moyenneSaisons = $_POST['moyenneSaison'];
        $hauteSaisons = $_POST['hauteSaison'];
        $tresHauteSaisons = $_POST['tresHauteSaison'];
        $menages = $_POST['menage'];
        $periods_names = $_POST['period_name'];
        $start_dates = $_POST['start_date'];
        $end_dates = $_POST['end_date'];

        // Se connecter à la base de données
        $pdo = Database::connect();
        // Parcourir les données du formulaire et mettre à jour la base de données
        for ($i = 0; $i < count($maisons); $i++) {

            $maisons_update = $pdo->prepare("UPDATE maisons SET basse_saison=?, moyenne_saison=?, haute_saison=?, tres_haute_saison=?, menage=? WHERE name=?");
            $maisons_update->execute([$basseSaisons[$i], $moyenneSaisons[$i], $hauteSaisons[$i], $tresHauteSaisons[$i], $menages[$i], strtolower($maisons[$i]->name)]);
        }
        for ($i = 0; $i < count($periods_names); $i++) {
            $periods_update = $pdo->prepare("UPDATE periods SET start_date=?, end_date=? WHERE name=?");
            $periods_update->execute([$start_dates[$i], $end_dates[$i], $periods_names[$i]]);
        }
    } catch (PDOException $e) {
        // Afficher les détails de l'erreur
        die("Erreur PDO : " . $e->getMessage());
    }
    // Rediriger ou afficher un message de succès
    header("Refresh:0.1");
}


require_once './assets/elements/header.php';
?>
<section class="section gestion-section">
    <h1 class="section-title">gestion tarifs et periodes</h1>
    <form class="gestion-form" action="gestion.php" method="post">
        <table class="maison-form">
            <thead>
                <th scope="col" class="table-head">Nom</th>
                <th scope="col" class="table-head">Basses Saison</th>
                <th scope="col" class="table-head">Moyenne Saison</th>
                <th scope="col" class="table-head">Haute Saison</th>
                <th scope="col" class="table-head">Très Haute Saison</th>
                <th scope="col" class="table-head">Menage</th>
            </thead>
            <tbody>
                <?php foreach ($maisons as $maison) : ?>
                    <tr>
                        <th scope="row" class="form-label"><?= $maison->name ?></th>
                        <td><input class="form-control" type="text" name="basseSaison[]" value="<?= $maison->bassesSaison ?>"></td>
                        <td><input class="form-control" type="text" name="moyenneSaison[]" value="<?= $maison->moyenneSaison ?>"></td>
                        <td><input class="form-control" type="text" name="hauteSaison[]" value="<?= $maison->hauteSaison ?>"></td>
                        <td><input class="form-control" type="text" name="tresHauteSaison[]" value="<?= $maison->tresHauteSaison ?>"></td>
                        <td><input class="form-control" type="text" name="menage[]" value="<?= $maison->menage ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <table class="period-form">
            <?php foreach ($saisons as $saison) : ?>

                <?php foreach ($saison->periods as $period) : ?>

                    <tr>
                        <td>
                            <h4 class="form-label"><?= $period->name ?></h4>
                        </td>
                        <td><input class="form-control" type="hidden" name="period_name[]" value="<?= $period->name ?>"><input class="form-control" type="date" name="start_date[]" value="<?= $period->start_date ?>"></td>
                        <td><input class="form-control" type="date" name="end_date[]" value="<?= $period->end_date ?>"></td>
                    </tr>
                <?php endforeach; ?>

            <?php endforeach; ?>
        </table>
        <input class="btn btn-primary" type="submit" value="Mettre à Jour">
    </form>
</section>
<?php
require_once './assets/elements/footer.php';
?>