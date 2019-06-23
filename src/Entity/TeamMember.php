<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamMemberRepository")
 */
class TeamMember
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="teamMembers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeaMakerHistory", mappedBy="teamMember")
     */
    private $teaMakerHistories;

    /**
     * TeamMember constructor.
     */
    public function __construct()
    {
        $this->teaMakerHistories = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TeamMember
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Team|null
     */
    public function getTeam(): ?Team
    {
        return $this->team;
    }

    /**
     * @param Team|null $team
     * @return TeamMember
     */
    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return Collection|TeaMakerHistory[]
     */
    public function getTeaMakerHistories(): Collection
    {
        return $this->teaMakerHistories;
    }

    /**
     * @param TeaMakerHistory $teaMakerHistory
     * @return TeamMember
     */
    public function addTeaMakerHistory(TeaMakerHistory $teaMakerHistory): self
    {
        if (!$this->teaMakerHistories->contains($teaMakerHistory)) {
            $this->teaMakerHistories[] = $teaMakerHistory;
            $teaMakerHistory->setTeamMember($this);
        }

        return $this;
    }

    /**
     * @param TeaMakerHistory $teaMakerHistory
     * @return TeamMember
     */
    public function removeTeaMakerHistory(TeaMakerHistory $teaMakerHistory): self
    {
        if ($this->teaMakerHistories->contains($teaMakerHistory)) {
            $this->teaMakerHistories->removeElement($teaMakerHistory);
            // set the owning side to null (unless already changed)
            if ($teaMakerHistory->getTeamMember() === $this) {
                $teaMakerHistory->setTeamMember(null);
            }
        }

        return $this;
    }
}
