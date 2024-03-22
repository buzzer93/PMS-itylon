<?php

namespace App;

use DateTime;

/**
 * Class Reservation
 *
 * Represents information about a reservation.
 */
class Reservation
{
    /**
     * @var int The identifier for the reservation.
     */
    public int $id;

    /**
     * @var string The last name associated with the reservation.
     */
    public string $nom;

    /**
     * @var string The first name associated with the reservation.
     */
    public string $prenom;

    /**
     * @var string The email address associated with the reservation.
     */
    public string $email;

    /**
     * @var string The phone number associated with the reservation.
     */
    public string $tel;

    /**
     * @var string The name of the house for the reservation.
     */
    public string $maison;

    /**
     * @var DateTime The arrival date for the reservation.
     */
    public DateTime $arrivee;

    /**
     * @var DateTime The departure date for the reservation.
     */
    public DateTime $depart;

    /**
     * @var int The number of people for the reservation.
     */
    public int $nb_adulte;
    
    /**
     * @var int The number of people for the reservation.
     */
    public int $nb_mineur;

    /**
     * @var bool Whether there are dogs (chien) for the reservation.
     */
    public bool $chien;

    /**
     * @var float The price associated with the reservation.
     */
    public float $prix;

    /**
     * @var float The advance payment (acompte) for the reservation.
     */
    public float $acompte;

    /**
     * @var string Any additional comments for the reservation.
     */
    public string $commentaire;

    /**
     * @var string The booking information associated with the reservation.
     */
    public string $booking;

    /**
     * Reservation constructor.
     *
     * @param int $id The identifier for the reservation.
     * @param string $nom The last name associated with the reservation.
     * @param string $prenom The first name associated with the reservation.
     * @param string $email The email address associated with the reservation.
     * @param string $tel The phone number associated with the reservation.
     * @param string $maison The name of the house for the reservation.
     * @param DateTime $arrivee The arrival date for the reservation.
     * @param DateTime $depart The departure date for the reservation.
     * @param int $nb_adulte The number of adult for the reservation.
     * @param int $nb_mineur The number of child for the reservation.
     * @param bool $chien Whether there are dogs (chien) for the reservation.
     * @param float $prix The price associated with the reservation.
     * @param float $acompte The advance payment (acompte) for the reservation.
     * @param string $commentaire Any additional comments for the reservation.
     * @param string $booking The booking information associated with the reservation.
     */
    public function __construct(
        int $id,
        string $nom,
        string $prenom,
        string $email,
        string $tel,
        string $maison,
        DateTime $arrivee,
        DateTime $depart,
        int $nb_adulte,
        int $nb_mineur,
        bool $chien,
        float $prix,
        float $acompte,
        string $commentaire,
        string $booking
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->tel = $tel;
        $this->maison = $maison;
        $this->arrivee = $arrivee;
        $this->depart = $depart;
        $this->nb_adulte = $nb_adulte;
        $this->nb_mineur = $nb_mineur;
        $this->chien = $chien;
        $this->prix = $prix;
        $this->acompte = $acompte;
        $this->commentaire = $commentaire;
        $this->booking = $booking;
    }
}
