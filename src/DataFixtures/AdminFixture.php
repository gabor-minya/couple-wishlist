<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $adminPassword = getenv('ADMIN_PASSWORD') ?: 'ChangeMe123!';
        $user = new User('admin', $this->hasher->hashPassword(new User('admin',''), $adminPassword), ['ROLE_ADMIN','ROLE_USER']);
        $manager->persist($user);
        $manager->flush();
    }
}
