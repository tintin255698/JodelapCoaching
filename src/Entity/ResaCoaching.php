<?php

namespace App\Entity;

use App\Repository\ResaCoachingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResaCoachingRepository::class)
 */
class ResaCoaching
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="resaCoachings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=CoachingTarif::class, inversedBy="resaCoachings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $coaching;

    /**
     * @ORM\Column(type="boolean")
     */
    private $resaConfirm;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPersonne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCoaching(): ?coachingTarif
    {
        return $this->coaching;
    }

    public function setCoaching(?coachingTarif $coaching): self
    {
        $this->coaching = $coaching;

        return $this;
    }

    public function getResaConfirm(): ?bool
    {
        return $this->resaConfirm;
    }

    public function setResaConfirm(bool $resaConfirm): self
    {
        $this->resaConfirm = $resaConfirm;

        return $this;
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

    public function getNbPersonne(): ?int
    {
        return $this->nbPersonne;
    }

    public function setNbPersonne(int $nbPersonne): self
    {
        $this->nbPersonne = $nbPersonne;

        return $this;
    }
}
