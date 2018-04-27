<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QRController extends Controller
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

	if(is_null($location) || !is_int($id)) {
		return new Response('Invalid request.', 404, ['HTTP/1.1 404 Not Found']);
	}

        return $this->render('web/qrcode.html.twig', [
		'id' => $id,
		'message' => $location->getQrcode(),
		'description' => $location->getDescription()
    	]);
    }

    /**
     * @Route("/refreshQR", name="location_refresh", methods="POST")
     */
	public function refreshQR(Request $request, LocationRepository $locationRepository): Response
	{
		$id = (int)$request->request->get('location');
		$location = $locationRepository->find($id);
		
		if(is_null($location) || !is_int($id)) {
			return new Response('Invalid request.', 404, ['HTTP/1.1 404 Not Found']);
		}

		return new Response(uniqid($location->getQrcode()), 200);
	}
}
