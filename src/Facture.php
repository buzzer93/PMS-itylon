<?php

namespace App;

use DateInterval;
use DateTime;

/**
 * Class Facture
 *
 * Represents information about a bill.
 */
class Facture
{
    /**
     * @var string The date of the bill.
     */
    public string $date;

    /**
     * @var int The identifier for the reservation.
     */
    public int $id;

    /**
     * @var string The last name associated with the reservation.
     */
    public string $nom;

    /**
     * @var string The name of the house for the reservation.
     */
    public string $maison;

    /**
     * @var float The price for the stay.
     */
    public float $prixSejour;

    /**
     * @var array The array of days during the stay.
     */
    public array $jours;

    /**
     * @var float The tax price.
     */
    public float $prixTaxe;

    /**
     * @var float The cleaning fee price.
     */
    public float $prixMenage;

    /**
     * @var float The dog fee price.
     */
    public float $prixChien;

    /**
     * @var float The advance payment for the reservation.
     */
    public float $acompte;

    /**
     * @var float The total price of the bill.
     */
    public float $prixTotal;

    /**
     * @var int The booking information associated with the reservation.
     */
    public int $booking;

    /**
     * @var array The data associated with the house.
     */
    public array $maisonData;

    /**
     * @var array The data associated with the periods.
     */
    public array $periodsData;

    /**
     * Facture constructor.
     *
     * @param DateTime $date The date of the bill.
     * @param array $reservation An associative array containing reservation data.
     * @param array $maisonData An associative array containing data associated with the house.
     * @param array $periodsData An associative array containing data associated with the periods.
     */
    public function __construct(DateTime $date, array $reservation, array $maisonData, array $periodsData)
    {
        $this->date = $date->format('d/m/Y');
        $this->id = $reservation['id'];
        $this->nom = $reservation['nom'];
        $this->maison = ucfirst($reservation['maison']);
        $this->maisonData = $maisonData;
        $this->acompte = $reservation['acompte'];
        $this->jours = $this->detailSejour($reservation['arrivee'], $reservation['depart']);
        $this->prixSejour = $this->calculerPrixSejour($maisonData, $periodsData, $this->jours, $reservation['booking'], intval($reservation['prix']));
        $this->prixTaxe = count($this->jours) * $reservation['nb_adulte'];
        $this->prixMenage = intval($maisonData['menage']);
        $this->prixChien = intval($reservation['chien']) * 50;
        $this->prixTotal = intval($this->prixSejour) + intval($this->prixTaxe) + intval($this->prixMenage) + intval($this->prixChien);
        $this->booking = $reservation['booking'];
        $this->periodsData = $periodsData;
    }

    /**
     * Generate an array of dates between the arrival and departure dates.
     *
     * @param string $dateArrivee The arrival date.
     * @param string $dateDepart The departure date.
     * @return array The array of dates between the arrival and departure dates.
     */
    public function detailSejour(string $dateArrivee, string $dateDepart): array
    {
        $tableauDates = array();
        $date_debut = new DateTime($dateArrivee);
        $dateFin = new DateTime($dateDepart);
        $date_fin = $dateFin->sub(new DateInterval('P1D'));

        while ($date_debut <= $date_fin) {
            $tableauDates[] = $date_debut->format('Y-m-d');
            $date_debut->add(new DateInterval('P1D'));  // Ajouter 1 jour à la date debut
        }

        return $tableauDates;
    }

    /**
     * Calculate the price for the stay.
     *
     * @param array $maison An associative array containing data associated with the house.
     * @param array $periods An associative array containing data associated with the periods.
     * @param array $jours An array of days during the stay.
     * @param int $booking The booking information.
     * @param float $prix The original price for the stay (if provided).
     * @return float The calculated price for the stay.
     */
    public function calculerPrixSejour(array $maison, array $periods, array $jours, int $booking, float $prix): float
    {
        if ($prix === 0) {
            $prix_sejour = 0;
            if ($booking === 2) {
                foreach ($jours as $jour) {

                    $date_jour = new DateTime($jour);

                    foreach ($periods as $period) {
                        $start_date = new DateTime($period['start_date']);
                        $end_date = new DateTime($period['end_date']);
                        if ($date_jour >= $start_date && $date_jour <= $end_date) {
                            $prix_sejour += ($maison[str_replace(' ','_',$period['saison'])]) / 7;
                        }
                    }
                }
            }

            return round($prix_sejour);
        } else {
            return $prix;
        }
    }

    /**
     * Generate month data for the bill.
     *
     * @param DateTime $date_select The selected date.
     * @return array The month data for the bill.
     */
    public function getMonthData(DateTime $date_select): array
    {
        $monthData = [
            "id"=> $this-> id,
            "nom"=> $this->nom,
            "sejour" => 0,
            "taxe" => 0,
            "nuits" => 0,
            "menage"=> $this->prixMenage
        ];
        $datetime = $date_select;
        $startOfMonth = $datetime->modify('first day of this month')->format('Y-m-d');
        $endOfMonth = $datetime->modify('last day of this month')->format('Y-m-d');
            
            
                foreach ($this->jours as $jour) {
                    $date_jour = new DateTime($jour);
                    if($jour>=$startOfMonth && $jour <= $endOfMonth){
                        $monthData['taxe']+= $this->prixTaxe/count($this->jours);
                        $monthData['nuits'] += 1;
                        if($this->booking === 2){
                        foreach ($this->periodsData as $period) {
                            $start_date = new DateTime($period['start_date']);
                            $end_date = new DateTime($period['end_date']);
                            if ($date_jour >= $start_date && $date_jour <= $end_date) {
                                $monthData['sejour'] += round($this->maisonData[$period['saison']] / 7);
                            }
                        }
                    }
                }
            }
        
        return $monthData;
    }
}
