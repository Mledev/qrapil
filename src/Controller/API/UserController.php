<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
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

        foreach ($users as $user) {
            if ($connexion->{'Email'} === $user->getEmail()){
                if($connexion->{'Password'} === $user->getPassword()){
                    $token = $user->getToken();
                }
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
        $users = $userRepository->findAll();
        $connexion = $request->getContent();
        $token = json_decode($connexion, false , 2);

        foreach ($users as $user) {
            if ($token->{'Token'} === $user->getToken()){
                $newToken = $random = random_bytes(20);
                $newToken = bin2hex($newToken);
                $user->setToken($newToken);
            }
        }

        $entityManager->flush();

        return new JsonResponse(array('Token' => $newToken));
    }
}
