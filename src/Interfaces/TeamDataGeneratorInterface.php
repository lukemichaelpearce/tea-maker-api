<?php

namespace App\Interfaces;

use App\Entity\Team;

/**
 * Interface TeamDataGeneratorInterface
 * @package App\Interfaces
 */
interface TeamDataGeneratorInterface
{
    /**
     * @param Team $team
     * @return array
     */
    public function generate(Team $team):array;
}