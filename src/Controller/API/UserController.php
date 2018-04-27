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
        $users = $userRepository->findAll();
        $connexion = $request->getContent();
        $token = 0;
        $connexion = json_decode($connexion, false , 2);
        $user = $userRepository->findOneBy(array('email' => $connexion->{'Email'}));
        if (!isset($user)){

            return new JsonResponse(array('error' => 'Email invalid'), 404);
        }else{
            if($connexion->{'Password'} === $user->getPassword()){
                $token = $user->getToken();
            }else{
                return new JsonResponse(array('error' => 'Password invalid'), 404);
            }

            return new JsonResponse(array('token' => $token));
        }


    }

    /**
     * @Route("/refreshToken", name="refresh_token", methods="POST")
     */
    public function refreshToken(Request $request, UserRepository $userRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $connexion = $request->getContent();
        $token = json_decode($connexion, false , 2);
        $user = $userRepository->findOneBy(array('token' => $token->{'Token'}));
        if (!isset($user)) {

            return new JsonResponse(array('error' => 'Token invalid'), 404);
        }else{
            $mail = $user->getEmail();
            $newToken =  md5($mail.random_bytes(20));
            $user->setToken($newToken);
            $entityManager->flush();

            return new JsonResponse(array('Token' => $newToken));
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
}
