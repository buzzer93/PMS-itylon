<?php
namespace App;

/**
 * Class Period
 * Représente une période avec un nom, une date de début et une date de fin.
 */
class Period {

    /**
     * @var string Le nom de la période.
     */
    public string $name;

    /**
     * @var string La date de début de la période.
     */
    public string $start_date;

    /**
     * @var string La date de fin de la période.
     */
    public string $end_date;

    /**
     * @var string Le nom de la saison de cette période.
     */
    public string $saison;

    /**
     * Constructeur de la classe Period.
     *
     * @param string $name Le nom de la période.
     * @param string $start_date La date de début de la période.
     * @param string $end_date La date de fin de la période.
     * @param string $saison Le nom de la saison de cette période.
     */
    public function __construct(string $name, string $start_date, string $end_date, string $saison) {
        $this->name = $name;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->saison = $saison;
    }
}
