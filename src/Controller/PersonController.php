<?php
namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/person/new', name:'person_new', methods:['GET','POST'])]
    public function new(Request $req, EntityManagerInterface $em) {
        $person = new Person('');
        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('person/new.html.twig', [
            'form' => $form->createView(),
            'person' => $person,
        ]);
    }

    #[Route('/person/{id}/edit', name: 'person_edit', methods: ['GET','POST'])]
    public function edit(Person $person, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person->setLastModifiedAt(new \DateTimeImmutable());
            $em->flush();

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return $this->render('person/edit.html.twig', [
            'form' => $form->createView(),
            'person' => $person,
        ]);
    }

    #[Route('/person/{id}/item/new', name: 'person_add_item', methods: ['GET','POST'])]
    public function addItem(Person $person, Request $request, EntityManagerInterface $em): Response
    {
        $item = new Item();
        $item->setPerson($person);
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return $this->render('item/new.html.twig', [
            'form' => $form->createView(),
            'person' => $person,
        ]);
    }

    #[Route('/person/{id}/trash', name: 'person_trash', methods: ['POST'])]
    public function trash(Person $person, EntityManagerInterface $em): Response
    {
        // Ha soft delete-et használsz
        $person->setDeletedAt(new \DateTimeImmutable());

        $em->flush();

        return $this->redirectToRoute('dashboard'); // vagy a megfelelő lista oldal
    }

    #[Route('/person/{id}', name:'person_show')]
    public function show(Person $person) {
        return $this->render('person/show.html.twig', ['person' => $person]);
    }

    #[Route('/person/{id}/toggle-hold', name:'person_toggle_hold', methods:['POST'])]
    public function toggleHold(Person $person, EntityManagerInterface $em) {
        $person->setIsOnHold(!$person->isOnHold());
        $person->setLastModifiedAt(new \DateTimeImmutable());
        $em->flush();
        return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
    }
}
