<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DestinationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=DestinationRepository::class)
 */
class Destination
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $distance;

    /**
     * @ORM\OneToMany(targetEntity=Trip::class, mappedBy="destination")
     */
    private $trips;

    /**
     * @ORM\ManyToMany(targetEntity=Spacecraft::class, mappedBy="possibleDestination")
     */
    private $spacecrafts;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
        $this->spacecrafts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * @return Collection|Trip[]
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): self
    {
        if (!$this->trips->contains($trip)) {
            $this->trips[] = $trip;
            $trip->setDestination($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getDestination() === $this) {
                $trip->setDestination(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Spacecraft[]
     */
    public function getSpacecrafts(): Collection
    {
        return $this->spacecrafts;
    }

    public function addSpacecraft(Spacecraft $spacecraft): self
    {
        if (!$this->spacecrafts->contains($spacecraft)) {
            $this->spacecrafts[] = $spacecraft;
            $spacecraft->addPossibleDestination($this);
        }

        return $this;
    }

    public function removeSpacecraft(Spacecraft $spacecraft): self
    {
        if ($this->spacecrafts->removeElement($spacecraft)) {
            $spacecraft->removePossibleDestination($this);
        }

        return $this;
    }
}
