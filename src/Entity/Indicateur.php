<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndicateurRepository")
 */
class Indicateur
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
    private $nomIndicateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptionIndicateur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FaitEC", mappedBy="indicateur")
     */
    private $faitECs;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IndicateurBase", inversedBy="indicateurs")
     */
    private $indicateurBase;

    public function __construct()
    {
        $this->faitECs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomIndicateur(): ?string
    {
        return $this->nomIndicateur;
    }

    public function setNomIndicateur(?string $nomIndicateur): self
    {
        $this->nomIndicateur = $nomIndicateur;

        return $this;
    }

    public function getDescriptionIndicateur(): ?string
    {
        return $this->descriptionIndicateur;
    }

    public function setDescriptionIndicateur(?string $descriptionIndicateur): self
    {
        $this->descriptionIndicateur = $descriptionIndicateur;

        return $this;
    }

    /**
     * @return Collection|FaitEC[]
     */
    public function getFaitECs(): Collection
    {
        return $this->faitECs;
    }

    public function addFaitEC(FaitEC $faitEC): self
    {
        if (!$this->faitECs->contains($faitEC)) {
            $this->faitECs[] = $faitEC;
            $faitEC->setIndicateur($this);
        }

        return $this;
    }

    public function removeFaitEC(FaitEC $faitEC): self
    {
        if ($this->faitECs->contains($faitEC)) {
            $this->faitECs->removeElement($faitEC);
            // set the owning side to null (unless already changed)
            if ($faitEC->getIndicateur() === $this) {
                $faitEC->setIndicateur(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIndicateurBase(): ?IndicateurBase
    {
        return $this->indicateurBase;
    }

    public function setIndicateurBase(?IndicateurBase $indicateurBase): self
    {
        $this->indicateurBase = $indicateurBase;

        return $this;
    }
    /**
     * generates a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nomIndicateur;
    }
}
