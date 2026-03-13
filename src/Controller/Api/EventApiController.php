<?php

namespace App\Controller\Api;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class EventApiController extends AbstractController
{
    #[Route('/events', name: 'app_api_events', methods: ['GET'])]
    public function list(EventRepository $eventRepository, SerializerInterface $serializer): JsonResponse
    {
        $events = $eventRepository->findPublishedFuture();

        $json = $serializer->serialize($events, 'json', [
            'groups' => ['event:public'],
        ]);

        return new JsonResponse($json, 200, [
            'Content-Type' => 'application/json',
        ], true);
    }
}
