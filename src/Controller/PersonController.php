<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\WishlistItem;
use App\Form\PersonType;
use App\Form\WishlistItemType;
use App\Repository\WishlistItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PersonController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator) {}

    #[Route('/person/new', name: 'person_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $person = new Person();
        $form   = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($person);
            $em->flush();
            $this->addFlash('success', $this->translator->trans('flash.person_added', [
                '%name%' => $person->getName(),
            ]));

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('person/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/person/{id}/edit', name: 'person_edit', methods: ['GET', 'POST'])]
    public function edit(Person $person, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person->touch();
            $em->flush();
            $this->addFlash('success', $this->translator->trans('flash.person_updated'));

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return $this->render('person/edit.html.twig', [
            'form'   => $form,
            'person' => $person,
        ]);
    }

    #[Route('/person/{id}', name: 'person_show', methods: ['GET'])]
    public function show(Person $person, WishlistItemRepository $itemRepo): Response
    {
        return $this->render('person/show.html.twig', [
            'person' => $person,
            'items'  => $itemRepo->findActiveByPerson($person),
        ]);
    }

    #[Route('/person/{id}/item/new', name: 'person_item_new', methods: ['GET', 'POST'])]
    public function addItem(Person $person, Request $request, EntityManagerInterface $em): Response
    {
        $item = new WishlistItem($person);
        $form = $this->createForm(WishlistItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $item->touch();
            $em->flush();
            $this->addFlash('success', $this->translator->trans('flash.item_added', [
                '%name%' => $item->getName(),
            ]));

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return $this->render('item/new.html.twig', [
            'form'   => $form,
            'person' => $person,
        ]);
    }

    #[Route('/person/{id}/trash', name: 'person_trash', methods: ['GET'])]
    public function trash(Person $person, WishlistItemRepository $itemRepo): Response
    {
        return $this->render('person/trash.html.twig', [
            'person' => $person,
            'items'  => $itemRepo->findDeletedByPerson($person),
        ]);
    }

    #[Route('/person/{id}/toggle-hold', name: 'person_toggle_hold', methods: ['POST'])]
    public function toggleHold(Person $person, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('toggle-hold' . $person->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', $this->translator->trans('flash.csrf_error'));

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        $person->setIsOnHold(!$person->isOnHold());
        $person->touch();
        $em->flush();

        $flashKey = $person->isOnHold() ? 'flash.hold_on' : 'flash.hold_off';
        $this->addFlash('success', $this->translator->trans($flashKey, [
            '%name%' => $person->getName(),
        ]));

        return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
    }
}
