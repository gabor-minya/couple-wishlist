<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\Table(name: 'persons')]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name = '';

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $linkedUser = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isOnHold = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $lastModifiedAt = null;

    #[ORM\OneToMany(
        mappedBy: 'person',
        targetEntity: WishlistItem::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true,
        fetch: 'EXTRA_LAZY'
    )]
    private Collection $items;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getLinkedUser(): ?User
    {
        return $this->linkedUser;
    }

    public function setLinkedUser(?User $user): self
    {
        $this->linkedUser = $user;
        return $this;
    }

    public function isOnHold(): bool
    {
        return $this->isOnHold;
    }

    public function setIsOnHold(bool $isOnHold): self
    {
        $this->isOnHold = $isOnHold;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getLastModifiedAt(): ?\DateTimeImmutable
    {
        return $this->lastModifiedAt;
    }

    public function setLastModifiedAt(?\DateTimeImmutable $lastModifiedAt): self
    {
        $this->lastModifiedAt = $lastModifiedAt;
        return $this;
    }

    public function touch(): self
    {
        $this->lastModifiedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getActiveItems(): Collection
    {
        return $this->items->filter(
            fn(WishlistItem $item) => $item->getDeletedAt() === null
        );
    }

    public function getActiveItemCount(): int
    {
        return $this->getActiveItems()->count();
    }
}
