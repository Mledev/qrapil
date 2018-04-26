<?php

namespace App\Controller\API;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/event")
 */
class EventController extends Controller
{
    /**
     * @Route("/", name="event_api", methods="GET")
     */
    public function api(): Response
    {
        return $this->json(['user'=>'Hello world']);
    }
}
