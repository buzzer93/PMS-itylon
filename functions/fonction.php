<?php
/**
 * Configuration de la localisation pour le formatage des dates en français.
 */
setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'fr', 'fra', 'french');

/**
 * Alias plus court pour l'échappement HTML.
 *
 * @param string $str La chaîne à échapper.
 * @return string La chaîne échappée.
 */
function e(string $str): string
{
    return htmlspecialchars($str);
}

/**
 * Fonction pour afficher une variable de manière plus lisible.
 *
 * @param mixed $variable La variable à afficher.
 */
function dump($variable)
{
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
}

/**
 * Fonction améliorée de validation de formulaire utilisant des exceptions.
 *
 * @param mixed $field La valeur à valider.
 * @return mixed La valeur validée.
 * @throws InvalidArgumentException Si le champ est vide.
 */
function validateForm($field)
{
    if (empty($field)) {
        throw new InvalidArgumentException('Ce champ ne peut pas être vide');
    }
    return $field;
}

/**
 * Nettoie les données en échappant les caractères spéciaux et en supprimant les espaces vides.
 *
 * @param mixed $donnees Les données à nettoyer.
 * @return mixed Les données nettoyées.
 */
function checkInput($data): mixed
{
    if (is_string($data)) {
        return htmlspecialchars(trim($data));
    }
    // Si c'est un entier, convertissez-le en entier
    elseif (is_int($data)) {
        return intval($data);
    }
    // Sinon, retournez les données telles quelles
    else {
        return $data;
    }
}

/**
 * Fonction pour formater une date dans un format spécifique.
 *
 * @param string $date La date à formater.
 * @return string La date formatée.
 */
function dateFormat(string $date): string
{
    $dateTime = new DateTime($date);

    $moisFrancais = [
        '01' => 'janv', '02' => 'fév', '03' => 'mars',
        '04' => 'avr', '05' => 'mai', '06' => 'juin',
        '07' => 'juil', '08' => 'août', '09' => 'sep',
        '10' => 'oct', '11' => 'nov', '12' => 'déc'
    ];

    // Formater la date en utilisant le tableau $moisFrancais
    return $dateTime->format('d') . '-' . $moisFrancais[$dateTime->format('m')] . '-' . $dateTime->format('Y');
}

/**
 * Fonction pour générer les opérations possibles sur une réservation.
 *
 * @param int $ReservationId L'identifiant de la réservation.
 * @return string Le code HTML des boutons de modification et de suppression.
 */
function generateReservationOperations(int $ReservationId): string
{
    // Retourner le code HTML les boutons d'action
    return '
        <a class="btn btn-orange" href="update.php?id=' . $ReservationId . '">
           Modifier
        </a>
        <a class="btn btn-red" href="delete.php?id=' . $ReservationId . '">
           Supprimer
        </a>
        <a class="btn" href="facture.php?id=' . $ReservationId . '">
           Facture
        </a>';
}
?>
