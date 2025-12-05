<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'wishlist_items')]
class WishlistItem
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private Person $person;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $priority = 'unknown';

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $addedAt;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isFulfilled = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $deletedAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $lastModifiedAt;

    public function __construct(Person $person, string $name)
    {
        $this->person = $person;
        $this->name = $name;
        $this->addedAt = new \DateTimeImmutable();
        $this->lastModifiedAt = new \DateTimeImmutable();
    }

    // getters / setters...
    public function getId(): ?string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $n): self { $this->name = $n; $this->touch(); return $this; }
    public function touch(): void { $this->lastModifiedAt = new \DateTimeImmutable(); if ($this->person) $this->person->setLastModifiedAt($this->lastModifiedAt); }
    public function softDelete(): void { $this->deletedAt = new \DateTime(); }
    public function restore(): void { $this->deletedAt = null; }
}
