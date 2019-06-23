<?php

namespace App\Interfaces;

use App\Entity\TeamMember;

/**
 * Interface TeamMemberSelectorInterface
 * @package App\Interfaces
 */
interface TeamMemberSelectorInterface
{
    /**
     * @param array $teamMembers
     * @return TeamMember
     */
    public function select(array $teamMembers): TeamMember;
}