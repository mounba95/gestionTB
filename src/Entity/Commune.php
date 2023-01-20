<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommuneRepository")
 */
class Commune
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $codeCommune;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomCommune;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departement", inversedBy="communes")
     */
    private $departement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Centre", mappedBy="commune")
     */
    private $centres;

    public function __construct()
    {
        $this->centres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCommune(): ?string
    {
        return $this->codeCommune;
    }

    public function setCodeCommune(?string $codeCommune): self
    {
        $this->codeCommune = $codeCommune;

        return $this;
    }

    public function getNomCommune(): ?string
    {
        return $this->nomCommune;
    }

    public function setNomCommune(?string $nomCommune): self
    {
        $this->nomCommune = $nomCommune;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection|Centre[]
     */
    public function getCentres(): Collection
    {
        return $this->centres;
    }

    public function addCentre(Centre $centre): self
    {
        if (!$this->centres->contains($centre)) {
            $this->centres[] = $centre;
            $centre->setCommune($this);
        }

        return $this;
    }

    public function removeCentre(Centre $centre): self
    {
        if ($this->centres->contains($centre)) {
            $this->centres->removeElement($centre);
            // set the owning side to null (unless already changed)
            if ($centre->getCommune() === $this) {
                $centre->setCommune(null);
            }
        }

        return $this;
    }
    /**
     * generates a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nomCommune;
    }
}
