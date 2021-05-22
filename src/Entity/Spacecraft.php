<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SpacecraftRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SpacecraftRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *              fields={"name"},
 *              message="Ce vaisseau existe déja dans la base."
 *)
 */
class Spacecraft
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
     * @ORM\Column(type="string", length=255)
     */
    private $brand;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfSeat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nationality;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $speed;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rating;

    /**
     * @ORM\OneToMany(targetEntity=Trip::class, mappedBy="spacecraft")
     */
    private $trip;

    /**
     * @ORM\OneToMany(targetEntity=Feedback::class, mappedBy="spacecraft", orphanRemoval=true)
     */
    private $feedback;

    /**
     * @ORM\ManyToMany(targetEntity=Destination::class, inversedBy="spacecrafts")
     */
    private $possibleDestination;

    /**
     * @ORM\Column(type="float")
     */
    private $reservationPrice;

    /**
     * @ORM\Column(type="float")
     */
    private $pricePerDistance;

    public function __construct()
    {
        $this->trip = new ArrayCollection();
        $this->feedback = new ArrayCollection();
        $this->possibleDestination = new ArrayCollection();
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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getNumberOfSeat(): ?int
    {
        return $this->numberOfSeat;
    }

    public function setNumberOfSeat(int $numberOfSeat): self
    {
        $this->numberOfSeat = $numberOfSeat;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

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

    public function getSpeed(): ?float
    {
        return $this->speed;
    }

    public function setSpeed(float $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set automatically the date of creation
     * @ORM\PrePersist
     * @return self
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTimeImmutable();
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set automatically the date of update
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return self
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    /**
     * Automatically set or updates the rating of the spacecraft based on its feedbacks ratings
     */
    public function setRating(): self
    {
        if (count($this->feedback) > 0) {
            $ratings = [];
            foreach ($this->feedback as $feedback) {
                array_push($ratings, $feedback->getRating());
            }
            $rating = round(array_sum($ratings) / (count($ratings)));
        } else {
            $rating = null;
        }
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection|Trip[]
     */
    public function getTrip(): Collection
    {
        return $this->trip;
    }

    public function addTrip(Trip $trip): self
    {
        if (!$this->trip->contains($trip)) {
            $this->trip[] = $trip;
            $trip->setSpacecraft($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trip->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getSpacecraft() === $this) {
                $trip->setSpacecraft(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Feedback[]
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback[] = $feedback;
            $feedback->setSpacecraft($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getSpacecraft() === $this) {
                $feedback->setSpacecraft(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Destination[]
     */
    public function getPossibleDestination(): Collection
    {
        return $this->possibleDestination;
    }

    public function addPossibleDestination(Destination $possibleDestination): self
    {
        if (!$this->possibleDestination->contains($possibleDestination)) {
            $this->possibleDestination[] = $possibleDestination;
        }

        return $this;
    }

    public function removePossibleDestination(Destination $possibleDestination): self
    {
        $this->possibleDestination->removeElement($possibleDestination);

        return $this;
    }

    public function getReservationPrice(): ?float
    {
        return $this->reservationPrice;
    }

    public function setReservationPrice(float $reservationPrice): self
    {
        $this->reservationPrice = $reservationPrice;

        return $this;
    }

    public function getPricePerDistance(): ?float
    {
        return $this->pricePerDistance;
    }

    public function setPricePerDistance(float $pricePerDistance): self
    {
        $this->pricePerDistance = $pricePerDistance;

        return $this;
    }
}
