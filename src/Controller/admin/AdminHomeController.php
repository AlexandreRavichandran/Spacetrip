<?php

namespace App\Controller\admin;

use App\Repository\FeedbackRepository;
use App\Repository\SpacecraftRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    /**
     * Show latest updated trips
     * @Route("/admin/home/trips", name="app_admin_trip_home")
     * @return Response
     */
    public function showLatestTrips(TripRepository $repo): Response
    {
        $trips = $repo->findBy(['reserved' => false], ['createdAt' => 'DESC'], 5);
        return $this->render('admin/index.html.twig', [
            'trips' => $trips,
            'class' => 'trip',
            'reserved' => false,
        ]);
    }

    /**
     * Show latest updated spacecrafts
     * @Route("/admin/home/spacecrafts", name="app_admin_spacecraft_home")
     * @return Response
     */
    public function showLatestSpacecrafts(SpacecraftRepository $repo): Response
    {
        $spacecrafts = $repo->findLatestSpacecrafts('updatedAt', 5);
        return $this->render('admin/index.html.twig', [
            'spacecrafts' => $spacecrafts,
            'class' => 'spacecraft'
        ]);
    }

    /**
     * Show Latest registrated users
     * @Route("/admin/home/users", name="app_admin_user_home")
     * @return Response
     */
    public function showLatestUsers(UserRepository $repo): Response
    {
        $users = $repo->findBy([], ['createdAt' => 'DESC'], 5);
        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'class' => 'user'
        ]);
    }

    /**
     * Show latest feedbacks by users
     * @Route("/admin/home/feedbacks", name="app_admin_feedback_home")
     * @return Response
     */
    public function showLatestFeedbacks(FeedbackRepository $repo): Response
    {
        $feedbacks = $repo->findBy([], ['createdAt' => 'DESC'], 5);
        return $this->render('admin/index.html.twig', [
            'feedbacks' => $feedbacks,
            'class' => 'feedback'
        ]);
    }

    /**
     * Show latest trips reserved by users
     * @Route("/admin/home/reserved_trips", name="app_admin_reserved_trips_home")
     * @return Response
     */
    public function showLatestReservedTrips(TripRepository $repo): Response
    {
        $trips = $repo->findBy(['reserved' => true], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/index.html.twig', [
            'trips' => $trips,
            'class' => 'trip',
            'reserved' => true,
        ]);
    }
}
