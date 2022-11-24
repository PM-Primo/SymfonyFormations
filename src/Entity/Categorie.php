<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
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
    private $nom_categorie;

    /**
     * @ORM\OneToMany(targetEntity=ModuleFormation::class, mappedBy="categorie")
     */
    private $modulesFormation;

    public function __construct()
    {
        $this->modulesFormation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nom_categorie;
    }

    public function setNomCategorie(string $nom_categorie): self
    {
        $this->nom_categorie = $nom_categorie;

        return $this;
    }

    /**
     * @return Collection<int, ModuleFormation>
     */
    public function getModulesFormation(): Collection
    {
        return $this->modulesFormation;
    }

    public function addModulesFormation(ModuleFormation $modulesFormation): self
    {
        if (!$this->modulesFormation->contains($modulesFormation)) {
            $this->modulesFormation[] = $modulesFormation;
            $modulesFormation->setCategorie($this);
        }

        return $this;
    }

    public function removeModulesFormation(ModuleFormation $modulesFormation): self
    {
        if ($this->modulesFormation->removeElement($modulesFormation)) {
            // set the owning side to null (unless already changed)
            if ($modulesFormation->getCategorie() === $this) {
                $modulesFormation->setCategorie(null);
            }
        }

        return $this;
    }
}
