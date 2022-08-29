<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity=Lieu::class, mappedBy="ville")
     */
    private $lieuList;

    public function __construct()
    {
        $this->lieuList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection<int, Lieu>
     */
    public function getLieuList(): Collection
    {
        return $this->lieuList;
    }

    public function addLieuList(Lieu $lieuList): self
    {
        if (!$this->lieuList->contains($lieuList)) {
            $this->lieuList[] = $lieuList;
            $lieuList->setVille($this);
        }

        return $this;
    }

    public function removeLieuList(Lieu $lieuList): self
    {
        if ($this->lieuList->removeElement($lieuList)) {
            // set the owning side to null (unless already changed)
            if ($lieuList->getVille() === $this) {
                $lieuList->setVille(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getNom();
    }
}
