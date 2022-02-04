<?php

namespace App\Entity;

use App\Repository\CoffretRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoffretRepository::class)
 */
class Coffret
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $produit;

    /**
     * @ORM\OneToMany(targetEntity=ResaCoaching::class, mappedBy="coffretProduit")
     */
    private $resaCoachings;

    public function __construct()
    {
        $this->resaCoachings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getProduit(): ?string
    {
        return $this->produit;
    }

    public function setProduit(string $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * @return Collection|ResaCoaching[]
     */
    public function getResaCoachings(): Collection
    {
        return $this->resaCoachings;
    }

    public function addResaCoaching(ResaCoaching $resaCoaching): self
    {
        if (!$this->resaCoachings->contains($resaCoaching)) {
            $this->resaCoachings[] = $resaCoaching;
            $resaCoaching->setCoffretProduit($this);
        }

        return $this;
    }

    public function removeResaCoaching(ResaCoaching $resaCoaching): self
    {
        if ($this->resaCoachings->removeElement($resaCoaching)) {
            // set the owning side to null (unless already changed)
            if ($resaCoaching->getCoffretProduit() === $this) {
                $resaCoaching->setCoffretProduit(null);
            }
        }

        return $this;
    }
}
