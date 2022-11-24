<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgrammeRepository::class)
 */
class Programme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=SessionFormation::class, inversedBy="programmes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sessionFormation;

    /**
     * @ORM\ManyToOne(targetEntity=ModuleFormation::class, inversedBy="programmes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $moduleFormation;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_jours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionFormation(): ?SessionFormation
    {
        return $this->sessionFormation;
    }

    public function setSessionFormation(?SessionFormation $sessionFormation): self
    {
        $this->sessionFormation = $sessionFormation;

        return $this;
    }

    public function getModuleFormation(): ?ModuleFormation
    {
        return $this->moduleFormation;
    }

    public function setModuleFormation(?ModuleFormation $moduleFormation): self
    {
        $this->moduleFormation = $moduleFormation;

        return $this;
    }

    public function getNbJours(): ?int
    {
        return $this->nb_jours;
    }

    public function setNbJours(int $nb_jours): self
    {
        $this->nb_jours = $nb_jours;

        return $this;
    }
}
