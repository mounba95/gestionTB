<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeCERepository")
 */
class TypeCE
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
    private $nomTypeCE;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptionTypeCE;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Centre", mappedBy="typeCE")
     */
    private $zoneImpCE;

    public function __construct()
    {
        $this->zoneImpCE = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTypeCE(): ?string
    {
        return $this->nomTypeCE;
    }

    public function setNomTypeCE(?string $nomTypeCE): self
    {
        $this->nomTypeCE = $nomTypeCE;

        return $this;
    }

    public function getDescriptionTypeCE(): ?string
    {
        return $this->descriptionTypeCE;
    }

    public function setDescriptionTypeCE(?string $descriptionTypeCE): self
    {
        $this->descriptionTypeCE = $descriptionTypeCE;

        return $this;
    }

    /**
     * @return Collection|Centre[]
     */
    public function getZoneImpCE(): Collection
    {
        return $this->zoneImpCE;
    }

    public function addZoneImpCE(Centre $zoneImpCE): self
    {
        if (!$this->zoneImpCE->contains($zoneImpCE)) {
            $this->zoneImpCE[] = $zoneImpCE;
            $zoneImpCE->setTypeCE($this);
        }

        return $this;
    }

    public function removeZoneImpCE(Centre $zoneImpCE): self
    {
        if ($this->zoneImpCE->contains($zoneImpCE)) {
            $this->zoneImpCE->removeElement($zoneImpCE);
            // set the owning side to null (unless already changed)
            if ($zoneImpCE->getTypeCE() === $this) {
                $zoneImpCE->setTypeCE(null);
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
        return $this->nomTypeCE;
    }
}
