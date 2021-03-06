<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Repository\FeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    public function __construct()
    {
    }
    /**
     * @Route("/profile", name="app_user_profile")
     * @return Response
     */
    public function index(FeedbackRepository $feedbackRepo, TripRepository $tripRepo): Response
    {
        //Check if user is connected
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Veuillez vous connecter');

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
    public function edit(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        //Check if user is connected
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Veuillez vous connecter');

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $em->flush();
            $this->addFlash('success', 'Vos informations ont bien ??t?? modifi??s.');
            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Function to generate random user data to connect to an account on login page (AJAX Request)
     * @Route("/login/getUserData", name="app_user_ajax_getUserData")
     * @return 
     */
    public function generateUserData(UserRepository $repo, Request $request)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $allUser = $repo->filterByRoles('ROLE_USER');;
            $randomUser = $allUser[mt_rand(0, count($allUser))];

            $jsonData = [
                'username' => $randomUser->getEmail(),
            ];

            return new JsonResponse($jsonData);
        }
    }
}
