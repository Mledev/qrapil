<?php

namespace App\Controller\API;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/getLocation")
 */
class LocationController extends Controller
{
    /**
     * @Route("/", name="location_api", methods="GET")
     */
    public function api(): Response
    {
        return $this->json(['user'=>'Hello world']);
    }
}
