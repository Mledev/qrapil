<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebController extends Controller
{
    /**
     * @Route("/", name="location_select", methods="GET")
     */
    public function select(EventRepository $eventRepository): Response
    {
        return $this->render('web/select.html.twig', [
            'locations' => $eventRepository->findAll()
        ]);
    }

    /**
     * @Route("/getQRCode", name="location_qrcode", methods="POST")
     */
    public function getQRCode(Request $request, LocationRepository $locationRepository): Response
    {
		$id = (int)$request->request->get('location');
		$location = $locationRepository->find($id);

		if(!isset($location) || !is_int($id)) {
			return new Response('Invalid request.', 404, ['HTTP/1.1 404 Not Found']);
		}

		return $this->render('web/qrcode.html.twig', [
			'id' => $id,
			'location' => $location
		]);
    }

    /**
     * @Route("/refreshQR", name="location_refresh", methods="GET|POST")
     */
	public function refreshQR(Request $request, LocationRepository $locationRepository): Response
	{
		$id = (int)$request->request->get('location');
		$location = $locationRepository->find($id);

		if(!is_int($id) || !isset($location)) {
			return new Response('Invalid request.', 404, ['HTTP/1.1 404 Not Found']);
		}

		$entityManager = $this->getDoctrine()->getManager();
		$newQR = uniqid();
        $location->setQrcode($newQR);
        $entityManager->flush();

		return new Response($newQR, 200);
	}
}
