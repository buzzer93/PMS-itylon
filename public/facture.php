<?php
use App\DataBase;
use App\Facture;
use PhpOffice\PhpSpreadsheet\IOFactory;

require_once '../vendor/autoload.php';
require_once '../functions/auth.php';
login();
require_once '../functions/fonction.php';

$db = Database::connect();
$reservation_select = $db->prepare("SELECT * FROM reservations WHERE id = :id");
$reservation_select->execute(['id' => $_GET['id']]);
$reservation = $reservation_select->fetch(PDO::FETCH_ASSOC);

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

// Charger le modèle de facture au format Excel (.xlsx)
$spreadsheet = IOFactory::load('model facture.xlsx');
$sheet = $spreadsheet->getActiveSheet();

// Remplacer des valeurs dans des cellules spécifiques
$sheet->setCellValue('G3', $facture->date);
$sheet->setCellValue('G4', $facture->id);
$sheet->setCellValue('B10', $facture->nom);
$sheet->setCellValue('B11', $facture->maison);
if ($facture->prixSejour > 0) {
    $sheet->setCellValue('H15', $facture->prixSejour);
}

if ($facture->prixTaxe > 0) {
    $sheet->setCellValue('H16', $facture->prixTaxe);
}

if ($facture->prixMenage > 0) {
    $sheet->setCellValue('H17', $facture->prixMenage);
}



if($facture->prixChien > 0){
    $sheet->setCellValue('A18', 'Chien');
    $sheet->setCellValue('H18', $facture->prixChien);
}

if ($facture->acompte > 0) {
    $sheet->setCellValue('H19', $facture->acompte);
}
// Sauvegarder la facture mise à jour
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('./archive/facture'. $reservation['id'].'-'.$reservation['arrivee'].'.xlsx');


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <a id="DL"  href='<?= './archive/facture'. $reservation['id'].'-'.$reservation['arrivee'].'.xlsx'?>' download='<?='../archive/facture'. $reservation['id'].'-'.$reservation['arrivee'].'.xlsx'?>' ></a>
    <script>
        function autoDl() {
            setTimeout(() => {
                dl.click();
            }, 1000)
        }
        dl = document.getElementById('DL');
        dl.onload = autoDl();
        setTimeout(() => {
            window.location.href = 'index.php'; 
            }, 2000)
        
        
    </script>
</body>

</html>