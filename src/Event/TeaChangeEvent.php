<?php
namespace App\Event;

use App\Entity\Team;
use Symfony\Component\EventDispatcher\Event;

class TeaChangeEvent extends Event
{
    const NAME = 'tea.change';

    protected $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function getTeam()
    {
        return $this->team;
    }
}