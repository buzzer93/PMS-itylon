<?php
namespace App;

/**
 * Class Maison
 *
 * Represents information about a house or property.
 */
class Maison
{
    /**
     * @var int The id of the house.
     */
    public int $id;
    
    /**
     * @var string The name of the house.
     */
    public string $name;

    /**
     * @var float The price during low season.
     */
    public float $bassesSaison;

    /**
     * @var float The price during average season.
     */
    public float $moyenneSaison;

    /**
     * @var float The price during high season.
     */
    public float $hauteSaison;

    /**
     * @var float The price during very high season.
     */
    public float $tresHauteSaison;

    /**
     * @var float The cleaning fee for the house.
     */
    public float $menage;

    /**
     * Maison constructor.
     * 
     * @param int $id The id of the house.
     * @param string $name The name of the house.
     * @param float $bassesSaison The price during low season.
     * @param float $moyenneSaison The price during average season.
     * @param float $hauteSaison The price during high season.
     * @param float $tresHauteSaison The price during very high season.
     * @param float $menage The cleaning fee for the house.
     */
    public function __construct(int $id, string $name, float $bassesSaison, float $moyenneSaison, float $hauteSaison, float $tresHauteSaison, float $menage)
    {
        $this->id = $id;
        $this->name = ucfirst($name);
        $this->bassesSaison = $bassesSaison;
        $this->moyenneSaison = $moyenneSaison;
        $this->hauteSaison = $hauteSaison;
        $this->tresHauteSaison = $tresHauteSaison;
        $this->menage = $menage;
    }
}
