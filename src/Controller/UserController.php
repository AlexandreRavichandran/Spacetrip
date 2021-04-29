<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\FeedbackRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="app_user_profile")
     */
    public function index(FeedbackRepository $feedbackRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, "Veuillez vous connecter.");
        $user = $this->getUser();
        $feedbacks = $feedbackRepo->findBy(['user' => $user->getId()], null, 5);
        return $this->render('user/index.html.twig', [
            'feedbacks' => $feedbacks
        ]);
    }
}
