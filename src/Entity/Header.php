<?php

namespace App\Entity;

use App\Repository\HeaderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HeaderRepository::class)
 */
class Header
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
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $texte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $btn1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $btn2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlBtn1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlBtn2;

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getBtn1(): ?string
    {
        return $this->btn1;
    }

    public function setBtn1(string $btn1): self
    {
        $this->btn1 = $btn1;

        return $this;
    }

    public function getBtn2(): ?string
    {
        return $this->btn2;
    }

    public function setBtn2(string $btn2): self
    {
        $this->btn2 = $btn2;

        return $this;
    }

    public function getUrlBtn1(): ?string
    {
        return $this->urlBtn1;
    }

    public function setUrlBtn1(string $urlBtn1): self
    {
        $this->urlBtn1 = $urlBtn1;

        return $this;
    }

    public function getUrlBtn2(): ?string
    {
        return $this->urlBtn2;
    }

    public function setUrlBtn2(string $urlBtn2): self
    {
        $this->urlBtn2 = $urlBtn2;

        return $this;
    }
}
