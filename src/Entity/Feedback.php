<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FeedbackRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Feedback
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      max = 510,
     *      maxMessage = "Le commentaire est trop long ({{ limit }} caractères max.)"
     * )
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="feedback")
     * 
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Spacecraft::class, inversedBy="feedback")
     */
    private $spacecraft;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *  min = 0,
     *  max = 5,
     *  notInRangeMessage = "La note doit etre un chiffre entre {{ min }} et {{ max }}."
     * )
     * 
     */
    private $rating;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    /**
     * Set automatically the creation date
     * @ORM\PrePersist
     * @param \DateTimeInterface $createdAt
     * @return self
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
