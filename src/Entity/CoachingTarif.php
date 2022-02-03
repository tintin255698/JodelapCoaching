<?php

namespace App\Entity;

use App\Repository\CoachingTarifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CoachingTarifRepository::class)
 */
class CoachingTarif
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
    private $heure;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceForTwo;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceForThree;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceUnity;

    /**
     * @ORM\OneToMany(targetEntity=ResaCoaching::class, mappedBy="coaching", orphanRemoval=true)
     */
    private $resaCoachings;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    public function __construct()
    {
        $this->resaCoachings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeure(): ?int
    {
        return $this->heure;
    }

    public function setHeure(int $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getPriceForTwo(): ?int
    {
        return $this->priceForTwo;
    }

    public function setPriceForTwo(int $priceForTwo): self
    {
        $this->priceForTwo = $priceForTwo;

        return $this;
    }

    public function getPriceForThree(): ?int
    {
        return $this->priceForThree;
    }

    public function setPriceForThree(int $priceForThree): self
    {
        $this->priceForThree = $priceForThree;

        return $this;
    }

    public function getPriceUnity(): ?int
    {
        return $this->priceUnity;
    }

    public function setPriceUnity(int $priceUnity): self
    {
        $this->priceUnity = $priceUnity;

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
            $resaCoaching->setCoaching($this);
        }

        return $this;
    }

    public function removeResaCoaching(ResaCoaching $resaCoaching): self
    {
        if ($this->resaCoachings->removeElement($resaCoaching)) {
            // set the owning side to null (unless already changed)
            if ($resaCoaching->getCoaching() === $this) {
                $resaCoaching->setCoaching(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }
}
