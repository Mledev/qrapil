<?php

namespace App\Controller\Backend;

use App\Entity\Action;
use App\Form\ActionType;
use App\Repository\ActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/action")
 */
class ActionController extends Controller
{
    /**
     * @Route("/", name="action_index", methods="GET")
     */
    public function index(ActionRepository $actionRepository): Response
    {
        return $this->render('action/index.html.twig', ['actions' => $actionRepository->findAll()]);
    }

    /**
     * @Route("/new", name="action_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $action = new Action();
        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();

            return $this->redirectToRoute('action_index');
        }

        return $this->render('action/new.html.twig', [
            'action' => $action,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="action_show", methods="GET")
     */
    public function show(Action $action): Response
    {
        return $this->render('action/show.html.twig', ['action' => $action]);
    }

    /**
     * @Route("/{id}/edit", name="action_edit", methods="GET|POST")
     */
    public function edit(Request $request, Action $action): Response
    {
        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('action_edit', ['id' => $action->getId()]);
        }

        return $this->render('action/edit.html.twig', [
            'action' => $action,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="action_delete", methods="DELETE")
     */
    public function delete(Request $request, Action $action): Response
    {
        if ($this->isCsrfTokenValid('delete'.$action->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($action);
            $em->flush();
        }

        return $this->redirectToRoute('action_index');
    }
}
