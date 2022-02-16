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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbPersonne;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeroDeCommande;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateResa;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="resaCoachings")
     */
    private $evenement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=Coffret::class, inversedBy="resaCoachings")
     */
    private $coffretProduit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $heuresCoffret;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityEvent;

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

    public function getNumeroDeCommande(): ?string
    {
        return $this->numeroDeCommande;
    }

    public function setNumeroDeCommande($numeroDeCommande): self
    {
        $this->numeroDeCommande = $numeroDeCommande;

        return $this;
    }

    public function getDateResa(): ?\DateTimeInterface
    {
        return $this->dateResa;
    }

    public function setDateResa(\DateTimeInterface $dateResa): self
    {
        $this->dateResa = $dateResa;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getCoffretProduit(): ?coffret
    {
        return $this->coffretProduit;
    }

    public function setCoffretProduit(?coffret $coffretProduit): self
    {
        $this->coffretProduit = $coffretProduit;

        return $this;
    }

    public function getHeuresCoffret(): ?int
    {
        return $this->heuresCoffret;
    }

    public function setHeuresCoffret(?int $heuresCoffret): self
    {
        $this->heuresCoffret = $heuresCoffret;

        return $this;
    }

    public function getQuantityEvent(): ?int
    {
        return $this->quantityEvent;
    }

    public function setQuantityEvent(int $quantityEvent): self
    {
        $this->quantityEvent = $quantityEvent;

        return $this;
    }
}
