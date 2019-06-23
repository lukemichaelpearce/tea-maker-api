<?php
namespace App\EventListener;

use App\Entity\TeaMakerHistory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class TeaChangeListener
 * @package App\EventListener
 */
class TeaChangeListener
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * TeaChangeListener constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Responsible for adding history when a new tea maker is set
     *
     * @param Event $event
     */
    public function onTeaChange(Event $event)
    {
        $team = $event->getTeam();

        $teaMakerHistory = new TeaMakerHistory();
        $teaMakerHistory->setTeam($team);
        $teaMakerHistory->setTeamMember($team->getCurrentTeamMember());
        $teaMakerHistory->setCreated(new \DateTime());

        $this->entityManager->persist($teaMakerHistory);
        $this->entityManager->flush();
    }
}