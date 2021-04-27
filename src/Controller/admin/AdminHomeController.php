<?php

namespace App\Controller\admin;

use App\Repository\SpacecraftRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    /**
     * Show latest updated trips and spacecrafts
     * @Route("/admin/home", name="app_admin_home")
     * @return Response
     */
    public function index(TripRepository $tripRepository, SpacecraftRepository $spacecraftRepository): Response
    {

        $trips = $tripRepository->findLatestTrips('updatedAt', 5);
        $spacecrafts = $spacecraftRepository->findLatestSpacecrafts('updatedAt', 5);
        return $this->render('admin/index.html.twig', [
            'trips' => $trips,
            'spacecrafts' => $spacecrafts
        ]);
    }
}
