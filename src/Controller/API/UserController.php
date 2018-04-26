<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/login")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="user_api_api", methods="GET")
     */
    public function api(): Response
    {
        return $this->json(['user'=>'Hello world']);
    }
}
