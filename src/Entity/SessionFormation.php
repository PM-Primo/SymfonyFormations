<?php

namespace App\Entity;

use App\Repository\SessionFormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionFormationRepository::class)
 */
class SessionFormation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_places;

    /**
     * @ORM\Column(type="date")
     */
    private $date_debut;

    /**
     * @ORM\Column(type="date")
     */
    private $date_fin;

    /**
     * @ORM\ManyToMany(targetEntity=Stagiaire::class, inversedBy="sessionsFormation")
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity=Programme::class, mappedBy="sessionFormation", orphanRemoval=true)
     */
    private $programmes;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $intitule_session;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->programmes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nb_places;
    }

    public function setNbPlaces(int $nb_places): self
    {
        $this->nb_places = $nb_places;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    /**
     * @return Collection<int, Stagiaire>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Stagiaire $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Stagiaire $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes[] = $programme;
            $programme->setSessionFormation($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): self
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getSessionFormation() === $this) {
                $programme->setSessionFormation(null);
            }
        }

        return $this;
    }

    public function getIntituleSession(): ?string
    {
        return $this->intitule_session;
    }

    public function setIntituleSession(string $intitule_session): self
    {
        $this->intitule_session = $intitule_session;

        return $this;
    }
}
