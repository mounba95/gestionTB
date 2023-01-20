<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ZoneImpCERepository")
 */
class ZoneImpCE
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomZoneImpCE;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptionZoneImpCE;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Centre", mappedBy="zoneImCE")
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

    public function getNomZoneImpCE(): ?string
    {
        return $this->nomZoneImpCE;
    }

    public function setNomZoneImpCE(?string $nomZoneImpCE): self
    {
        $this->nomZoneImpCE = $nomZoneImpCE;

        return $this;
    }

    public function getDescriptionZoneImpCE(): ?string
    {
        return $this->descriptionZoneImpCE;
    }

    public function setDescriptionZoneImpCE(?string $descriptionZoneImpCE): self
    {
        $this->descriptionZoneImpCE = $descriptionZoneImpCE;

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
            $centre->setZoneImCE($this);
        }

        return $this;
    }

    public function removeCentre(Centre $centre): self
    {
        if ($this->centres->contains($centre)) {
            $this->centres->removeElement($centre);
            // set the owning side to null (unless already changed)
            if ($centre->getZoneImCE() === $this) {
                $centre->setZoneImCE(null);
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
        return $this->nomZoneImpCE;
    }
}
