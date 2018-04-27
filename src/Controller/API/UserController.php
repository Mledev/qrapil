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

        if ($connexion->{'Email'} === $user->getEmail()){
            if($connexion->{'Password'} === $user->getPassword()){
                $token = $user->getToken();
            }
        }

        return new JsonResponse(array('token' => $token));
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
        $mail = $user->getEmail();
        $newToken =  md5($mail.random_bytes(20));
        $user->setToken($newToken);
        $entityManager->flush();

        return new JsonResponse(array('Token' => $newToken));
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
        $advert = $userRepository->findOneBy(array('token' => $token->{'token'}));


        /*foreach ($users as $user) {
            if ($token->{'token'} === $user->getToken()) {
                foreach ($events as $event){
                    $dateevent = $event->getDate();
                    if($currentdate >= $dateevent && $currentdate <= $dateevent->add(new DateInterval('P1H'))){
                        return new JsonResponse(array('date' => $event->getDate()));
                    }
                }
            }
        }*/
        return new JsonResponse(array('error' => $advert->getToken()));
    }
}
