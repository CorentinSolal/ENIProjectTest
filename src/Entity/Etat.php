<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
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
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="etat")
     */
    private $sortieList;

    public function __construct()
    {
        $this->sortieList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortieList(): Collection
    {
        return $this->sortieList;
    }

    public function addSortieList(Sortie $sortieList): self
    {
        if (!$this->sortieList->contains($sortieList)) {
            $this->sortieList[] = $sortieList;
            $sortieList->setEtat($this);
        }

        return $this;
    }

    public function removeSortieList(Sortie $sortieList): self
    {
        if ($this->sortieList->removeElement($sortieList)) {
            // set the owning side to null (unless already changed)
            if ($sortieList->getEtat() === $this) {
                $sortieList->setEtat(null);
            }
        }

        return $this;
    }
}
