<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'persons')]
class Person
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $linkedUser = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isOnHold = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $lastModifiedAt = null;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: WishlistItem::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->createdAt = new \DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    // getters/setters...
    public function getId(): ?string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $n): self { $this->name = $n; return $this; }
    public function isOnHold(): bool { return $this->isOnHold; }
    public function setIsOnHold(bool $v): self { $this->isOnHold = $v; return $this; }
    public function getItems(): Collection { return $this->items; }
    public function getLastModifiedAt(): ?\DateTimeImmutable { return $this->lastModifiedAt; }
    public function setLastModifiedAt(?\DateTimeImmutable $d): self { $this->lastModifiedAt = $d; return $this; }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }


}
