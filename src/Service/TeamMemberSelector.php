<?php

namespace App\Service;

use App\Entity\TeamMember;
use App\Interfaces\TeamMemberSelectorInterface;
use http\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\Collection;
use http\Exception\RuntimeException;

/**
 * Class TeamMemberSelector
 * @package App\Service
 */
class TeamMemberSelector implements TeamMemberSelectorInterface
{

    /**
     * @param TeamMember[] $teamMembers
     * @return TeamMember
     */
    public function select(array $teamMembers): TeamMember
    {
        if (empty($teamMembers)) {
            throw new InvalidArgumentException('The team members array cannot be empty');
        }

        // TODO: It would be good to base the selection on a round robin approach to make it fair for all members of the team
        // simple random array selection
        $randKey = array_rand($teamMembers);
        if (!$teamMembers[$randKey] instanceof TeamMember) {
            throw new RuntimeException('The team member is not an instance of TeamMember');
        }

        // return the random team member
        return $teamMembers[$randKey];
    }
}
