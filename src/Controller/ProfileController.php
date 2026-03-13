<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/mes-evenements', name: 'app_profile_events', methods: ['GET'])]
    public function mesEvenements(): Response
    {
        $user = $this->getUser();
        $reservations = $user->getReservations();

        $events = [];
        foreach ($reservations as $r) {
            $e = $r->getEvent();
            if ($e && $e->isInFuture()) {
                $events[] = $e;
            }
        }

        usort($events, fn ($a, $b) => $a->getDateDebut() <=> $b->getDateDebut());

        return $this->render('profile/mes_evenements.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('', name: 'app_profile', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
