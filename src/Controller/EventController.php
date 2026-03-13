<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EventController extends AbstractController
{
    /**
     * CATALOGUE PUBLIC AVEC FILTRE (Scope Optionnel)
     */
    #[Route('/events', name: 'app_events_catalog', methods: ['GET'])]
    public function catalog(Request $request, EventRepository $eventRepository, CategoryRepository $categoryRepository): Response
    {
        $categoryId = $request->query->get('category');
        $categories = $categoryRepository->findAll();

        if ($categoryId) {
            $events = $eventRepository->findBy(['category' => $categoryId, 'isPublished' => true]);
        } else {
            $events = $eventRepository->findPublishedFuture();
        }

        return $this->render('event/catalog.html.twig', [
            'events' => $events,
            'categories' => $categories,
            'currentCategory' => $categoryId 
        ]);
    }

    #[Route('/event/{id}', name: 'app_event_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Event $event, ReservationRepository $reservationRepository): Response
    {
        $dejaInscrit = false;
        /** @var User $user */
        $user = $this->getUser();

        if ($user) {
            $dejaInscrit = $reservationRepository->userHasReservation($user, $event->getId());
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
            'dejaInscrit' => $dejaInscrit,
        ]);
    }

    #[Route('/event/{id}/inscrire', name: 'app_event_inscrire', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function inscrire(
        Event $event,
        ReservationService $reservationService,
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager,
    ): Response {
        $token = new CsrfToken('inscrire' . $event->getId(), $request->request->get('_token'));
        
        if (!$csrfTokenManager->isTokenValid($token)) {
            $this->addFlash('error', 'Token de sécurité invalide.');
            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($reservationService->inscrire($user, $event)) {
            $this->addFlash('success', 'Inscription enregistrée.');
        } else {
            $this->addFlash('error', 'Impossible de vous inscrire (places complètes ou déjà inscrit).');
        }

        return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
    }

    /* --- SECTION ADMINISTRATION --- */

    #[Route('/admin/events', name: 'app_admin_events', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function adminIndex(EventRepository $repo): Response
    {
        return $this->render('event/admin_index.html.twig', [
            'events' => $repo->findAll(),
        ]);
    }

    #[Route('/admin/events/new', name: 'app_admin_event_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($event);
            $em->flush();

            $this->addFlash('success', 'L\'événement a été créé.');
            return $this->redirectToRoute('app_admin_events');
        }

        return $this->render('event/new.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    #[Route('/admin/events/{id}/edit', name: 'app_admin_event_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Event $event, Request $request, EntityManagerInterface $em): Response
    {
        $ancienneCapacite = $event->getCapaciteMax();
    
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nouvelleCapacite = $event->getCapaciteMax();
            $reservations = $event->getReservations();

            // LOGIQUE D'EXCLUSION (Scope Optionnel)
            if ($nouvelleCapacite < $ancienneCapacite && count($reservations) > $nouvelleCapacite) {
                $nbAExclure = count($reservations) - $nouvelleCapacite;
            
                for ($i = 0; $i < $nbAExclure; $i++) {
                    $derniereResa = $reservations->last(); 
                
                    if ($derniereResa) {
                        $emailUser = $derniereResa->getUser()->getEmail();
                        $em->remove($derniereResa);
                        $this->addFlash('warning', "Réservation de $emailUser supprimée (réduction de capacité).");
                    }
                }
            }

            $em->flush();
            $this->addFlash('success', 'L\'événement a été mis à jour.');
            return $this->redirectToRoute('app_admin_events');
        }

        return $this->render('event/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    #[Route('/admin/events/{id}/delete', name: 'app_admin_event_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Event $event, Request $request, EntityManagerInterface $em): Response
    {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $event->getId(), $submittedToken)) {
            $em->remove($event);
            $em->flush();
            $this->addFlash('success', 'L\'événement a été supprimé.');
        } else {
            $this->addFlash('error', 'Échec de la suppression : token invalide.');
        }

        return $this->redirectToRoute('app_admin_events');
    }
}
