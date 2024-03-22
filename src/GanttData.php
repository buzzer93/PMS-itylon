<?php
namespace App;

/**
 * Class GanttData
 *
 * Represents data for Gantt chart visualization.
 */
class GanttData
{
    /**
     * @var int The identifier for the data.
     */
    public int $id;

    /**
     * @var array The x-axis data representing the time range [arrival, departure].
     */
    public array $x;

    /**
     * @var string The y-axis data representing the location.
     */
    public string $y;

    /**
     * @var string The last name associated with the data.
     */
    public string $nom;

    /**
     * @var string The first name associated with the data.
     */
    public string $prenom;

    /**
     * @var int The booking information associated with the data.
     */
    public int $booking;

    /**
     * GanttData constructor.
     *
     * @param array $reservation An associative array containing reservation data.
     *                          Should have keys: 'id', 'arrivee', 'depart', 'maison', 'nom', 'prenom', 'booking'.
     */
    public function __construct(array $reservation)
    {
        $this->id = (int) $reservation['id'];
        $this->x = [(string) $reservation['arrivee'], (string) $reservation['depart']];
        $this->y = ucfirst((string) $reservation['maison']);
        $this->nom = (string) $reservation['nom'];
        $this->prenom = (string) $reservation['prenom'];
        $this->booking = (int) $reservation['booking'];
    }
}
