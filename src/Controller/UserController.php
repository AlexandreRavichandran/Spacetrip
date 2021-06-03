<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\TripRepository;
use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="app_user_profile")
     * @return Response
     */
    public function index(FeedbackRepository $feedbackRepo, TripRepository $tripRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, "Veuillez vous connecter.");
        $user = $this->getUser();
        $feedbacks = $feedbackRepo->findBy(['user' => $user->getId()], ['createdAt' => 'DESC']);
        $reservedTrips = $tripRepo->findUserTrips($user->getEmail());
        $trips = $user->getTrip();
        return $this->render('user/index.html.twig', [
            'feedbacks' => $feedbacks,
            'reservedTrips' => $reservedTrips,
            'trips' => $trips
        ]);
    }

    /**
     * Edit user's information
     * @Route("/profile/edit",name="app_user_edit")
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $em, UserRepository $repo): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $repo->upgradePassword($user, $user->getPassword());
            $em->flush();
            $this->addFlash('success', 'Vos informations ont bien été modifiés.');
            return $this->redirectToRoute('app_user_profile');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
