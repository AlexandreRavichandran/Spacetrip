<?php

namespace App\Controller;

use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Show homepage
     * @Route("/", name="app_home")
     * @return Response
     */
    public function index(TripRepository $repo): Response
    {
        $trips = $repo->findLatestTrips();
        return $this->render('home/index.html.twig', [
            'trips' => $trips
        ]);
    }
}
