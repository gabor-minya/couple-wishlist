<?php
namespace App\Controller;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(EntityManagerInterface $em)
    {
        $people = $em->getRepository(Person::class)->findBy([], ['lastModifiedAt' => 'DESC']);
        return $this->render('dashboard/index.html.twig', ['people' => $people]);
    }
}
