<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
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
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $descriptif;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $niveau;

    /**
     * @ORM\Column(type="string", length=255, nullable=true )
     */
    private $lieu;

    /**
     * @ORM\Column(type="time")
     */
    private $finSession;

    /**
     * @ORM\Column(type="date")
     */
    private $finResa;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $materiel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $protectionObligatoire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $protectionConseillees;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $autres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieuPrecis;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=ResaCoaching::class, mappedBy="evenement")
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

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

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(?string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getFinSession(): ?\DateTimeInterface
    {
        return $this->finSession;
    }

    public function setFinSession(\DateTimeInterface $finSession): self
    {
        $this->finSession = $finSession;

        return $this;
    }

    public function getFinResa(): ?\DateTimeInterface
    {
        return $this->finResa;
    }

    public function setFinResa(\DateTimeInterface $finResa): self
    {
        $this->finResa = $finResa;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getMateriel(): ?string
    {
        return $this->materiel;
    }

    public function setMateriel(string $materiel): self
    {
        $this->materiel = $materiel;

        return $this;
    }

    public function getProtectionObligatoire(): ?string
    {
        return $this->protectionObligatoire;
    }

    public function setProtectionObligatoire(string $protectionObligatoire): self
    {
        $this->protectionObligatoire = $protectionObligatoire;

        return $this;
    }

    public function getProtectionConseillees(): ?string
    {
        return $this->protectionConseillees;
    }

    public function setProtectionConseillees(string $protectionConseillees): self
    {
        $this->protectionConseillees = $protectionConseillees;

        return $this;
    }

    public function getAutres(): ?string
    {
        return $this->autres;
    }

    public function setAutres(string $autres): self
    {
        $this->autres = $autres;

        return $this;
    }

    public function getLieuPrecis(): ?string
    {
        return $this->lieuPrecis;
    }

    public function setLieuPrecis(string $lieuPrecis): self
    {
        $this->lieuPrecis = $lieuPrecis;

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
            $resaCoaching->setEvenement($this);
        }

        return $this;
    }

    public function removeResaCoaching(ResaCoaching $resaCoaching): self
    {
        if ($this->resaCoachings->removeElement($resaCoaching)) {
            // set the owning side to null (unless already changed)
            if ($resaCoaching->getEvenement() === $this) {
                $resaCoaching->setEvenement(null);
            }
        }

        return $this;
    }

}
