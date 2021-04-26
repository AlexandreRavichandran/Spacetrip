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
     * @Route("/admin/home", name="admin_home")
     */
    public function index(TripRepository $tripRepository, SpacecraftRepository $spacecraftRepository): Response
    {

        $trips = $tripRepository->findLatestTrips('createdAt', 5);
        $spacecrafts = $spacecraftRepository->findLatestSpacecrafts('createdAt', 5);
        return $this->render('admin/index.html.twig', [
            'trips' => $trips,
            'spacecrafts' => $spacecrafts
        ]);
    }
}
