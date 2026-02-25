<?php

namespace App\Entity;

use App\Repository\WishlistItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishlistItemRepository::class)]
#[ORM\Table(name: 'wishlist_items')]
class WishlistItem
{
    public const PRIORITY_HIGH   = 'high';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_LOW    = 'low';

    public const PRIORITIES = [
        'priority.high'   => self::PRIORITY_HIGH,
        'priority.medium' => self::PRIORITY_MEDIUM,
        'priority.low'    => self::PRIORITY_LOW,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private Person $person;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name = '';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: 'string', length: 512, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: 'string', length: 512, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(type: 'string', length: 10)]
    private string $currency = 'USD';

    #[ORM\Column(type: 'string', length: 20)]
    private string $priority = self::PRIORITY_MEDIUM;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $addedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $lastModifiedAt;

    #[ORM\Column(type: 'boolean')]
    private bool $isFulfilled = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $deletedAt = null;

    public function __construct(Person $person)
    {
        $this->person = $person;
        $this->addedAt = new \DateTimeImmutable();
        $this->lastModifiedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerson(): Person
    {
        return $this->person;
    }

    public function setPerson(Person $person): self
    {
        $this->person = $person;
        return $this;
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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = ($price !== '' && $price !== null) ? $price : null;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function getAddedAt(): \DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function getLastModifiedAt(): \DateTimeImmutable
    {
        return $this->lastModifiedAt;
    }

    public function isFulfilled(): bool
    {
        return $this->isFulfilled;
    }

    public function setIsFulfilled(bool $isFulfilled): self
    {
        $this->isFulfilled = $isFulfilled;
        return $this;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function touch(): self
    {
        $this->lastModifiedAt = new \DateTimeImmutable();
        $this->person->touch();
        return $this;
    }

    public function softDelete(): self
    {
        $this->deletedAt = new \DateTime();
        return $this;
    }

    public function restore(): self
    {
        $this->deletedAt = null;
        return $this;
    }
}
