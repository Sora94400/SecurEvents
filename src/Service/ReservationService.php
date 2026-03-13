<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ReservationRepository $reservationRepository,
    ) {
    }

    public function inscrire(User $user, Event $event): bool
    {
        if ($this->reservationRepository->userHasReservation($user, (int) $event->getId())) {
            return false;
        }

        if (!$event->hasPlacesDisponibles()) {
            return false;
        }

        if (!$event->isInFuture() || !$event->isPublished()) {
            return false;
        }

        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setEvent($event);

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return true;
    }
}
