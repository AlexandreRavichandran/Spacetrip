<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TripRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * 
 * @ORM\Entity(repositoryClass=TripRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *              fields={"name"},
 *              message="Un voyage de ce nom existe déja. Veuillez choisir un nom different."
 *)
 */
class Trip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $departureAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $arrivalAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * 
     */
    private $availableSeatNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reserved;

    /**
     * @ORM\ManyToOne(targetEntity=Spacecraft::class, inversedBy="trip")
     * @ORM\JoinColumn(nullable=false)
     */
    private $spacecraft;

    /**
     * @ORM\ManyToOne(targetEntity=Destination::class, inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $destination;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *          min = 1,
     *          max = 4
     * )
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="trip")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Automatically set the creation date
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
     * Automatically set the updated date
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return self
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getDepartureAt(): ?\DateTimeInterface
    {
        return $this->departureAt;
    }

    public function setDepartureAt(\DateTimeInterface $departureAt): self
    {
        $this->departureAt = $departureAt;

        return $this;
    }

    public function getArrivalAt(): ?\DateTimeInterface
    {
        return $this->arrivalAt;
    }

    public function setArrivalAt(\DateTimeInterface $arrivalAt): self
    {
        $this->arrivalAt = $arrivalAt;

        return $this;
    }

    public function getAvailableSeatNumber(): ?int
    {
        return $this->availableSeatNumber;
    }

    public function setAvailableSeatNumber(?int $availableSeatNumber): self
    {
        $this->availableSeatNumber = $availableSeatNumber;

        return $this;
    }

    public function getReserved(): ?bool
    {
        return $this->reserved;
    }

    public function setReserved(bool $reserved): self
    {
        $this->reserved = $reserved;

        return $this;
    }

    public function getSpacecraft(): ?Spacecraft
    {
        return $this->spacecraft;
    }

    public function setSpacecraft(?Spacecraft $spacecraft): self
    {
        $this->spacecraft = $spacecraft;

        return $this;
    }

    public function getDestination(): ?Destination
    {
        return $this->destination;
    }

    public function setDestination(?Destination $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Set automatically the price of a Trip based on the destination distance and spacecraft prices
     * (formula = destination_distance * price_per_distance + reservation_price)

     * @return self
     */
    public function setPrice(bool $reserved = false): self
    {
        $destination = $this->getDestination();
        $spacecraft = $this->getSpacecraft();
        if ($reserved) {
            $price = ($destination->getDistance() * $spacecraft->getPricePerDistance() + $spacecraft->getReservationPrice());
        } else {
            $price = ($destination->getDistance() * $spacecraft->getPricePerDistance() + $spacecraft->getReservationPrice() + 2500);
        }
        $this->price = $price;
        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Status 1 : Unpayed,
     * Status 2 : Created/available,
     * Status 3 : Sold out,
     * Status 4 : Ended
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addTrip($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeTrip($this);
        }

        return $this;
    }
}
