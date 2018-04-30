<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ActionRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stats")
 */
class StatsController extends Controller
{
    /**
     * @Route("/", name="stats_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('stats/index.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/{id}", name="stats_user", methods="GET")
     */
    public function user(User $user, ActionRepository $actionRepository, EventRepository $eventRepository): Response
    {
        return $this->render('stats/user.html.twig', [
		'events' => $eventRepository->findAll(),
		'actions' => $actionRepository->findByUserId($user->getId())
	]);
    }

}
