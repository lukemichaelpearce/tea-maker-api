<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
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
     * @ORM\OneToMany(targetEntity="App\Entity\TeamMember", mappedBy="team")
     */
    private $teamMembers;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TeamMember", cascade={"persist", "remove"})
     */
    private $currentTeamMember;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeaMakerHistory", mappedBy="team")
     */
    private $teaMakerHistories;

    /**
     * Team constructor.
     */
    public function __construct()
    {
        $this->teamMembers = new ArrayCollection();
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
     * @return Team
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|TeamMember[]
     */
    public function getTeamMembers(): Collection
    {
        return $this->teamMembers;
    }

    /**
     * @param TeamMember $teamMember
     * @return Team
     */
    public function addTeamMember(TeamMember $teamMember): self
    {
        if (!$this->teamMembers->contains($teamMember)) {
            $this->teamMembers[] = $teamMember;
            $teamMember->setTeam($this);
        }

        return $this;
    }

    /**
     * @param TeamMember $teamMember
     * @return Team
     */
    public function removeTeamMember(TeamMember $teamMember): self
    {
        if ($this->teamMembers->contains($teamMember)) {
            $this->teamMembers->removeElement($teamMember);
            // set the owning side to null (unless already changed)
            if ($teamMember->getTeam() === $this) {
                $teamMember->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return TeamMember|null
     */
    public function getCurrentTeamMember(): ?TeamMember
    {
        return $this->currentTeamMember;
    }

    /**
     * @param TeamMember|null $currentTeamMember
     * @return Team
     */
    public function setCurrentTeamMember(?TeamMember $currentTeamMember): self
    {
        $this->currentTeamMember = $currentTeamMember;

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
     * @return Team
     */
    public function addTeaMakerHistory(TeaMakerHistory $teaMakerHistory): self
    {
        if (!$this->teaMakerHistories->contains($teaMakerHistory)) {
            $this->teaMakerHistories[] = $teaMakerHistory;
            $teaMakerHistory->setTeam($this);
        }

        return $this;
    }

    /**
     * @param TeaMakerHistory $teaMakerHistory
     * @return Team
     */
    public function removeTeaMakerHistory(TeaMakerHistory $teaMakerHistory): self
    {
        if ($this->teaMakerHistories->contains($teaMakerHistory)) {
            $this->teaMakerHistories->removeElement($teaMakerHistory);
            // set the owning side to null (unless already changed)
            if ($teaMakerHistory->getTeam() === $this) {
                $teaMakerHistory->setTeam(null);
            }
        }

        return $this;
    }
}
