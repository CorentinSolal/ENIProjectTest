<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
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
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="campus")
     */
    private $sortieList;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="campus")
     */
    private $participantList;

    public function __construct()
    {
        $this->sortieList = new ArrayCollection();
        $this->participantList = new ArrayCollection();
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
            $sortieList->setCampus($this);
        }

        return $this;
    }

    public function removeSortieList(Sortie $sortieList): self
    {
        if ($this->sortieList->removeElement($sortieList)) {
            // set the owning side to null (unless already changed)
            if ($sortieList->getCampus() === $this) {
                $sortieList->setCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipantList(): Collection
    {
        return $this->participantList;
    }

    public function addParticipantList(Participant $participantList): self
    {
        if (!$this->participantList->contains($participantList)) {
            $this->participantList[] = $participantList;
            $participantList->setCampus($this);
        }

        return $this;
    }

    public function removeParticipantList(Participant $participantList): self
    {
        if ($this->participantList->removeElement($participantList)) {
            // set the owning side to null (unless already changed)
            if ($participantList->getCampus() === $this) {
                $participantList->setCampus(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNom();
    }
}
