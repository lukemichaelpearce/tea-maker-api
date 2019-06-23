<?php

namespace App\Service;

use App\Entity\Team;
use App\Entity\TeamMember;
use App\Interfaces\TeamDataGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class TeamDataGenerator
 * @package App\Service
 */
class TeamDataGenerator implements TeamDataGeneratorInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * TeamDataGenerator constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    )
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param Team $team
     * @return array
     */
    public function generate(Team $team): array
    {
        $members = $team->getTeamMembers();

        // Hydrate any member data for the team
        $memberData = [];
        if (count($members)) {
            foreach ($members as $member) {
                $memberData[] = [
                    'id' => $member->getId(),
                    'name' => $member->getName()
                ];
            }
        }

        // Select the current team member if it exists
        $currentTeamMemberId = null;
        if ($team->getCurrentTeamMember() instanceof TeamMember) {
            $currentTeamMemberId = $team->getCurrentTeamMember()->getId();
        }

        // return all team data
        return [
            'id' => $team->getId(),
            'name' => $team->getName(),
            'currentTeamMemberId' => $currentTeamMemberId,
            'teamMembers' => $memberData
        ];
    }
}
