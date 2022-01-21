<?php

namespace App\Entity;

use App\Repository\CoachingTarifRepository;
use Doctrine\ORM\Mapping as ORM;

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
}
