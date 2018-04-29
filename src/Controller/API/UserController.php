<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class UserController extends Controller
{
    /**
     * @Route("/login", name="user_login", methods="POST")
     */
    public function login(Request $request, UserRepository $userRepository): Response
    {
        $connexion = $request->getContent();
        $connexion = json_decode($connexion, false , 2);
        $user = $userRepository->findOneBy(array('email' => $connexion->{'email'}));
        $password = hash('sha512', $connexion->{'password'});

        if (!isset($user)){

            return new JsonResponse(array('error' => 'Email invalid'), 404);
        }else{
            if($password === $user->getPassword()){
                $token = $user->getToken();
            }else{
                return new JsonResponse(array('error' => 'Password invalid'.$password.' and not '.$user->getPassword()), 404);
            }

            return new JsonResponse(array('token' => $token));
        }


    }

    /**
     * @Route("/refreshtoken", name="refresh_token", methods="POST")
     */
    public function refreshToken(Request $request, UserRepository $userRepository): Response
    {
        $connexion = $request->getContent();
        $token = json_decode($connexion, false , 2);
        $user = $userRepository->findOneBy(array('token' => $token->{'token'}));
        if (!isset($user)) {

            return new JsonResponse(array('error' => 'Token invalid'), 404);
        } else {
            $entityManager = $this->getDoctrine()->getManager();
            $mail = $user->getEmail();
            $newToken =  md5($mail.random_bytes(20));
            $user->setToken($newToken);
            $entityManager->flush();

            return new JsonResponse(array('token' => $newToken));
        }
    }

    /**
     * @Route("/getlocation", name="getlocation", methods="GET")
     */
    public function getlocation(Request $request, UserRepository $userRepository, EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        $connexion = $request->query->get('json');
        $token = json_decode($connexion, false , 2);
        $currentdate = date('c');
        $user = $userRepository->findOneBy(array('token' => $token->{'token'}));

        if (!isset($user))
        {

            return new JsonResponse(array('error' => 'Token invalid'), 404);
        }else{
            foreach ($events as $event)
            {
                $dateevent = $event->getDate()->format('Y-m-d H:i:s');
                $endevent = strtotime("+1 day", strtotime($dateevent));
                if ($currentdate >= $dateevent && $currentdate <= date("Y-m-d", $endevent)) {

                    return new JsonResponse(array('date' => $event->getDate()->format('Y-m-d H:i:s'), 'location' => $event->getLocation()->getDescription()));
                }
            }
        }
    }

    /**
     * @Route("/checkin", name="checkin", methods="POST")
     */
    public function checkin(Request $request, UserRepository $userRepository, EventRepository $eventRepository, LocationRepository $locationRepository): Response
    {
        $json = $request->getContent();
        $data = json_decode($json);

        $beacon = $data->{'beaconCollection'};
        $qrCodeStatus = $data->{'QRCodeData'};
        $location = $locationRepository->findOneBy(array('beacon' => $beacon));
        $event = $eventRepository->findOneBy(array('location' => $location));
        $currentdate = $data->{'date'};
        $dateevent = $event->getDate()->format('Y-m-d H:i:s');
        $user = $userRepository->findOneBy(array('token' => $data->{'Token'}));
        $validdate = false;

        $endevent = strtotime("+1 day", strtotime($dateevent));

        if ($currentdate >= $dateevent && $currentdate <= date("Y-m-d", $endevent)) {

            $validdate = true;
        }

        if (!isset($user))
        {

            return new JsonResponse(array('response' => 'KO'), 404);
        }
        if ($beacon && $event && $qrCodeStatus && $validdate == true) {
            return new JsonResponse(array('response' => 'OK'));
        }
        return new JsonResponse(array('response' => 'KO'), 404);
    }
}
