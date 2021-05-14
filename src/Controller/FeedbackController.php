<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Entity\Spacecraft;
use App\Form\FeedbackType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FeedbackController extends AbstractController
{
    /**
     * Add a feedback on a spacecraft
     * @Route("/{id}/feedback/create", name="app_feedback_create")
     */
    public function index(Spacecraft $spacecraft, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, "Veuillez vous connecter.");
        $user = $this->getUser();
        $feedback = new Feedback;
        $feedback->setUser($user)->setSpacecraft($spacecraft);
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $feedback = $form->getData();
            $feedback->setUser($user)->setSpacecraft($spacecraft);
            $spacecraft->addFeedback($feedback);
            $em->persist($feedback);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire a été posté avec succès.');
            return $this->redirectToRoute('app_spacecraft_show', ['id' => $spacecraft->getId()]);
        }
        return $this->render('feedback/create.html.twig', [
            'spacecraft' => $spacecraft,
            'form' => $form->createView()
        ]);
    }
}
