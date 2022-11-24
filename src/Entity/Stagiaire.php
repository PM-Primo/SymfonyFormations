<?php

namespace App\Entity;

use App\Repository\StagiaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StagiaireRepository::class)
 */
class Stagiaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom_stagiaire;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom_stagiaire;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $sexe;

    /**
     * @ORM\Column(type="date")
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $telephone;

    /**
     * @ORM\ManyToMany(targetEntity=SessionFormation::class, mappedBy="participants")
     */
    private $sessionsFormation;

    public function __construct()
    {
        $this->sessionsFormation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomStagiaire(): ?string
    {
        return $this->nom_stagiaire;
    }

    public function setNomStagiaire(string $nom_stagiaire): self
    {
        $this->nom_stagiaire = $nom_stagiaire;

        return $this;
    }

    public function getPrenomStagiaire(): ?string
    {
        return $this->prenom_stagiaire;
    }

    public function setPrenomStagiaire(string $prenom_stagiaire): self
    {
        $this->prenom_stagiaire = $prenom_stagiaire;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, SessionFormation>
     */
    public function getSessionsFormation(): Collection
    {
        return $this->sessionsFormation;
    }

    public function addSessionsFormation(SessionFormation $sessionsFormation): self
    {
        if (!$this->sessionsFormation->contains($sessionsFormation)) {
            $this->sessionsFormation[] = $sessionsFormation;
            $sessionsFormation->addParticipant($this);
        }

        return $this;
    }

    public function removeSessionsFormation(SessionFormation $sessionsFormation): self
    {
        if ($this->sessionsFormation->removeElement($sessionsFormation)) {
            $sessionsFormation->removeParticipant($this);
        }

        return $this;
    }
}
