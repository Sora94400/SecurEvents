<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/events')]
#[IsGranted('ROLE_ADMIN')]
class EventAdminController extends AbstractController
{
    #[Route('', name: 'app_admin_events', methods: ['GET'])]
    public function list(EventRepository $eventRepository): Response
    {
        return $this->render('admin/event/list.html.twig', [
            'events' => $eventRepository->findAllOrderedByDate(),
        ]);
    }

    #[Route('/new', name: 'app_admin_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Événement créé.');

            return $this->redirectToRoute('app_admin_events');
        }

        return $this->render('admin/event/form.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_event_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Event $event, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Événement modifié.');

            return $this->redirectToRoute('app_admin_events');
        }

        return $this->render('admin/event/form.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_event_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(
        Event $event,
        Request $request,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
    ): Response {
        $token = new CsrfToken('delete' . $event->getId(), $request->request->get('_token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $entityManager->remove($event);
        $entityManager->flush();

        $this->addFlash('success', 'Événement supprimé.');

        return $this->redirectToRoute('app_admin_events');
    }

    #[Route('/{id}/participants', name: 'app_admin_event_participants', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function participants(Event $event): Response
    {
        $participants = [];
        foreach ($event->getReservations() as $r) {
            $u = $r->getUser();
            if ($u) {
                $participants[] = ['prenom' => $u->getPrenom(), 'nom' => $u->getNom(), 'email' => $u->getEmail()];
            }
        }

        return $this->render('admin/event/participants.html.twig', [
            'event' => $event,
            'participants' => $participants,
        ]);
    }
}
