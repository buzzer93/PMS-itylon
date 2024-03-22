<?php
namespace App;

/**
 * Class Saison
 *
 * Represents information about a season.
 */
class Saison
{
    /**
     * @var string The name of the season.
     */
    public string $name;

    /**
     * @var array The period during which the season occurs.
     */
    public array $periods;

    /**
     * Saison constructor.
     *
     * @param string $name The name of the season.
     * @param array $periods The periods during which the season occurs.
     */
    public function __construct(string $name, array $periods)
    {
        $this->name = ucfirst($name);
        $this->periods = $periods;
    }
}
