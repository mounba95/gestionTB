<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndicateurBaseRepository")
 */
class IndicateurBase
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
    private $nomIndicateurBase;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptionIndicateurBase;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Indicateur", mappedBy="indicateurBase")
     */
    private $indicateurs;

    public function __construct()
    {
        $this->indicateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomIndicateurBase(): ?string
    {
        return $this->nomIndicateurBase;
    }

    public function setNomIndicateurBase(?string $nomIndicateurBase): self
    {
        $this->nomIndicateurBase = $nomIndicateurBase;

        return $this;
    }

    public function getDescriptionIndicateurBase(): ?string
    {
        return $this->descriptionIndicateurBase;
    }

    public function setDescriptionIndicateurBase(?string $descriptionIndicateurBase): self
    {
        $this->descriptionIndicateurBase = $descriptionIndicateurBase;

        return $this;
    }

    /**
     * @return Collection|Indicateur[]
     */
    public function getIndicateurs(): Collection
    {
        return $this->indicateurs;
    }

    public function addIndicateur(Indicateur $indicateur): self
    {
        if (!$this->indicateurs->contains($indicateur)) {
            $this->indicateurs[] = $indicateur;
            $indicateur->setIndicateurBase($this);
        }

        return $this;
    }

    public function removeIndicateur(Indicateur $indicateur): self
    {
        if ($this->indicateurs->contains($indicateur)) {
            $this->indicateurs->removeElement($indicateur);
            // set the owning side to null (unless already changed)
            if ($indicateur->getIndicateurBase() === $this) {
                $indicateur->setIndicateurBase(null);
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
        return $this->nomIndicateurBase;
    }
}
