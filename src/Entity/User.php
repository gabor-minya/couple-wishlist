<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $username;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $username, string $hashedPassword, array $roles = ['ROLE_USER'])
    {
        $this->username = $username;
        $this->password = $hashedPassword;
        $this->roles = $roles;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?string { return $this->id; }
    public function getUserIdentifier(): string { return $this->username; }
    public function getUsername(): string { return $this->username; }
    public function setUsername(string $u): self { $this->username = $u; return $this; }

    public function getRoles(): array { return array_unique($this->roles); }
    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $hashed): self { $this->password = $hashed; return $this; }

    public function eraseCredentials(): void {}
}
