<?php

namespace App\Controller;


use App\Repository\FeedbackRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="app_user_profile")
     */
    public function index(FeedbackRepository $feedbackRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, "Veuillez vous connecter.");
        $user = $this->getUser();
        $feedbacks = $feedbackRepo->findBy(['user' => $user->getId()], ['createdAt' => 'DESC']);
        return $this->render('user/index.html.twig', [
            'feedbacks' => $feedbacks
        ]);
    }
}
