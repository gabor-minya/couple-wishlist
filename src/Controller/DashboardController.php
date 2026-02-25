<?php

namespace App\Controller;

use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(PersonRepository $personRepository): Response
    {
        $people = $personRepository->findAllOrderedByActivity();

        return $this->render('dashboard/index.html.twig', [
            'people' => $people,
        ]);
    }
}
