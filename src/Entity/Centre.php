<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CentreRepository")
 */
class Centre
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
    private $nomCE;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresseCE;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $chefCE;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $numeroChefCE;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreationCE;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commune", inversedBy="centres")
     */
    private $commune;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeCE", inversedBy="zoneImpCE")
     */
    private $typeCE;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ZoneImpCE", inversedBy="centres")
     */
    private $zoneImCE;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FaitEC", mappedBy="centre")
     */
    private $faitECs;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    public function __construct()
    {
        $this->faitECs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCE(): ?string
    {
        return $this->nomCE;
    }

    public function setNomCE(?string $nomCE): self
    {
        $this->nomCE = $nomCE;

        return $this;
    }

    public function getAdresseCE(): ?string
    {
        return $this->adresseCE;
    }

    public function setAdresseCE(?string $adresseCE): self
    {
        $this->adresseCE = $adresseCE;

        return $this;
    }

    public function getChefCE(): ?string
    {
        return $this->chefCE;
    }

    public function setChefCE(?string $chefCE): self
    {
        $this->chefCE = $chefCE;

        return $this;
    }

    public function getNumeroChefCE(): ?string
    {
        return $this->numeroChefCE;
    }

    public function setNumeroChefCE(?string $numeroChefCE): self
    {
        $this->numeroChefCE = $numeroChefCE;

        return $this;
    }

    public function getDateCreationCE(): ?\DateTimeInterface
    {
        return $this->dateCreationCE;
    }

    public function setDateCreationCE(?\DateTimeInterface $dateCreationCE): self
    {
        $this->dateCreationCE = $dateCreationCE;

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

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getCommune(): ?Commune
    {
        return $this->commune;
    }

    public function setCommune(?Commune $commune): self
    {
        $this->commune = $commune;

        return $this;
    }

    public function getTypeCE(): ?TypeCE
    {
        return $this->typeCE;
    }

    public function setTypeCE(?TypeCE $typeCE): self
    {
        $this->typeCE = $typeCE;

        return $this;
    }

    public function getZoneImCE(): ?ZoneImpCE
    {
        return $this->zoneImCE;
    }

    public function setZoneImCE(?ZoneImpCE $zoneImCE): self
    {
        $this->zoneImCE = $zoneImCE;

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
            $faitEC->setCentre($this);
        }

        return $this;
    }

    public function removeFaitEC(FaitEC $faitEC): self
    {
        if ($this->faitECs->contains($faitEC)) {
            $this->faitECs->removeElement($faitEC);
            // set the owning side to null (unless already changed)
            if ($faitEC->getCentre() === $this) {
                $faitEC->setCentre(null);
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
}
