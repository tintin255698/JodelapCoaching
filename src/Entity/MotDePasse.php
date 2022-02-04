<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class MotDePasse
{

    private $ancienMdp;

    /**
     * @Assert\Length(min=6, minMessage="Votre mot de passe doit faire au moins 6 caracteres !" )
     */

    private $nouveauMdp;


    /**
     * @Assert\EqualTo(propertyPath="nouveauMdp", message="Vous avez fait une erreur dans la confirmation de votre mot de passe" )
     */
    private $confirmerMdp;

    public function getAncienMdp(): ?string
    {
        return $this->ancienMdp;
    }

    public function setAncienMdp(string $ancienMdp): self
    {
        $this->ancienMdp = $ancienMdp;

        return $this;
    }

    public function getNouveauMdp(): ?string
    {
        return $this->nouveauMdp;
    }

    public function setNouveauMdp(string $nouveauMdp): self
    {
        $this->nouveauMdp = $nouveauMdp;

        return $this;
    }

    public function getConfirmerMdp(): ?string
    {
        return $this->confirmerMdp;
    }

    public function setConfirmerMdp(string $confirmerMdp): self
    {
        $this->confirmerMdp = $confirmerMdp;

        return $this;
    }
}
