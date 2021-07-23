<?php

namespace App\Controller\admin;

use App\Repository\DestinationRepository;
use App\Repository\FeedbackRepository;
use App\Repository\SpacecraftRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    private $tripRepository;
    private $feedbackRepository;
    private $destinationRepository;
    private $userRepository;
    private $spacecraftRepository;

    /**
     * Construct function of AdminHomeController
     */
    public function __construct(
        TripRepository $tripRepo,
        FeedbackRepository $feedbackRepo,
        SpacecraftRepository $spacecraftRepo,
        UserRepository $userRepo,
        DestinationRepository $destinationRepo
    ) {
        $this->tripRepository = $tripRepo;
        $this->feedbackRepository = $feedbackRepo;
        $this->destinationRepository = $destinationRepo;
        $this->userRepository = $userRepo;
        $this->spacecraftRepository = $spacecraftRepo;
    }


    /**
     * Show the admin homepage
     * @Route("/admin",name="app_admin_home")
     * @return Response
     */
    public function showDashboard(): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        //Calculate the percentage of use of each Spacecrafts in trips & put in array
        $spacecrafts = $this->spacecraftRepository->findAll();
        $spacecraftPercentages = [];
        foreach ($spacecrafts as $spacecraft) {
            $percentage = (count($spacecraft->getTrip()) / count($this->tripRepository->findAll())) * 100;
            array_push($spacecraftPercentages, $percentage);
        }

        //Calculate the percentage of use of each Destinations in trips & put in array
        $destinations = $this->destinationRepository->findAll();
        $destinationPercentages = [];
        foreach ($destinations as $destination) {
            $percentage = (count($destination->getTrips()) / count($this->tripRepository->findAll())) * 100;
            array_push($destinationPercentages, $percentage);
        }

        return $this->render('admin/index.html.twig', [
            'spacecrafts' => $spacecrafts,
            'spacecraftPercentages' => $spacecraftPercentages,
            'destinations' => $destinations,
            'destinationPercentages' => $destinationPercentages,
            'class' => 'home'
        ]);
    }
    /**
     * Show latest updated trips
     * @Route("/admin/home/trips", name="app_admin_trip_show_latest")
     * @return Response
     */
    public function showLatestTrips(): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $trips = $this->tripRepository->findBy(['reserved' => false], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/index.html.twig', [
            'trips' => $trips,
            'class' => 'trip',
            'reserved' => false,
        ]);
    }

    /**
     * Show latest updated spacecrafts
     * @Route("/admin/home/spacecrafts", name="app_admin_spacecraft_show_latest")
     * @return Response
     */
    public function showLatestSpacecrafts(): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $spacecrafts = $this->spacecraftRepository->findLatestSpacecrafts('updatedAt', 5);

        return $this->render('admin/index.html.twig', [
            'spacecrafts' => $spacecrafts,
            'class' => 'spacecraft'
        ]);
    }

    /**
     * Show Latest registrated users
     * @Route("/admin/home/users", name="app_admin_user_show_latest")
     * @return Response
     */
    public function showLatestUsers(): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $users = $this->userRepository->showLatestUsers('ROLE_USER', 5);

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'class' => 'user'
        ]);
    }

    /**
     * Show latest feedbacks by users
     * @Route("/admin/home/feedbacks", name="app_admin_feedback_show_latest")
     * @return Response
     */
    public function showLatestFeedbacks(): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $feedbacks = $this->feedbackRepository->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/index.html.twig', [
            'feedbacks' => $feedbacks,
            'class' => 'feedback'
        ]);
    }

    /**
     * Show latest trips reserved by users
     * @Route("/admin/home/reserved_trips", name="app_admin_reserved_trips_show_latest")
     * @return Response
     */
    public function showLatestReservedTrips(): Response
    {
        //Check if user connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $trips = $this->tripRepository->findBy(['reserved' => true], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/index.html.twig', [
            'trips' => $trips,
            'class' => 'trip',
            'reserved' => true,
        ]);
    }

    /**
     * Show latests destinations 
     * @Route("/admin/home/destinations",name="app_admin_destination_show_latest")
     * @return Response
     */
    public function showLatestDestinations(): Response
    {
        //Check if user is connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Veuillez vous connecter.");

        $destinations = $this->destinationRepository->findBy([], ['updatedAt' => 'DESC'], 5);

        return $this->render('admin/index.html.twig', [
            'destinations' => $destinations,
            'class' => 'destination'
        ]);
    }
}
